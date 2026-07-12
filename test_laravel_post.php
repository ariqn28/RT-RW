<?php
// Test POST ke Laravel route /test-ajukan (tanpa auth)
$url = 'http://127.0.0.1:8000/test-ajukan';
$data = http_build_query([
    'jenis_surat' => 'Surat Test',
    'nama' => 'Test Nama',
    'nik' => '1234567890',
    'alamat' => 'Jl. Test',
    'alasan' => 'Alasan pengajuan surat untuk testing aplikasi RT/RW.',
    '_token' => 'dummy_token_for_test'
]);

$options = [
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/x-www-form-urlencoded\r\nX-Requested-With: XMLHttpRequest\r\n',
        'content' => $data,
    ],
];

$context = stream_context_create($options);
$response = @file_get_contents($url, false, $context);

if ($response === false) {
    file_put_contents('test_laravel_output.txt', "LARAVEL POST FAILED: " . (error_get_last()['message'] ?? 'unknown') . "\n");
} else {
    file_put_contents('test_laravel_output.txt', "LARAVEL POST SUCCESS!\nHTTP Code: " . ($http_response_header[0] ?? 'unknown') . "\nResponse:\n" . substr($response, 0, 1000) . "\n");
}
echo "Done. Check test_laravel_output.txt\n";

