<?php
$dbPath = __DIR__ . '/database/database.sqlite';

echo "=== CEK INTEGRITAS DATABASE ===\n";
echo "Database: $dbPath\n";

if (!file_exists($dbPath)) {
    echo "ERROR: Database tidak ditemukan!\n";
    exit(1);
}

try {
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Cek integrity
    echo "\n1. PRAGMA integrity_check:\n";
    $stmt = $pdo->query("PRAGMA integrity_check;");
    $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
    foreach ($result as $line) {
        echo "   $line\n";
    }
    
    // Cek foreign keys
    echo "\n2. PRAGMA foreign_key_check:\n";
    $stmt = $pdo->query("PRAGMA foreign_key_check;");
    $fkErrors = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($fkErrors)) {
        echo "   OK - Tidak ada pelanggaran foreign key\n";
    } else {
        print_r($fkErrors);
    }
    
    // List tabel
    echo "\n3. Daftar Tabel:\n";
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name;");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    foreach ($tables as $table) {
        $count = $pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
        echo "   - $table ($count rows)\n";
    }
    
    // Detail pengajuans
    echo "\n4. Data Pengajuan:\n";
    $stmt = $pdo->query("SELECT id, nama, jenis_surat, status, user_id, created_at FROM pengajuans ORDER BY id;");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row) {
        echo "   #{$row['id']} | {$row['nama']} | {$row['jenis_surat']} | {$row['status']} | user_id={$row['user_id']} | {$row['created_at']}\n";
    }
    
    // Detail users
    echo "\n5. Data Users:\n";
    $stmt = $pdo->query("SELECT id, name, email, role FROM users ORDER BY id;");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row) {
        echo "   #{$row['id']} | {$row['name']} | {$row['email']} | {$row['role']}\n";
    }
    
    echo "\n=== SELESAI - Database OK ===\n";
    
} catch (Exception $e) {
    echo "\nERROR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}
