<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Bank;
use App\Models\UserBankAccount;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserBankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users and banks
        $users = User::take(5)->get();
        $banks = Bank::all();

        if ($users->isEmpty() || $banks->isEmpty()) {
            $this->command->info('No users or banks found. Skipping UserBankAccount seeding.');
            return;
        }

        // Sample account data
        $sampleAccounts = [
            [
                'account_number' => '1234567890',
                'account_holder_name' => 'John Doe',
                'is_primary' => true,
                'is_active' => true
            ],
            [
                'account_number' => '0987654321',
                'account_holder_name' => 'Jane Smith',
                'is_primary' => false,
                'is_active' => true
            ],
            [
                'account_number' => '5555666677',
                'account_holder_name' => 'Bob Johnson',
                'is_primary' => true,
                'is_active' => true
            ],
            [
                'account_number' => '1122334455',
                'account_holder_name' => 'Alice Wilson',
                'is_primary' => false,
                'is_active' => true
            ],
            [
                'account_number' => '6677889900',
                'account_holder_name' => 'Charlie Brown',
                'is_primary' => true,
                'is_active' => false
            ]
        ];

        $created = 0;

        foreach ($users as $index => $user) {
            // Create 1-3 accounts per user
            $numAccounts = rand(1, 3);

            for ($i = 0; $i < $numAccounts; $i++) {
                $bank = $banks->random();
                $accountData = $sampleAccounts[($index + $i) % count($sampleAccounts)];

                // Check if this combination already exists
                $exists = UserBankAccount::where('user_id', $user->id)
                    ->where('bank_id', $bank->id)
                    ->where('account_number', $accountData['account_number'])
                    ->exists();

                if (!$exists) {
                    // Check if user already has a primary account
                    $hasPrimary = $user->bankAccounts()->where('is_primary', true)->exists();

                    // If this is the first account for the user or they don't have a primary, make it primary
                    if ($user->bankAccounts()->count() == 0 || !$hasPrimary) {
                        $accountData['is_primary'] = true;
                    } else {
                        $accountData['is_primary'] = false;
                    }

                    UserBankAccount::create([
                        'user_id' => $user->id,
                        'bank_id' => $bank->id,
                        'account_number' => $accountData['account_number'],
                        'account_holder_name' => $user->full_name, // Use actual user name
                        'is_primary' => $accountData['is_primary'],
                        'is_active' => $accountData['is_active'],
                    ]);

                    $created++;
                }
            }
        }

        $this->command->info("Created {$created} user bank accounts.");
    }
}