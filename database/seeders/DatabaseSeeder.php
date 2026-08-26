<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        User::unguard();

        $users = [
            [
                'name' => 'Warga User',
                'email' => 'warga@gmail.com',
                'role' => 'warga',
                'password' => Hash::make('12345678'),
                'nik' => '1234567890123456',
                'alamat' => 'Jl. Test No. 1',
            ],
            [
                'name' => 'Ketua RT',
                'email' => 'rt@gmail.com',
                'role' => 'rt',
                'password' => Hash::make('12345678'),
                'nik' => '0000000000000001',
                'alamat' => 'Kantor RT',
            ],
            [
                'name' => 'Ketua RW',
                'email' => 'rw@gmail.com',
                'role' => 'rw',
                'password' => Hash::make('12345678'),
                'nik' => '0000000000000002',
                'alamat' => 'Kantor RW',
            ],
            [
                'name' => 'Admin Utama',
                'email' => 'admin@gmail.com',
                'role' => 'admin',
                'password' => Hash::make('12345678'),
                'nik' => '9999999999999991',
                'alamat' => 'Sistem',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }

        User::reguard();
    }
}
