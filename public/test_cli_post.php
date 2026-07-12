<?php
// Test POST dari command line
$url = 'http://127.0.0.1:8000/test_pure_post.php';
$data = http_build_query(['nama' => 'CLI Test', 'pesan' => 'Testing from CLI']);

$options = [
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/x-www-form-urlencoded',
        'content' => $data,
    ],
];

$context = stream_context_create($options);
$response = @file_get_contents($url, false, $context);

if ($response === false) {
    echo "POST FAILED: " . error_get_last()['message'] . "\n";
} else {
    echo "POST SUCCESS!\n";
    echo substr($response, 0, 200) . "\n";
}

