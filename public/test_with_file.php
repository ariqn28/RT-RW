<?php
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\UploadedFile;

$user = User::where('role', 'warga')->first();
if (!$user) {
    $user = User::create([
        'name' => 'Test Warga',
        'email' => 'testwarga' . time() . '@test.com',
        'password' => bcrypt('password123'),
        'role' => 'warga'
    ]);
}

Auth::login($user);

$request = new Illuminate\Http\Request();
$request->setMethod('POST');
$request->request->add([
    'jenis_surat' => 'Surat Pengantar',
    'nama' => 'Test Nama File',
    'nik' => '1234567890',
    'alamat' => 'Jl. Test No. 1',
    'alasan' => 'Ini adalah alasan pengajuan surat yang cukup panjang untuk memenuhi validasi minimal 10 karakter.',
]);

// Create a fake file
$fakeFile = UploadedFile::fake()->create('test.pdf', 100);
$request->files->set('file', $fakeFile);

$request->setUserResolver(function () use ($user) {
    return $user;
});

try {
    $controller = new App\Http\Controllers\PengajuanController();
    $response = $controller->store($request);
    echo "=== SUKSES DENGAN FILE ===\n";
    echo "Response type: " . get_class($response) . "\n";
    if (method_exists($response, 'getTargetUrl')) {
        echo "Redirect to: " . $response->getTargetUrl() . "\n";
    }
} catch (Exception $e) {
    echo "=== ERROR ===\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
