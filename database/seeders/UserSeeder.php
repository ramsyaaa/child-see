<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Superadmin
        User::updateOrCreate(['username' => 'superadmin'], [
            'name'      => 'Super Admin',
            'full_name' => 'Super Admin',
            'email'     => 'superadmin@inklusyncid.id',
            'password'  => Hash::make('password'),
            'phone'     => '+62812345678901',
            'role'      => 'SUPERADMIN',
            'status'    => 'ACTIVE',
        ]);

        // Admins
        $admins = [
            ['name' => 'Admin InkluSync', 'full_name' => 'Admin InkluSync', 'username' => 'admin',      'email' => 'admin@inklusyncid.id',  'phone' => '+62812345678902'],
            ['name' => 'Siti Nurhaliza',  'full_name' => 'Siti Nurhaliza',  'username' => 'siti.admin', 'email' => 'siti@inklusyncid.id',   'phone' => '+62812345678903'],
        ];
        foreach ($admins as $a) {
            User::updateOrCreate(['username' => $a['username']], array_merge($a, [
                'password' => Hash::make('password'),
                'role'     => 'ADMIN',
                'status'   => 'ACTIVE',
            ]));
        }

        // Members
        $members = [
            ['name' => 'Andi Wijaya',    'username' => 'andi.wijaya',    'email' => 'andi.wijaya@gmail.com',    'phone' => '+62812345678904'],
            ['name' => 'Budi Santoso',   'username' => 'budi.santoso',   'email' => 'budi.santoso@gmail.com',   'phone' => '+62812345678905'],
            ['name' => 'Citra Dewi',     'username' => 'citra.dewi',     'email' => 'citra.dewi@gmail.com',     'phone' => '+62812345678906'],
            ['name' => 'Dian Pratama',   'username' => 'dian.pratama',   'email' => 'dian.pratama@gmail.com',   'phone' => '+62812345678907'],
            ['name' => 'Eka Putri',      'username' => 'eka.putri',      'email' => 'eka.putri@gmail.com',      'phone' => '+62812345678908'],
            ['name' => 'Fajar Rahman',   'username' => 'fajar.rahman',   'email' => 'fajar.rahman@gmail.com',   'phone' => '+62812345678909'],
            ['name' => 'Gita Sari',      'username' => 'gita.sari',      'email' => 'gita.sari@gmail.com',      'phone' => '+62812345678910'],
            ['name' => 'Hendra Kusuma',  'username' => 'hendra.kusuma',  'email' => 'hendra.kusuma@gmail.com',  'phone' => '+62812345678911'],
        ];
        foreach ($members as $m) {
            User::updateOrCreate(['username' => $m['username']], array_merge($m, [
                'full_name' => $m['name'],
                'password'  => Hash::make('password'),
                'role'      => 'MEMBER',
                'status'    => 'ACTIVE',
            ]));
        }

        echo "\n✅ Users seeded:\n";
        echo "   Superadmin — username: superadmin / email: superadmin@inklusyncid.id / password: password\n";
        echo "   Admin      — username: admin      / email: admin@inklusyncid.id\n";
        echo "   Admin      — username: siti.admin / email: siti@inklusyncid.id\n";
        echo "   8 Members  — e.g. username: andi.wijaya / email: andi.wijaya@gmail.com\n\n";
    }
}
