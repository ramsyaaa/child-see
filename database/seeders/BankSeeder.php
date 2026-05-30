<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            [
                'bank_name' => 'Bank Central Asia',
                'bank_code' => 'BCA',
                'is_active' => true
            ],
            [
                'bank_name' => 'Bank Mandiri',
                'bank_code' => 'MANDIRI',
                'is_active' => true
            ],
            [
                'bank_name' => 'Bank Rakyat Indonesia',
                'bank_code' => 'BRI',
                'is_active' => true
            ],
            [
                'bank_name' => 'Bank Negara Indonesia',
                'bank_code' => 'BNI',
                'is_active' => true
            ],
            [
                'bank_name' => 'Bank CIMB Niaga',
                'bank_code' => 'CIMB',
                'is_active' => true
            ]
        ];

        foreach ($banks as $bank) {
            \App\Models\Bank::create($bank);
        }
    }
}
