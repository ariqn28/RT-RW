<?php
try {
    $response = file_get_contents("http://127.0.0.1:8000/ajukan");
    if ($response) {
        echo "OK - Halaman /ajukan bisa diakses\n";
        echo "Length: " . strlen($response) . " bytes\n";
    } else {
        echo "KOSONG - Response kosong\n";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
