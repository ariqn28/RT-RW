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
    public function run()
    {
        $password = Hash::make('12345678');

        $accounts = [
            ['name' => 'Warga 01', 'email' => 'warga01@example.com', 'role' => 'warga', 'nik' => '3273010101000001', 'alamat' => 'Wilayah RW 02'],
            ['name' => 'Warga 02', 'email' => 'warga02@example.com', 'role' => 'warga', 'nik' => '3273010101000002', 'alamat' => 'Wilayah RW 02'],
            ['name' => 'Warga 03', 'email' => 'warga03@example.com', 'role' => 'warga', 'nik' => '3273010101000003', 'alamat' => 'Wilayah RW 03'],
            ['name' => 'Warga 04', 'email' => 'warga04@example.com', 'role' => 'warga', 'nik' => '3273010101000004', 'alamat' => 'Wilayah RW 04'],
            ['name' => 'Warga 05', 'email' => 'warga05@example.com', 'role' => 'warga', 'nik' => '3273010101000005', 'alamat' => 'Wilayah RW 05'],
            ['name' => 'Ketua RT 01', 'email' => 'rt01@example.com', 'role' => 'rt', 'nik' => '3273010101000101', 'alamat' => 'Sekretariat RT 01'],
            ['name' => 'Ketua RW 02', 'email' => 'rw02@example.com', 'role' => 'rw', 'nik' => '3273010202000202', 'alamat' => 'Sekretariat RW 02'],
            ['name' => 'Ketua RW 03', 'email' => 'rw03@example.com', 'role' => 'rw', 'nik' => '3273010303000303', 'alamat' => 'Sekretariat RW 03'],
            ['name' => 'Ketua RW 04', 'email' => 'rw04@example.com', 'role' => 'rw', 'nik' => '3273010404000404', 'alamat' => 'Sekretariat RW 04'],
            ['name' => 'Ketua RW 05', 'email' => 'rw05@example.com', 'role' => 'rw', 'nik' => '3273010505000505', 'alamat' => 'Sekretariat RW 05'],
            ['name' => 'Administrator', 'email' => 'admin@example.com', 'role' => 'admin', 'nik' => '9999999999999999', 'alamat' => 'Kantor Pengelola'],
        ];

        foreach ($accounts as $account) {
            User::updateOrCreate(
                ['email' => $account['email']],
                array_merge($account, ['password' => $password])
            );
        }
    }
}
