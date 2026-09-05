<?php
/**
 * Test script: Full POST flow to /ajukan via curl
 * This simulates a browser: login -> get form -> submit form
 */

$baseUrl = 'http://127.0.0.1:8000';
$cookieFile = tempnam(sys_get_temp_dir(), 'curl_cookie_');

echo "=== Laravel Form POST Test ===\n";
echo "Cookie jar: $cookieFile\n\n";

function curlReq($url, $postData = null, $cookieFile) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    if ($postData !== null) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    }
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    return ['code' => $httpCode, 'body' => $response, 'error' => $error];
}

// 1. GET /login page to extract CSRF token
echo "[1] GET /login ...\n";
$r = curlReq("$baseUrl/login", null, $cookieFile);
echo "    HTTP Code: {$r['code']}\n";
if ($r['code'] != 200) {
    echo "    ERROR: Expected 200, got {$r['code']}\n";
    echo "    Response snippet: " . substr($r['body'], 0, 500) . "\n";
    exit(1);
}

// Extract CSRF token
preg_match('/name="_token" value="([^"]+)"/', $r['body'], $matches);
$csrfToken = $matches[1] ?? null;
echo "    CSRF Token: " . ($csrfToken ? substr($csrfToken, 0, 30) . "..." : "NOT FOUND") . "\n\n";

if (!$csrfToken) {
    echo "    ERROR: Could not extract CSRF token!\n";
    exit(1);
}

// 2. POST /login with credentials
echo "[2] POST /login (attempting test credentials)...\n";
// Try common test credentials - user should provide actual credentials
$loginData = [
    '_token' => $csrfToken,
    'email' => 'warga@test.com',
    'password' => 'password'
];
$r = curlReq("$baseUrl/login", $loginData, $cookieFile);
echo "    HTTP Code: {$r['code']}\n";
echo "    Redirect/Response: " . (strpos($r['body'], 'dashboard') !== false ? 'To dashboard' : 'Other') . "\n\n";

// 3. GET /ajukan to extract CSRF token for form
echo "[3] GET /ajukan ...\n";
$r = curlReq("$baseUrl/ajukan", null, $cookieFile);
echo "    HTTP Code: {$r['code']}\n";

if ($r['code'] == 302 || $r['code'] == 301) {
    echo "    REDIRECT detected! Location header present.\n";
    preg_match('/Location:\s*(.+?)\r?\n/', $r['body'], $loc);
    echo "    Redirect to: " . ($loc[1] ?? 'unknown') . "\n";
}

if ($r['code'] != 200) {
    echo "    ERROR: Cannot access /ajukan. Got HTTP {$r['code']}\n";
    echo "    Response snippet: " . substr($r['body'], 0, 800) . "\n";
    exit(1);
}

preg_match('/name="_token" value="([^"]+)"/', $r['body'], $matches);
$formToken = $matches[1] ?? null;
echo "    Form CSRF Token: " . ($formToken ? substr($formToken, 0, 30) . "..." : "NOT FOUND") . "\n\n";

// 4. POST /ajukan (the actual form submission)
echo "[4] POST /ajukan (form submission)...\n";
$formData = [
    '_token' => $formToken,
    'jenis_surat' => 'Surat Keterangan Domisili',
    'nama' => 'Test Warga',
    'nik' => '1234567890123456',
    'alamat' => 'Jl. Test No. 1',
    'alasan' => 'Ini adalah alasan pengajuan surat untuk testing. Minimal sepuluh karakter.',
];
$r = curlReq("$baseUrl/ajukan", $formData, $cookieFile);
echo "    HTTP Code: {$r['code']}\n";
echo "    cURL Error: " . ($r['error'] ?: 'None') . "\n";
echo "    Response snippet:\n";
echo "    " . str_replace("\n", "\n    ", substr($r['body'], 0, 1200)) . "\n";

echo "\n=== Test Complete ===\n";
unlink($cookieFile);

