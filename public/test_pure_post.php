<?php
// Pure PHP - tidak melalui Laravel sama sekali
echo "<!DOCTYPE html><html><head><title>Hasil POST</title></head><body>";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h1 style='color:green'>POST BERHASIL!</h1>";
    echo "<p>Nama: " . htmlspecialchars($_POST['nama'] ?? '') . "</p>";
    echo "<p>Pesan: " . htmlspecialchars($_POST['pesan'] ?? '') . "</p>";
    echo "<p>Server berjalan normal. Masalahnya ada di Laravel, bukan di PHP server.</p>";
} else {
    echo "<h1>Method tidak didukung</h1>";
}
echo "<p><a href='test_pure_post.html'>Kembali</a></p>";
echo "</body></html>";

