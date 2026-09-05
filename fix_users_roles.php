<?php
/**
 * Script untuk fix user roles yang tidak konsisten
 * Jalankan: php fix_users_roles.php
 */

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== FIX USER ROLES ===\n\n";

// Data user yang benar dan konsisten
$users_data = [
    [
        'name' => 'Warga Demo',
        'email' => 'ariqn@gmail.com',
        'role' => 'warga',
        'password' => '12345678',
        'nik' => '1234567890123456',
        'alamat' => 'Jl. Warga No. 1',
    ],
    [
        'name' => 'Ketua RT',
        'email' => 'ariqns@gmail.com',
        'role' => 'rt',
        'password' => '12345678',
        'nik' => '0000000000000001',
        'alamat' => 'Kantor RT',
    ],
    [
        'name' => 'Ariq Naufal',
        'email' => 'ariqns280702@gmail.com',
        'role' => 'rw',
        'password' => '12345678',
        'nik' => '0000000000000002',
        'alamat' => 'Kantor RW',
    ],
    [
        'name' => 'Admin',
        'email' => 'admin@gmail.com',
        'role' => 'admin',
        'password' => '12345678',
        'nik' => '9999999999999999',
        'alamat' => 'Admin',
    ],
];

foreach ($users_data as $user_data) {
    $existing = User::where('email', $user_data['email'])->first();
    
    if ($existing) {
        // Update role dan data user
        $existing->update([
            'name' => $user_data['name'],
            'role' => $user_data['role'],
            'password' => Hash::make($user_data['password']),
            'nik' => $user_data['nik'],
            'alamat' => $user_data['alamat'],
        ]);
        echo "✅ UPDATE: {$user_data['email']} → Role: {$user_data['role']}\n";
    } else {
        // Create new user
        User::create([
            'name' => $user_data['name'],
            'email' => $user_data['email'],
            'role' => $user_data['role'],
            'password' => Hash::make($user_data['password']),
            'nik' => $user_data['nik'],
            'alamat' => $user_data['alamat'],
        ]);
        echo "✨ CREATE: {$user_data['email']} → Role: {$user_data['role']}\n";
    }
}

echo "\n=== USER SETUP FINAL ===\n";
$all_users = User::select('id', 'email', 'name', 'role')->get();
foreach ($all_users as $u) {
    echo "#{$u->id} | {$u->email} | {$u->name} | [{$u->role}]\n";
}

echo "\n✅ Fix users roles selesai!\n";
echo "Sekarang login dengan email + password + role yang benar:\n";
echo "1. ariqn@gmail.com (warga)\n";
echo "2. ariqns@gmail.com (rt)\n";
echo "3. ariqns280702@gmail.com (rw)\n";
echo "4. admin@gmail.com (admin)\n";
echo "Password semua: 12345678\n";
