<?php

namespace App\Services;

use App\Models\AuctionLot;
use App\Models\AuctionWinner;
use App\Models\Bid;
use App\Models\Payment;
use App\Models\VirtualAccount;
use App\Models\Notification;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuctionManagementService
{
    /**
     * Update auction statuses based on current time.
     */
    public function updateAuctionStatuses()
    {
        $now = now();
        $updatedCount = 0;

        // Update SCHEDULED auctions to ONGOING
        $scheduledAuctions = AuctionLot::where('status', 'SCHEDULED')
            ->where('start_time', '<=', $now)
            ->get();

        foreach ($scheduledAuctions as $auction) {
            $auction->update(['status' => 'ONGOING']);
            $updatedCount++;
            
            Log::info('Auction started', [
                'auction_id' => $auction->id,
                'title' => $auction->title
            ]);
        }

        // Update ONGOING auctions to FINISHED
        $ongoingAuctions = AuctionLot::where('status', 'ONGOING')
            ->where('end_time', '<=', $now)
            ->get();

        foreach ($ongoingAuctions as $auction) {
            $this->finishAuction($auction);
            $updatedCount++;
        }

        return $updatedCount;
    }

    /**
     * Finish an auction and determine the winner.
     */
    public function finishAuction(AuctionLot $auction)
    {
        try {
            DB::beginTransaction();

            // Update auction status
            $auction->update(['status' => 'FINISHED']);

            // Get the highest bid
            $winningBid = Bid::where('lot_id', $auction->id)
                ->orderBy('amount', 'desc')
                ->first();

            if ($winningBid) {
                // Check if winner already exists (prevent duplicates)
                $existingWinner = AuctionWinner::where('lot_id', $auction->id)->first();
                
                if (!$existingWinner) {
                    // Calculate deposit amount if applicable
                    $depositAmount = 0;
                    if ($auction->requires_deposit) {
                        $depositVA = VirtualAccount::where('lot_id', $auction->id)
                            ->where('type', 'DEPOSIT')
                            ->first();
                        
                        if ($depositVA) {
                            $depositPayment = Payment::where('va_id', $depositVA->id)
                                ->where('user_id', $winningBid->bidder_id)
                                ->where('status', 'VERIFIED')
                                ->first();
                            
                            $depositAmount = $depositPayment ? $depositPayment->amount : 0;
                        }
                    }

                    // Calculate settlement due date (5 business days)
                    $settlementDue = $this->calculateSettlementDueDate();

                    // Create winner record
                    $winner = AuctionWinner::create([
                        'lot_id' => $auction->id,
                        'user_id' => $winningBid->bidder_id,
                        'final_price' => $winningBid->amount,
                        'deposit_amount' => $depositAmount,
                        'settlement_due' => $settlementDue,
                        'status' => 'PENDING'
                    ]);

                    // Create notifications using NotificationService
                    $notificationService = new NotificationService();
                    $notificationService->notifyAuctionWinner($winner);
                    $notificationService->notifyAuctionLosers($auction, $winner);

                    Log::info('Auction finished with winner', [
                        'auction_id' => $auction->id,
                        'winner_id' => $winningBid->bidder_id,
                        'final_price' => $winningBid->amount
                    ]);
                }
            } else {
                Log::info('Auction finished without bids', [
                    'auction_id' => $auction->id
                ]);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to finish auction', [
                'auction_id' => $auction->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Calculate settlement due date (5 business days from now).
     */
    private function calculateSettlementDueDate()
    {
        $date = now();
        $businessDays = 0;
        
        while ($businessDays < 5) {
            $date->addDay();
            
            // Skip weekends (Saturday = 6, Sunday = 0)
            if ($date->dayOfWeek !== 0 && $date->dayOfWeek !== 6) {
                $businessDays++;
            }
        }
        
        // Set time to end of business day (17:00)
        $date->setTime(17, 0, 0);
        
        return $date;
    }

    /**
     * Create notification for auction winner.
     */
    private function createWinnerNotification(AuctionWinner $winner)
    {
        $remainingAmount = $winner->final_price - $winner->deposit_amount;
        
        Notification::create([
            'user_id' => $winner->user_id,
            'title' => 'Selamat! Anda Memenangkan Lelang',
            'message' => "Anda telah memenangkan lelang '{$winner->lot->title}' dengan harga Rp " . 
                        number_format($winner->final_price, 0, ',', '.') . 
                        ". Silakan lakukan pembayaran settlement sebesar Rp " . 
                        number_format($remainingAmount, 0, ',', '.') . 
                        " sebelum {$winner->settlement_due->format('j F Y H:i')}.",
            'type' => 'AUCTION_WON',
            'data' => json_encode([
                'lot_id' => $winner->lot_id,
                'final_price' => $winner->final_price,
                'deposit_amount' => $winner->deposit_amount,
                'remaining_amount' => $remainingAmount,
                'settlement_due' => $winner->settlement_due->toISOString()
            ]),
            'is_read' => false
        ]);
    }

    /**
     * Create notifications for losing bidders.
     */
    private function createLosingBidderNotifications(AuctionLot $auction, $winnerId)
    {
        $losingBidders = Bid::where('lot_id', $auction->id)
            ->where('bidder_id', '!=', $winnerId)
            ->distinct('bidder_id')
            ->pluck('bidder_id');

        foreach ($losingBidders as $bidderId) {
            Notification::create([
                'user_id' => $bidderId,
                'title' => 'Lelang Berakhir',
                'message' => "Lelang '{$auction->title}' telah berakhir. Sayangnya Anda tidak memenangkan lelang ini. " .
                           ($auction->requires_deposit ? "Deposit Anda akan dikembalikan dalam 3-5 hari kerja." : ""),
                'type' => 'AUCTION_LOST',
                'data' => json_encode([
                    'lot_id' => $auction->id,
                    'requires_refund' => $auction->requires_deposit
                ]),
                'is_read' => false
            ]);
        }
    }

    /**
     * Check for overdue settlements and mark as defaulted.
     */
    public function checkOverdueSettlements()
    {
        $overdueWinners = AuctionWinner::where('status', 'PENDING')
            ->where('settlement_due', '<', now())
            ->get();

        foreach ($overdueWinners as $winner) {
            $winner->update(['status' => 'DEFAULTED']);
            
            // Create notification for defaulted winner
            Notification::create([
                'user_id' => $winner->user_id,
                'title' => 'Settlement Terlambat',
                'message' => "Pembayaran settlement untuk lelang '{$winner->lot->title}' telah melewati batas waktu. " .
                           "Silakan hubungi administrator untuk penyelesaian lebih lanjut.",
                'type' => 'SETTLEMENT_OVERDUE',
                'data' => json_encode([
                    'lot_id' => $winner->lot_id,
                    'final_price' => $winner->final_price
                ]),
                'is_read' => false
            ]);

            Log::warning('Settlement overdue', [
                'winner_id' => $winner->id,
                'lot_id' => $winner->lot_id,
                'user_id' => $winner->user_id,
                'settlement_due' => $winner->settlement_due
            ]);
        }

        return $overdueWinners->count();
    }

    /**
     * Send settlement reminders for winners approaching deadline.
     */
    public function sendSettlementReminders()
    {
        // Send reminder 24 hours before deadline
        $reminderDate = now()->addDay();
        
        $upcomingDeadlines = AuctionWinner::where('status', 'PENDING')
            ->whereBetween('settlement_due', [now(), $reminderDate])
            ->get();

        foreach ($upcomingDeadlines as $winner) {
            $remainingAmount = $winner->final_price - $winner->deposit_amount;
            $hoursLeft = now()->diffInHours($winner->settlement_due);
            
            Notification::create([
                'user_id' => $winner->user_id,
                'title' => 'Pengingat Settlement',
                'message' => "Pembayaran settlement untuk lelang '{$winner->lot->title}' akan berakhir dalam {$hoursLeft} jam. " .
                           "Silakan segera lakukan pembayaran sebesar Rp " . 
                           number_format($remainingAmount, 0, ',', '.') . ".",
                'type' => 'SETTLEMENT_REMINDER',
                'data' => json_encode([
                    'lot_id' => $winner->lot_id,
                    'remaining_amount' => $remainingAmount,
                    'hours_left' => $hoursLeft
                ]),
                'is_read' => false
            ]);
        }

        return $upcomingDeadlines->count();
    }

    /**
     * Get auction statistics.
     */
    public function getAuctionStatistics()
    {
        return [
            'total_auctions' => AuctionLot::count(),
            'scheduled_auctions' => AuctionLot::where('status', 'SCHEDULED')->count(),
            'ongoing_auctions' => AuctionLot::where('status', 'ONGOING')->count(),
            'finished_auctions' => AuctionLot::where('status', 'FINISHED')->count(),
            'cancelled_auctions' => AuctionLot::where('status', 'CANCELLED')->count(),
            'total_bids' => Bid::count(),
            'total_winners' => AuctionWinner::count(),
            'pending_settlements' => AuctionWinner::where('status', 'PENDING')->count(),
            'overdue_settlements' => AuctionWinner::where('status', 'PENDING')
                ->where('settlement_due', '<', now())->count()
        ];
    }
}
