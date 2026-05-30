# Laravel Tinker Commands for Creating Test Auction Lots

This document provides Laravel Tinker commands to populate the database with auction lots in different states for testing the bidder dashboard functionality.

## Prerequisites

Before running these commands, ensure you have:
1. Admin user(s) in the database
2. Auction categories created
3. Banks created for virtual accounts
4. Some bidder users for testing

## Step 1: Create Basic Data (if not exists)

```php
// Create admin user if not exists
$admin = \App\Models\User::firstOrCreate(
    ['email' => 'admin@auction.com'],
    [
        'full_name' => 'System Administrator',
        'password_hash' => bcrypt('password123'),
        'phone_number' => '+62812345678',
        'role' => 'ADMIN',
        'status' => 'ACTIVE'
    ]
);

// Create auction categories
$categories = [
    ['name' => 'Elektronik', 'description' => 'Perangkat elektronik dan gadget', 'additional_tax' => 10.00],
    ['name' => 'Barang Koleksi', 'description' => 'Barang antik dan koleksi berharga', 'additional_tax' => 5.00],
    ['name' => 'Kendaraan', 'description' => 'Mobil, motor, dan kendaraan lainnya', 'additional_tax' => 15.00],
    ['name' => 'Seni & Lukisan', 'description' => 'Karya seni dan lukisan', 'additional_tax' => 7.50]
];

foreach ($categories as $categoryData) {
    \App\Models\AuctionCategory::firstOrCreate(
        ['name' => $categoryData['name']],
        $categoryData
    );
}

// Create banks
$banks = [
    ['name' => 'Bank Central Asia (BCA)', 'code' => 'BCA', 'is_active' => true],
    ['name' => 'Bank Mandiri', 'code' => 'MANDIRI', 'is_active' => true],
    ['name' => 'Bank Negara Indonesia (BNI)', 'code' => 'BNI', 'is_active' => true]
];

foreach ($banks as $bankData) {
    \App\Models\Bank::firstOrCreate(
        ['code' => $bankData['code']],
        $bankData
    );
}

// Create test bidder users
$bidders = [
    [
        'full_name' => 'John Doe',
        'email' => 'john@example.com',
        'password_hash' => bcrypt('password123'),
        'phone_number' => '+62812345679',
        'role' => 'BIDDER',
        'status' => 'ACTIVE'
    ],
    [
        'full_name' => 'Jane Smith',
        'email' => 'jane@example.com',
        'password_hash' => bcrypt('password123'),
        'phone_number' => '+62812345680',
        'role' => 'BIDDER',
        'status' => 'ACTIVE'
    ],
    [
        'full_name' => 'Bob Wilson',
        'email' => 'bob@example.com',
        'password_hash' => bcrypt('password123'),
        'phone_number' => '+62812345681',
        'role' => 'BIDDER',
        'status' => 'ACTIVE'
    ]
];

foreach ($bidders as $bidderData) {
    \App\Models\User::firstOrCreate(
        ['email' => $bidderData['email']],
        $bidderData
    );
}
```

## Step 2: Create Auction Lots in Different States

