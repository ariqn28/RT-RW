<?php
/**
 * Test script untuk diagnosis POST /ajukan
 * Jalankan: php public/test_ajukan_post.php
 */

$baseUrl = 'http://127.0.0.1:8000';

echo "=== TEST PENGAJUAN FORM ===\n\n";

// Test 1: Cek apakah server running
echo "1. Cek server status... ";
$ch = curl_init($baseUrl . '/ajukan');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    echo "OK (HTTP 200)\n";
} elseif ($httpCode === 302 || $httpCode === 301) {
    echo "Redirect (HTTP $httpCode) - mungkin perlu login\n";
} else {
    echo "GAGAL (HTTP $httpCode)\n";
    echo "   Response: " . substr($response, 0, 200) . "\n";
}

// Test 2: Simulasi POST dengan multipart form (tanpa file)
echo "\n2. Simulasi POST form (tanpa file)... ";
$boundary = uniqid();
$body = "--$boundary\r\n";
$body .= "Content-Disposition: form-data; name=\"_token\"\r\n\r\ntest-token\r\n";
$body .= "--$boundary\r\n";
$body .= "Content-Disposition: form-data; name=\"jenis_surat\"\r\n\r\nSurat Test\r\n";
$body .= "--$boundary\r\n";
$body .= "Content-Disposition: form-data; name=\"nama\"\r\n\r\nTest Nama\r\n";
$body .= "--$boundary\r\n";
$body .= "Content-Disposition: form-data; name=\"nik\"\r\n\r\n1234567890\r\n";
$body .= "--$boundary\r\n";
$body .= "Content-Disposition: form-data; name=\"alamat\"\r\n\r\nJl. Test\r\n";
$body .= "--$boundary\r\n";
$body .= "Content-Disposition: form-data; name=\"alasan\"\r\n\r\nAlasan pengajuan surat untuk testing aplikasi RT/RW.\r\n";
$body .= "--$boundary--\r\n";

$ch = curl_init($baseUrl . '/ajukan');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: multipart/form-data; boundary=$boundary",
    'Accept: text/html'
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo "CURL ERROR: $error\n";
} else {
    echo "HTTP $httpCode\n";
    if ($httpCode >= 400 || strpos($response, 'Error') !== false || strpos($response, 'Exception') !== false) {
        echo "   Response snippet:\n" . substr($response, 0, 500) . "\n";
    }
}

echo "\n3. Simulasi POST form (dengan file)... ";
// Buat file test sementara
$tmpFile = sys_get_temp_dir() . '/test_upload.txt';
file_put_contents($tmpFile, 'Ini adalah file test untuk upload.');

$boundary = uniqid();
$body = "--$boundary\r\n";
$body .= "Content-Disposition: form-data; name=\"_token\"\r\n\r\ntest-token\r\n";
$body .= "--$boundary\r\n";
$body .= "Content-Disposition: form-data; name=\"jenis_surat\"\r\n\r\nSurat Test\r\n";
$body .= "--$boundary\r\n";
$body .= "Content-Disposition: form-data; name=\"nama\"\r\n\r\nTest Nama\r\n";
$body .= "--$boundary\r\n";
$body .= "Content-Disposition: form-data; name=\"nik\"\r\n\r\n1234567890\r\n";
$body .= "--$boundary\r\n";
$body .= "Content-Disposition: form-data; name=\"alamat\"\r\n\r\nJl. Test\r\n";
$body .= "--$boundary\r\n";
$body .= "Content-Disposition: form-data; name=\"alasan\"\r\n\r\nAlasan pengajuan surat untuk testing aplikasi RT/RW dengan file upload.\r\n";
$body .= "--$boundary\r\n";
$body .= "Content-Disposition: form-data; name=\"file\"; filename=\"test.txt\"\r\n";
$body .= "Content-Type: text/plain\r\n\r\n";
$body .= file_get_contents($tmpFile) . "\r\n";
$body .= "--$boundary--\r\n";

$ch = curl_init($baseUrl . '/ajukan');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: multipart/form-data; boundary=$boundary",
    'Accept: text/html'
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

unlink($tmpFile);

if ($error) {
    echo "CURL ERROR: $error\n";
} else {
    echo "HTTP $httpCode\n";
    if ($httpCode >= 400 || strpos($response, 'Error') !== false || strpos($response, 'Exception') !== false) {
        echo "   Response snippet:\n" . substr($response, 0, 500) . "\n";
    }
}

echo "\n=== SELESAI ===\n";
echo "Jika test 2 atau 3 menunjukkan error, periksa log Laravel:\n";
echo "  Get-Content storage\\logs\\laravel.log | Select-Object -Last 50\n";
