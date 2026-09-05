<?php
// Test langsung POST ke database tanpa melalui Laravel routing
// Untuk cek apakah SQLite dan file upload bekerja

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $count = DB::table('pengajuans')->count();
        echo "<h1>Test POST Berhasil!</h1>";
        echo "<p>Jumlah data pengajuan saat ini: <b>$count</b></p>";
        echo "<p>Data POST: <pre>" . print_r($_POST, true) . "</pre></p>";
        if (!empty($_FILES['file']['name'])) {
            echo "<p>File diterima: " . $_FILES['file']['name'] . " (" . $_FILES['file']['size'] . " bytes)</p>";
        }
        echo "<p><a href='test_submit.php'>Kembali ke form test</a></p>";
    } catch (Exception $e) {
        echo "<h1 style='color:red'>Error: " . $e->getMessage() . "</h1>";
    }
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Test Submit Langsung</title></head>
<body>
<h2>Test Form POST Langsung (bypass CSRF)</h2>
<p>Form ini untuk test apakah SQLite dan file upload bekerja tanpa error.</p>
<form action="test_submit.php" method="POST" enctype="multipart/form-data">
    <p>Jenis: <input type="text" name="jenis_surat" value="Surat Test" required></p>
    <p>Nama: <input type="text" name="nama" value="Test" required></p>
    <p>NIK: <input type="text" name="nik" value="123" required></p>
    <p>Alamat: <input type="text" name="alamat" value="Jl Test" required></p>
    <p>Alasan: <textarea name="alasan" required>Test</textarea></p>
    <p>File (max 500KB): <input type="file" name="file"></p>
    <button type="submit">Kirim Test</button>
</form>
<p><b>Catatan:</b> Jika klik Kirim dan muncul "Test POST Berhasil!", berarti server OK.
Jika "can't reach page", berarti server crash saat POST.</p>
</body>
</html>

