<?php
$path = __DIR__ . '/database/database.sqlite';
$pdo = new PDO('sqlite:' . $path);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name")->fetchAll(PDO::FETCH_COLUMN);
print_r($tables);
echo "\n--- users schema ---\n";
$cols = $pdo->query("PRAGMA table_info(users)")->fetchAll(PDO::FETCH_ASSOC);
print_r($cols);
echo "\n--- matching users ---\n";
$rows = $pdo->query("SELECT id, name, email, role, password FROM users WHERE email IN ('warga@gmail.com','rt@gmail.com','rw@gmail.com') ORDER BY email")->fetchAll(PDO::FETCH_ASSOC);
print_r($rows);