```php
// Get required data
$admin = \App\Models\User::where('role', 'ADMIN')->first();
$categories = \App\Models\AuctionCategory::all();
$banks = \App\Models\Bank::all();
$bidders = \App\Models\User::where('role', 'BIDDER')->get();

// Helper function to create virtual accounts
function createVirtualAccounts($lot, $banks) {
    $bank = $banks->random();
    
    if ($lot->requires_deposit) {
        \App\Models\VirtualAccount::create([
            'lot_id' => $lot->id,
            'bank_id' => $bank->id,
            'account_number' => '1234567890' . str_pad($lot->id, 3, '0', STR_PAD_LEFT),
            'account_name' => 'DEPOSIT - ' . substr($lot->title, 0, 20),
            'amount' => $lot->starting_price * 0.1, // 10% deposit
            'type' => 'DEPOSIT',
            'status' => 'ACTIVE'
        ]);
    }
    
    \App\Models\VirtualAccount::create([
        'lot_id' => $lot->id,
        'bank_id' => $bank->id,
        'account_number' => '9876543210' . str_pad($lot->id, 3, '0', STR_PAD_LEFT),
        'account_name' => 'SETTLEMENT - ' . substr($lot->title, 0, 20),
        'amount' => $lot->starting_price,
        'type' => 'SETTLEMENT',
        'status' => 'ACTIVE'
    ]);
}

// 1. UPCOMING AUCTIONS (Scheduled for future)
$upcomingLots = [
    [
        'category_id' => $categories->where('name', 'Elektronik')->first()->id,
        'admin_id' => $admin->id,
        'title' => 'iPhone 15 Pro Max 1TB - Brand New',
        'description' => 'Brand new iPhone 15 Pro Max with 1TB storage in Natural Titanium color.',
        'starting_price' => 22000000,
        'bid_type' => 'OPEN',
        'requires_deposit' => true,
        'start_time' => now()->addHours(6),
        'end_time' => now()->addDays(3),
        'status' => 'SCHEDULED',
        'location' => 'Jakarta'
    ],
    [
        'category_id' => $categories->where('name', 'Barang Koleksi')->first()->id,
        'admin_id' => $admin->id,
        'title' => 'Rolex Submariner Date - Vintage 1980s',
        'description' => 'Vintage Rolex Submariner Date from 1980s in excellent condition.',
        'starting_price' => 180000000,
        'bid_type' => 'CLOSED',
        'requires_deposit' => true,
        'start_time' => now()->addDays(1),
        'end_time' => now()->addDays(7),
        'status' => 'SCHEDULED',
        'location' => 'Surabaya'
    ]
];

foreach ($upcomingLots as $lotData) {
    $lot = \App\Models\AuctionLot::create($lotData);
    createVirtualAccounts($lot, $banks);
}

// 2. ONGOING AUCTIONS (Currently active)
$ongoingLots = [
    [
        'category_id' => $categories->where('name', 'Kendaraan')->first()->id,
        'admin_id' => $admin->id,
        'title' => 'BMW M3 Competition 2023',
        'description' => 'BMW M3 Competition 2023 in Alpine White, low mileage, perfect condition.',
        'starting_price' => 1500000000,
        'bid_type' => 'OPEN',
        'requires_deposit' => true,
        'start_time' => now()->subHours(6),
        'end_time' => now()->addDays(2),
        'status' => 'ONGOING',
        'location' => 'Jakarta'
    ],
    [
        'category_id' => $categories->where('name', 'Seni & Lukisan')->first()->id,
        'admin_id' => $admin->id,
        'title' => 'Lukisan Basuki Abdullah - Portrait',
        'description' => 'Original painting by Basuki Abdullah, oil on canvas, 60x80cm.',
        'starting_price' => 85000000,
        'bid_type' => 'CLOSED',
        'requires_deposit' => false,
        'start_time' => now()->subDays(1),
        'end_time' => now()->addHours(18),
        'status' => 'ONGOING',
        'location' => 'Bandung'
    ]
];

foreach ($ongoingLots as $lotData) {
    $lot = \App\Models\AuctionLot::create($lotData);
    createVirtualAccounts($lot, $banks);
    
    // Add some sample bids for ongoing auctions
    $bidCount = rand(3, 8);
    $currentPrice = $lot->starting_price;
    
    for ($i = 0; $i < $bidCount; $i++) {
        $bidder = $bidders->random();
        $increment = rand(1000000, 5000000); // 1-5 million increment
        $currentPrice += $increment;
        
        \App\Models\Bid::create([
            'lot_id' => $lot->id,
            'bidder_id' => $bidder->id,
            'amount' => $currentPrice,
            'created_at' => now()->subHours(rand(1, 24))
        ]);
    }
}

// 3. FINISHED AUCTIONS (Completed with winners)
$finishedLots = [
    [
        'category_id' => $categories->where('name', 'Elektronik')->first()->id,
        'admin_id' => $admin->id,
        'title' => 'MacBook Pro M2 Max 16" - Maxed Configuration',
        'description' => 'MacBook Pro 16" with M2 Max chip, 64GB RAM, 2TB SSD.',
        'starting_price' => 65000000,
        'bid_type' => 'OPEN',
        'requires_deposit' => true,
        'start_time' => now()->subDays(10),
        'end_time' => now()->subDays(3),
        'status' => 'FINISHED',
        'location' => 'Jakarta'
    ],
    [
        'category_id' => $categories->where('name', 'Barang Koleksi')->first()->id,
        'admin_id' => $admin->id,
        'title' => 'Hermès Kelly 32 Epsom Leather',
        'description' => 'Hermès Kelly 32 in Epsom leather, excellent condition with box and dustbag.',
        'starting_price' => 350000000,
        'bid_type' => 'CLOSED',
        'requires_deposit' => true,
        'start_time' => now()->subDays(14),
        'end_time' => now()->subDays(7),
        'status' => 'FINISHED',
        'location' => 'Surabaya'
    ]
];

foreach ($finishedLots as $lotData) {
    $lot = \App\Models\AuctionLot::create($lotData);
    createVirtualAccounts($lot, $banks);
    
    // Add bids and determine winner
    $bidCount = rand(5, 12);
    $currentPrice = $lot->starting_price;
    $lastBidder = null;
    
    for ($i = 0; $i < $bidCount; $i++) {
        $bidder = $bidders->random();
        $increment = rand(2000000, 8000000); // 2-8 million increment
        $currentPrice += $increment;
        
        $bid = \App\Models\Bid::create([
            'lot_id' => $lot->id,
            'bidder_id' => $bidder->id,
            'amount' => $currentPrice,
            'created_at' => now()->subDays(rand(7, 14))
        ]);
        
        $lastBidder = $bidder;
    }
    
    // Create auction winner
    if ($lastBidder) {
        \App\Models\AuctionWinner::create([
            'lot_id' => $lot->id,
            'user_id' => $lastBidder->id,
            'final_price' => $currentPrice,
            'deposit_amount' => $lot->requires_deposit ? ($lot->starting_price * 0.1) : 0,
            'settlement_due' => now()->addDays(5),
            'status' => 'PENDING_SETTLEMENT'
        ]);
    }
}
```

