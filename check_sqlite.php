<?php
try {
    $pdo = new PDO('sqlite:database/database.sqlite');
    $count = $pdo->query('SELECT COUNT(*) FROM pengajuans')->fetchColumn();
    echo "DB OK: $count rows in pengajuans\n";
    $users = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    echo "DB OK: $users rows in users\n";
} catch (Exception $e) {
    echo "DB ERROR: " . $e->getMessage() . "\n";
}

