<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\PengajuanController;
use App\Models\User;

$request = Request::create('/ajukan', 'POST', [
    'jenis_surat' => 'Surat Test',
    'nama' => 'Orang Dengan Nama Yang Sangat Panjang Sekali Melebihi Batas Empat Puluh Karakter',
    'nik' => '1234567890',
    'alamat' => 'Jl. Testing No. 1',
    'alasan' => 'Testing aplikasi RT/RW untuk memastikan form berfungsi.'
]);

try {
    $user = User::first();
    if (!$user) {
        throw new Exception("Tidak ada user di database. Jalankan 'php artisan db:seed' terlebih dahulu.");
    }

    auth()->login($user);
    echo "Logged in as: " . $user->email . "\n";

    $controller = new PengajuanController();
    $response = $controller->store($request);
    echo 'SUCCESS: ' . get_class($response) . "\n";
    if (method_exists($response, 'getTargetUrl')) {
        echo 'Redirect to: ' . $response->getTargetUrl() . "\n";
    }
} catch (Throwable $e) {
    echo 'ERROR: ' . get_class($e) . "\n";
    echo 'Message: ' . $e->getMessage() . "\n";
    echo 'File: ' . $e->getFile() . ':' . $e->getLine() . "\n";
}
