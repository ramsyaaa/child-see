<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\AuctionLot;
use App\Models\Bid;
use App\Models\AuctionWinner;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Create notification for successful bid placement.
     */
    public function notifyBidPlaced(Bid $bid)
    {
        try {
            Notification::create([
                'user_id' => $bid->bidder_id,
                'type' => 'BID_PLACED',
                'title' => 'Penawaran Berhasil Ditempatkan',
                'message' => 'Penawaran Anda sebesar ' . formatIDR($bid->amount) . ' untuk "' . $bid->lot->title . '" berhasil ditempatkan.',
                'data' => json_encode([
                    'bid_id' => $bid->id,
                    'lot_id' => $bid->lot_id,
                    'amount' => $bid->amount,
                    'lot_title' => $bid->lot->title
                ])
            ]);

            Log::info('Bid placed notification created', [
                'bid_id' => $bid->id,
                'user_id' => $bid->bidder_id,
                'amount' => $bid->amount
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to create bid placed notification', [
                'bid_id' => $bid->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Create notification when user is outbid.
     */
    public function notifyOutbid(Bid $previousHighestBid, Bid $newBid)
    {
        try {
            // Don't notify if the same user placed both bids
            if ($previousHighestBid->bidder_id === $newBid->bidder_id) {
                return;
            }

            Notification::create([
                'user_id' => $previousHighestBid->bidder_id,
                'type' => 'OUTBID',
                'title' => 'Anda Telah Disalip',
                'message' => 'Penawaran Anda untuk "' . $previousHighestBid->lot->title . '" telah disalip. Penawaran tertinggi sekarang: ' . formatIDR($newBid->amount),
                'data' => json_encode([
                    'lot_id' => $previousHighestBid->lot_id,
                    'your_bid' => $previousHighestBid->amount,
                    'new_highest_bid' => $newBid->amount,
                    'lot_title' => $previousHighestBid->lot->title
                ])
            ]);

            Log::info('Outbid notification created', [
                'outbid_user_id' => $previousHighestBid->bidder_id,
                'new_bidder_id' => $newBid->bidder_id,
                'lot_id' => $previousHighestBid->lot_id
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to create outbid notification', [
                'previous_bid_id' => $previousHighestBid->id,
                'new_bid_id' => $newBid->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Create notification for auction ending soon.
     */
    public function notifyAuctionEndingSoon(AuctionLot $lot, $hoursRemaining)
    {
        try {
            // Get all bidders for this auction
            $bidderIds = $lot->bids()->distinct('bidder_id')->pluck('bidder_id');

            foreach ($bidderIds as $bidderId) {
                Notification::create([
                    'user_id' => $bidderId,
                    'type' => 'AUCTION_ENDING_SOON',
                    'title' => 'Lelang Akan Berakhir',
                    'message' => 'Lelang "' . $lot->title . '" akan berakhir dalam ' . $hoursRemaining . ' jam. Jangan lewatkan kesempatan terakhir!',
                    'data' => json_encode([
                        'lot_id' => $lot->id,
                        'lot_title' => $lot->title,
                        'hours_remaining' => $hoursRemaining,
                        'end_time' => $lot->end_time->toISOString()
                    ])
                ]);
            }

            Log::info('Auction ending soon notifications created', [
                'lot_id' => $lot->id,
                'bidder_count' => $bidderIds->count(),
                'hours_remaining' => $hoursRemaining
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to create auction ending soon notifications', [
                'lot_id' => $lot->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Create notification for auction winner.
     */
    public function notifyAuctionWinner(AuctionWinner $winner)
    {
        try {
            Notification::create([
                'user_id' => $winner->user_id,
                'type' => 'AUCTION_WON',
                'title' => 'Selamat! Anda Memenangkan Lelang',
                'message' => 'Anda memenangkan lelang "' . $winner->lot->title . '" dengan penawaran ' . formatIDR($winner->final_price) . '. Silakan selesaikan pembayaran.',
                'data' => json_encode([
                    'winner_id' => $winner->id,
                    'lot_id' => $winner->lot_id,
                    'lot_title' => $winner->lot->title,
                    'final_price' => $winner->final_price,
                    'settlement_due' => $winner->settlement_due ? $winner->settlement_due->toISOString() : null
                ])
            ]);

            Log::info('Auction winner notification created', [
                'winner_id' => $winner->id,
                'user_id' => $winner->user_id,
                'lot_id' => $winner->lot_id,
                'final_price' => $winner->final_price
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to create auction winner notification', [
                'winner_id' => $winner->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Create notification for losing bidders.
     */
    public function notifyAuctionLosers(AuctionLot $lot, AuctionWinner $winner)
    {
        try {
            // Get all bidders except the winner
            $losingBidderIds = $lot->bids()
                ->where('bidder_id', '!=', $winner->user_id)
                ->distinct('bidder_id')
                ->pluck('bidder_id');

            foreach ($losingBidderIds as $bidderId) {
                Notification::create([
                    'user_id' => $bidderId,
                    'type' => 'AUCTION_LOST',
                    'title' => 'Lelang Berakhir',
                    'message' => 'Lelang "' . $lot->title . '" telah berakhir. Terima kasih atas partisipasi Anda. Jelajahi lelang lainnya!',
                    'data' => json_encode([
                        'lot_id' => $lot->id,
                        'lot_title' => $lot->title,
                        'winning_price' => $winner->final_price
                    ])
                ]);
            }

            Log::info('Auction loser notifications created', [
                'lot_id' => $lot->id,
                'loser_count' => $losingBidderIds->count(),
                'winner_id' => $winner->user_id
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to create auction loser notifications', [
                'lot_id' => $lot->id,
                'winner_id' => $winner->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Create notification for deposit verification.
     */
    public function notifyDepositVerified(Payment $payment)
    {
        try {
            Notification::create([
                'user_id' => $payment->user_id,
                'type' => 'DEPOSIT_VERIFIED',
                'title' => 'Deposit Terverifikasi',
                'message' => 'Deposit Anda sebesar ' . formatIDR($payment->amount) . ' untuk lelang "' . $payment->virtualAccount->lot->title . '" telah terverifikasi. Anda sekarang dapat melakukan penawaran.',
                'data' => json_encode([
                    'payment_id' => $payment->id,
                    'lot_id' => $payment->virtualAccount->lot_id,
                    'lot_title' => $payment->virtualAccount->lot->title,
                    'amount' => $payment->amount
                ])
            ]);

            Log::info('Deposit verified notification created', [
                'payment_id' => $payment->id,
                'user_id' => $payment->user_id,
                'amount' => $payment->amount
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to create deposit verified notification', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Create notification for settlement payment verification.
     */
    public function notifySettlementVerified(Payment $payment)
    {
        try {
            Notification::create([
                'user_id' => $payment->user_id,
                'type' => 'SETTLEMENT_VERIFIED',
                'title' => 'Settlement Terverifikasi',
                'message' => 'Pembayaran settlement Anda sebesar ' . formatIDR($payment->amount) . ' untuk lelang "' . $payment->virtualAccount->lot->title . '" telah terverifikasi. Transaksi selesai.',
                'data' => json_encode([
                    'payment_id' => $payment->id,
                    'lot_id' => $payment->virtualAccount->lot_id,
                    'lot_title' => $payment->virtualAccount->lot->title,
                    'amount' => $payment->amount
                ])
            ]);

            Log::info('Settlement verified notification created', [
                'payment_id' => $payment->id,
                'user_id' => $payment->user_id,
                'amount' => $payment->amount
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to create settlement verified notification', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Create notification for settlement deadline reminder.
     */
    public function notifySettlementDeadlineReminder(AuctionWinner $winner, $daysRemaining)
    {
        try {
            $urgencyLevel = $daysRemaining <= 1 ? 'URGENT' : 'REMINDER';
            
            Notification::create([
                'user_id' => $winner->user_id,
                'type' => 'SETTLEMENT_REMINDER',
                'title' => $daysRemaining <= 1 ? 'Settlement Segera Berakhir!' : 'Pengingat Settlement',
                'message' => 'Harap selesaikan pembayaran settlement untuk lelang "' . $winner->lot->title . '" dalam ' . $daysRemaining . ' hari. Batas waktu: ' . $winner->settlement_due->format('j F Y H:i'),
                'data' => json_encode([
                    'winner_id' => $winner->id,
                    'lot_id' => $winner->lot_id,
                    'lot_title' => $winner->lot->title,
                    'days_remaining' => $daysRemaining,
                    'settlement_due' => $winner->settlement_due->toISOString(),
                    'urgency_level' => $urgencyLevel
                ])
            ]);

            Log::info('Settlement deadline reminder notification created', [
                'winner_id' => $winner->id,
                'user_id' => $winner->user_id,
                'days_remaining' => $daysRemaining,
                'urgency_level' => $urgencyLevel
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to create settlement deadline reminder notification', [
                'winner_id' => $winner->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead($notificationId, $userId)
    {
        try {
            $notification = Notification::where('id', $notificationId)
                ->where('user_id', $userId)
                ->first();

            if ($notification) {
                $notification->update(['read_at' => now()]);
                return true;
            }

            return false;

        } catch (\Exception $e) {
            Log::error('Failed to mark notification as read', [
                'notification_id' => $notificationId,
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Mark all notifications as read for a user.
     */
    public function markAllAsRead($userId)
    {
        try {
            $count = Notification::where('user_id', $userId)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            Log::info('All notifications marked as read', [
                'user_id' => $userId,
                'count' => $count
            ]);

            return $count;

        } catch (\Exception $e) {
            Log::error('Failed to mark all notifications as read', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Get unread notification count for a user.
     */
    public function getUnreadCount($userId)
    {
        try {
            return Notification::where('user_id', $userId)
                ->whereNull('read_at')
                ->count();

        } catch (\Exception $e) {
            Log::error('Failed to get unread notification count', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }


}
