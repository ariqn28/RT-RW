<?php
$url = 'http://127.0.0.1:8000/ajukan';
$data = http_build_query([
    '_token' => 'test',
    'jenis_surat' => 'Surat Pengantar',
    'nama' => 'Test',
    'nik' => '1234567890123456',
    'alamat' => 'Alamat Testing Lengkap',
    'alasan' => 'Alasan pengajuan yang cukup panjang untuk validasi'
]);
$opts = [
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/x-www-form-urlencoded',
        'content' => $data,
        'ignore_errors' => true
    ]
];
$context = stream_context_create($opts);
$result = @file_get_contents($url, false, $context);
echo $result ? substr($result, 0, 500) : 'NO RESPONSE / ERROR';
