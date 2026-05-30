<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    /**
     * Seed production config data only.
     * Safe to re-run — all seeders use updateOrCreate.
     * Excludes: Products/bundles, BatchClasses, test Members.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            MasterClassSeeder::class,
            InstructorSeeder::class,
            RoomSeeder::class,
            BankAccountSeeder::class,
            CmsSeeder::class,
        ]);
    }
}