## Step 3: Verify Data Creation

```php
// Check created auction lots
echo "Total auction lots: " . \App\Models\AuctionLot::count() . "\n";
echo "Scheduled: " . \App\Models\AuctionLot::where('status', 'SCHEDULED')->count() . "\n";
echo "Ongoing: " . \App\Models\AuctionLot::where('status', 'ONGOING')->count() . "\n";
echo "Finished: " . \App\Models\AuctionLot::where('status', 'FINISHED')->count() . "\n";

// Check bids
echo "Total bids: " . \App\Models\Bid::count() . "\n";

// Check virtual accounts
echo "Total virtual accounts: " . \App\Models\VirtualAccount::count() . "\n";

// Check winners
echo "Total winners: " . \App\Models\AuctionWinner::count() . "\n";
```

## Usage Instructions

1. Open Laravel Tinker: `php artisan tinker`
2. Copy and paste the commands from Step 1 to create basic data
3. Copy and paste the commands from Step 2 to create auction lots
4. Run Step 3 to verify the data was created successfully
5. Exit Tinker: `exit`

## Notes

- Adjust the dates, prices, and descriptions as needed
- The commands create realistic test data with proper relationships
- Virtual accounts are automatically created for each auction lot
- Bids are added to ongoing and finished auctions for testing
- Winners are determined for finished auctions

This test data will allow comprehensive testing of the bidder dashboard functionality across different auction states.
