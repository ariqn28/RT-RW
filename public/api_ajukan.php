<?php
/**
 * Endpoint tunggal untuk debug form pengajuan.
 * Tidak melalui Laravel sama sekali - pure PHP manual.
 * Tujuannya: isolasi apakah crash terjadi di PHP server atau di Laravel middleware.
 */

require __DIR__ . '/../vendor/autoload.php';

// Konfigurasi minimal Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Ambil kernel dan run sederhana TANPA middleware
// Kita akan langsung akses database

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

try {
    // Validasi minimal
    $required = ['jenis_surat', 'nama', 'nik', 'alamat', 'alasan'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            http_response_code(422);
            echo json_encode(['error' => "Field $field wajib diisi"]);
            exit;
        }
    }

    // Insert ke database
    $now = date('Y-m-d H:i:s');
    DB::insert(
        'INSERT INTO pengajuans (jenis_surat, nama, nik, alamat, alasan, status, user_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
        [
            $_POST['jenis_surat'],
            $_POST['nama'],
            $_POST['nik'],
            $_POST['alamat'],
            $_POST['alasan'],
            'baru',
            $_POST['user_id'] ?? null,
            $now,
            $now
        ]
    );

    $id = DB::getPdo()->lastInsertId();

    echo json_encode([
        'success' => true,
        'message' => 'Pengajuan berhasil (via bypass)!',
        'id' => $id,
        'url' => '/status/' . $id
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
}
