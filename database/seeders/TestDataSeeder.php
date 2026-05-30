<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\AuctionCategory;
use App\Models\AuctionLot;
use App\Models\VirtualAccount;
use App\Models\Bid;
use App\Models\Payment;
use App\Models\AuctionWinner;
use App\Models\Notification;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test user
        $testUser = User::firstOrCreate(
            ['phone_number' => '0895349417505'],
            [
                'full_name' => 'Test User',
                'email' => 'testuser@example.com',
                'password_hash' => bcrypt('password123'),
                'role' => 'BIDDER',
                'status' => 'ACTIVE',
                'nik' => '1234567890123456',
                'npwp' => '123456789012345'
            ]
        );

        // Create additional test users
        $testUser2 = User::firstOrCreate(
            ['email' => 'testuser2@example.com'],
            [
                'full_name' => 'Test User 2',
                'password_hash' => bcrypt('password123'),
                'phone_number' => '0812345678901',
                'role' => 'BIDDER',
                'status' => 'ACTIVE',
                'nik' => '1234567890123457',
                'npwp' => '123456789012346'
            ]
        );

        $testUser3 = User::firstOrCreate(
            ['email' => 'testuser3@example.com'],
            [
                'full_name' => 'Test User 3',
                'password_hash' => bcrypt('password123'),
                'phone_number' => '0812345678902',
                'role' => 'BIDDER',
                'status' => 'ACTIVE',
                'nik' => '1234567890123458',
                'npwp' => '123456789012347'
            ]
        );

        // Create test category
        $category = AuctionCategory::firstOrCreate(
            ['name' => 'Test Category'],
            ['description' => 'Category for testing purposes']
        );

        // Create test lots with different statuses
        
        // Get admin user
        $admin = User::where('role', 'ADMIN')->first();

        // 1. IN PROGRESS lot (ongoing auction)
        $inProgressLot = AuctionLot::firstOrCreate(
            ['title' => 'Test Lot - In Progress'],
            [
                'category_id' => $category->id,
                'admin_id' => $admin->id,
                'description' => 'Test auction lot currently in progress',
                'starting_price' => 1000000,
                'start_time' => Carbon::now()->subHours(2),
                'end_time' => Carbon::now()->addHours(2),
                'status' => 'ONGOING',
                'bid_type' => 'OPEN',
                'requires_deposit' => true,
                'location' => 'Test Location'
            ]
        );

        // 2. FINISHED lot (auction ended, winner determined)
        $finishedLot = AuctionLot::firstOrCreate(
            ['title' => 'Test Lot - Finished'],
            [
                'category_id' => $category->id,
                'admin_id' => $admin->id,
                'description' => 'Test auction lot that has finished',
                'starting_price' => 2000000,
                'start_time' => Carbon::now()->subDays(3),
                'end_time' => Carbon::now()->subDays(1),
                'status' => 'FINISHED',
                'bid_type' => 'OPEN',
                'requires_deposit' => true,
                'location' => 'Test Location'
            ]
        );

        // 3. FINISHED lot (settlement completed)
        $closedLot = AuctionLot::firstOrCreate(
            ['title' => 'Test Lot - Finished Settlement'],
            [
                'category_id' => $category->id,
                'admin_id' => $admin->id,
                'description' => 'Test auction lot with completed settlement',
                'starting_price' => 1500000,
                'start_time' => Carbon::now()->subDays(7),
                'end_time' => Carbon::now()->subDays(5),
                'status' => 'FINISHED',
                'bid_type' => 'CLOSED',
                'requires_deposit' => true,
                'location' => 'Test Location'
            ]
        );

        // Get default bank
        $bank = \App\Models\Bank::first();

        // Create virtual accounts for each lot
        $inProgressVA = VirtualAccount::firstOrCreate(
            ['lot_id' => $inProgressLot->id, 'type' => 'DEPOSIT'],
            [
                'bank_id' => $bank->id,
                'va_number' => '1234567890001',
                'amount' => 500000,
                'status' => 'ACTIVE'
            ]
        );

        $finishedDepositVA = VirtualAccount::firstOrCreate(
            ['lot_id' => $finishedLot->id, 'type' => 'DEPOSIT'],
            [
                'bank_id' => $bank->id,
                'va_number' => '1234567890002',
                'amount' => 1000000,
                'status' => 'ACTIVE'
            ]
        );

        $finishedSettlementVA = VirtualAccount::firstOrCreate(
            ['lot_id' => $finishedLot->id, 'type' => 'SETTLEMENT'],
            [
                'bank_id' => $bank->id,
                'va_number' => '1234567890003',
                'amount' => 2000000,
                'status' => 'ACTIVE'
            ]
        );

        $closedDepositVA = VirtualAccount::firstOrCreate(
            ['lot_id' => $closedLot->id, 'type' => 'DEPOSIT'],
            [
                'bank_id' => $bank->id,
                'va_number' => '1234567890004',
                'amount' => 750000,
                'status' => 'ACTIVE'
            ]
        );

        $closedSettlementVA = VirtualAccount::firstOrCreate(
            ['lot_id' => $closedLot->id, 'type' => 'SETTLEMENT'],
            [
                'bank_id' => $bank->id,
                'va_number' => '1234567890005',
                'amount' => 1750000,
                'status' => 'ACTIVE'
            ]
        );

        // Create deposit payments for all users on all lots
        $deposits = [
            ['user' => $testUser, 'va' => $inProgressVA, 'amount' => 500000],
            ['user' => $testUser2, 'va' => $inProgressVA, 'amount' => 500000],
            ['user' => $testUser, 'va' => $finishedDepositVA, 'amount' => 1000000],
            ['user' => $testUser2, 'va' => $finishedDepositVA, 'amount' => 1000000],
            ['user' => $testUser3, 'va' => $finishedDepositVA, 'amount' => 1000000],
            ['user' => $testUser, 'va' => $closedDepositVA, 'amount' => 750000],
            ['user' => $testUser2, 'va' => $closedDepositVA, 'amount' => 750000],
        ];

        foreach ($deposits as $depositData) {
            Payment::firstOrCreate(
                [
                    'user_id' => $depositData['user']->id,
                    'va_id' => $depositData['va']->id
                ],
                [
                    'amount' => $depositData['amount'],
                    'status' => 'VERIFIED',
                    'proof_file' => 'test-proof-' . $depositData['user']->id . '-' . $depositData['va']->id . '.jpg',
                    'verified_at' => Carbon::now()->subDays(1)
                ]
            );
        }

        // Create bids for the in-progress lot
        $inProgressBids = [
            ['user' => $testUser, 'amount' => 1100000],
            ['user' => $testUser2, 'amount' => 1200000],
            ['user' => $testUser, 'amount' => 1300000],
        ];

        foreach ($inProgressBids as $bidData) {
            Bid::firstOrCreate(
                [
                    'lot_id' => $inProgressLot->id,
                    'bidder_id' => $bidData['user']->id,
                    'amount' => $bidData['amount']
                ],
                [
                    'created_at' => Carbon::now()->subMinutes(rand(10, 120))
                ]
            );
        }

        // Create bids for the finished lot and set winner
        $finishedBids = [
            ['user' => $testUser, 'amount' => 2100000],
            ['user' => $testUser2, 'amount' => 2300000],
            ['user' => $testUser3, 'amount' => 2500000],
            ['user' => $testUser, 'amount' => 2700000],
            ['user' => $testUser2, 'amount' => 3000000], // Winner
        ];

        foreach ($finishedBids as $bidData) {
            Bid::firstOrCreate(
                [
                    'lot_id' => $finishedLot->id,
                    'bidder_id' => $bidData['user']->id,
                    'amount' => $bidData['amount']
                ],
                [
                    'created_at' => Carbon::now()->subDays(2)->addMinutes(rand(10, 120))
                ]
            );
        }

        // Set winner for finished lot
        AuctionWinner::firstOrCreate(
            ['lot_id' => $finishedLot->id],
            [
                'user_id' => $testUser2->id,
                'final_price' => 3000000,
                'deposit_amount' => 1000000,
                'settlement_due' => Carbon::now()->addDays(5),
                'status' => 'SETTLED',
                'created_at' => Carbon::now()->subDays(1)
            ]
        );

        // Create settlement payment for winner
        Payment::firstOrCreate(
            [
                'user_id' => $testUser2->id,
                'va_id' => $finishedSettlementVA->id
            ],
            [
                'amount' => 2000000, // 3000000 - 1000000 deposit
                'status' => 'VERIFIED',
                'proof_file' => 'test-settlement-proof-' . $testUser2->id . '.jpg',
                'verified_at' => Carbon::now()->subHours(12)
            ]
        );

        echo "Test data seeder completed successfully!\n";
        echo "Test user created: {$testUser->full_name} (Phone: {$testUser->phone_number})\n";
        echo "Test lots created: In Progress, Finished, Closed\n";
        echo "Virtual accounts and payments created\n";
        echo "Bids and winners created\n";
        echo "Settlement payment created for winner\n";
    }
}
