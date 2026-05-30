<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BankAccount;

class BankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bankAccounts = [
            [
                'bank_name' => 'Bank Central Asia (BCA)',
                'account_number' => '1234567890',
                'account_holder' => 'PT Ferensa Studio Indonesia',
                'branch' => 'KCP Jakarta Sudirman',
                'note' => 'Primary account for member payments. Please include your name and transaction number in the transfer notes.',
                'is_active' => true,
            ],
            [
                'bank_name' => 'Bank Mandiri',
                'account_number' => '1370012345678',
                'account_holder' => 'PT Ferensa Studio Indonesia',
                'branch' => 'Cabang Jakarta Thamrin',
                'note' => 'Alternative payment account. Available for all types of transactions.',
                'is_active' => true,
            ],
            [
                'bank_name' => 'Bank Negara Indonesia (BNI)',
                'account_number' => '0987654321',
                'account_holder' => 'PT Ferensa Studio Indonesia',
                'branch' => 'Kantor Cabang Jakarta Gatot Subroto',
                'note' => 'Secondary account for member convenience. Please upload payment proof after transfer.',
                'is_active' => true,
            ],
            [
                'bank_name' => 'Bank Rakyat Indonesia (BRI)',
                'account_number' => '0123456789012345',
                'account_holder' => 'PT Ferensa Studio Indonesia',
                'branch' => 'Unit Senayan',
                'note' => 'Available for subscription and drop-in payments.',
                'is_active' => true,
            ],
            [
                'bank_name' => 'CIMB Niaga',
                'account_number' => '800123456789',
                'account_holder' => 'PT Ferensa Studio Indonesia',
                'branch' => 'Cabang Pembantu Kuningan',
                'note' => 'Additional payment option. Transfers are usually processed within 1 business day.',
                'is_active' => false, // Inactive for testing purposes
            ],
        ];

        foreach ($bankAccounts as $account) {
            BankAccount::create($account);
        }
    }
}

