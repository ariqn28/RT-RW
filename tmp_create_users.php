<?php
$path = __DIR__ . '/database/database.sqlite';
$pdo = new PDO('sqlite:' . $path);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$users = [
    [
        'name' => 'warga',
        'email' => 'warga@gmail.com',
        'role' => 'warga',
        'password' => password_hash('12345678', PASSWORD_BCRYPT),
        'nik' => '1234567890123456',
        'alamat' => 'Jl Test No 1',
    ],
    [
        'name' => 'admin rt',
        'email' => 'rt@gmail.com',
        'role' => 'rt',
        'password' => password_hash('12345678', PASSWORD_BCRYPT),
        'nik' => '0000000000000001',
        'alamat' => 'Kantor RT',
    ],
    [
        'name' => 'rw',
        'email' => 'rw@gmail.com',
        'role' => 'rw',
        'password' => password_hash('12345678', PASSWORD_BCRYPT),
        'nik' => '0000000000000002',
        'alamat' => 'Kantor RW',
    ],
];

$insertSql = "INSERT INTO users (name, email, password, role, nik, alamat, created_at, updated_at) VALUES (:name, :email, :password, :role, :nik, :alamat, datetime('now'), datetime('now'))";
$stmt = $pdo->prepare($insertSql);
foreach ($users as $user) {
    $stmt->execute([
        ':name' => $user['name'],
        ':email' => $user['email'],
        ':password' => $user['password'],
        ':role' => $user['role'],
        ':nik' => $user['nik'],
        ':alamat' => $user['alamat'],
    ]);
    echo "Inserted {$user['email']}\n";
}

$rows = $pdo->query("SELECT id, name, email, role FROM users WHERE email IN ('warga@gmail.com','rt@gmail.com','rw@gmail.com') ORDER BY email")->fetchAll(PDO::FETCH_ASSOC);
print_r($rows);
