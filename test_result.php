<?php
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
    file_put_contents('test_output.txt', "POST FAILED: " . (error_get_last()['message'] ?? 'unknown') . "\n");
} else {
    file_put_contents('test_output.txt', "POST SUCCESS!\n" . substr($response, 0, 500) . "\n");
}
echo "Done. Check test_output.txt\n";

