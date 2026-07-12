<?php
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\User;

// Cari user warga
$user = User::where('role', 'warga')->first();
if (!$user) {
    echo "Tidak ada user warga. Buat user test...\n";
    $user = User::create([
        'name' => 'Test Warga',
        'email' => 'testwarga' . time() . '@test.com',
        'password' => bcrypt('password123'),
        'role' => 'warga'
    ]);
}

// Login
Auth::login($user);
echo "Login sebagai: " . $user->name . " (role: " . $user->role . ")\n";

// Simulasi request POST ke /ajukan
$request = new Illuminate\Http\Request();
$request->setMethod('POST');
$request->request->add([
    'jenis_surat' => 'Surat Pengantar',
    'nama' => 'Test Nama',
    'nik' => '1234567890',
    'alamat' => 'Jl. Test No. 1',
    'alasan' => 'Ini adalah alasan pengajuan surat yang cukup panjang untuk memenuhi validasi minimal 10 karakter.',
]);

// Set user auth
$request->setUserResolver(function () use ($user) {
    return $user;
});

try {
    $controller = new App\Http\Controllers\PengajuanController();
    $response = $controller->store($request);
    echo "\n=== SUKSES ===\n";
    echo "Response type: " . get_class($response) . "\n";
    if (method_exists($response, 'getTargetUrl')) {
        echo "Redirect to: " . $response->getTargetUrl() . "\n";
    }
} catch (Exception $e) {
    echo "\n=== ERROR ===\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}
