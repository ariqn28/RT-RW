<?php
/**
 * Run: php create_test_users.php
 * Creates test accounts for RT/RW dashboard
 */

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Bersihkan cache koneksi agar membaca kondisi database terbaru
Illuminate\Support\Facades\DB::purge();

$connection = Illuminate\Support\Facades\DB::connection();
$dbPath = $connection->getDatabaseName();
echo "--------------------------------------------------\n";
echo "DATABASE AKTIF: " . $dbPath . "\n";
echo "--------------------------------------------------\n";

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

// --- BAGIAN PEMULIHAN STRUKTUR DATABASE ---
echo "Memeriksa integritas role 'admin'...\n";
try {
    // Mencoba memasukkan dummy admin untuk tes constraint
    User::unguard();
    User::updateOrCreate(['email' => 'check@test.com'], ['role' => 'admin', 'password' => '123', 'name' => 'Check', 'nik' => '0', 'alamat' => '-']);
    User::where('email', 'check@test.com')->delete();
    echo "✅ Database sudah mendukung role admin.\n";
} catch (\Exception $e) {
    echo "⚠️ Database membatasi role admin. Melakukan perbaikan struktur...\n";
    // Fix: Mengubah kolom role menjadi string biasa tanpa constraint enum di SQLite
    Schema::table('users', function (Blueprint $table) {
        $table->string('role')->change();
    });
    echo "✅ Struktur database berhasil diperbarui.\n";
}
echo "--------------------------------------------------\n";
// ------------------------------------------

$users = [
    [
        'name' => 'Warga User',
        'email' => 'warga@gmail.com',
        'role' => 'warga',
        'password' => Hash::make('12345678'),
        'nik' => '1234567890123456',
        'alamat' => 'Jl Test No 1'
    ],
    [
        'name' => 'Ketua RT',
        'email' => 'rt@gmail.com',
        'role' => 'rt',
        'password' => Hash::make('12345678'),
        'nik' => '0000000000000001',
        'alamat' => 'Kantor RT'
    ],
    [
        'name' => 'Ketua RW',
        'email' => 'rw@gmail.com', 
        'role' => 'rw',
        'password' => Hash::make('12345678'),
        'nik' => '0000000000000002',
        'alamat' => 'Kantor RW'
    ],
    [
        'name' => 'Admin Utama',
        'email' => 'admin@gmail.com',
        'role' => 'admin',
        'password' => Hash::make('12345678'),
        'nik' => '9999999999999991',
        'alamat' => 'Sistem'
    ],
    [
        'name' => 'Admin Cadangan',
        'email' => 'admin1@gmail.com',
        'role' => 'admin',
        'password' => Hash::make('12345678'),
        'nik' => '9999999999999992',
        'alamat' => 'Sistem'
    ]
];

User::unguard();

foreach ($users as $data) {
    try {
        $user = User::updateOrCreate(
            ['email' => $data['email']],
            [
                'name' => $data['name'],
                'role' => $data['role'],
                'password' => $data['password'],
                'nik' => $data['nik'],
                'alamat' => $data['alamat'],
            ]
        );

        
        echo "✅ Tersinkron: {$data['email']} ({$data['role']})\n";
    } catch (\Exception $e) {
        echo "❌ Gagal sinkron {$data['email']}: " . $e->getMessage() . "\n";
    }
}

User::reguard();

echo "--------------------------------------------------\n";
echo "TOTAL USER DI DATABASE: " . User::count() . "\n";
echo "--------------------------------------------------\n";

echo "Daftar Email yang Benar-benar Ada:\n";
print_r(User::pluck('email')->toArray());

echo "\n=== LOGIN CREDENTIALS ===";
echo "\nWarga: warga@gmail.com / 12345678";
echo "\nRT:    rt@gmail.com / 12345678"; 
echo "\nRW:    rw@gmail.com / 12345678";
echo "\nAdmin 1: admin@gmail.com / 12345678";
echo "\nAdmin 2: admin1@gmail.com / 12345678";
echo "\n\nTest: php artisan serve";
?>
