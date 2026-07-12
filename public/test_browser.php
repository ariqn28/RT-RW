<?php
// Test like a real browser: login, get CSRF, submit form
$base = 'http://127.0.0.1:8000';
$cookieFile = tempnam(sys_get_temp_dir(), 'cookie');

// 1. Get login page to extract CSRF
$ch = curl_init("$base/login");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
$html = curl_exec($ch);
curl_close($ch);

// Extract CSRF token
preg_match('/name="_token" value="([^"]+)"/', $html, $matches);
$token = $matches[1] ?? '';
echo "CSRF Token: $token\n";

// 2. Login
$ch = curl_init("$base/login");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    '_token' => $token,
    'email' => 'username@gmail.com',
    'password' => 'password123'
]));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Login HTTP Code: $httpCode\n";

// 3. Get form page to extract new CSRF
$ch = curl_init("$base/ajukan");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
$html = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Form Page HTTP Code: $httpCode\n";

preg_match('/name="_token" value="([^"]+)"/', $html, $matches);
$token = $matches[1] ?? '';
echo "New CSRF Token: $token\n";

// 4. Submit form
$ch = curl_init("$base/ajukan");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    '_token' => $token,
    'jenis_surat' => 'Surat Pengantar',
    'nama' => 'Test Browser',
    'nik' => '1234567890',
    'alamat' => 'Jl. Browser No. 1',
    'alasan' => 'Ini adalah alasan pengajuan surat yang cukup panjang untuk memenuhi validasi.',
]));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HEADER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$err = curl_error($ch);
curl_close($ch);

echo "\n=== SUBMIT RESULT ===\n";
echo "HTTP Code: $httpCode\n";
echo "CURL Error: $err\n";
echo "Response (first 500 chars):\n" . substr($response, 0, 500) . "\n";

// Check if server still alive
sleep(1);
$ch = curl_init("$base/login");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$test = curl_exec($ch);
$alive = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "\nServer still alive? HTTP: $alive\n";

unlink($cookieFile);
