<?php
// Test form POST sederhana tanpa auth
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2>Berhasil! POST request diterima.</h2>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Test POST</title></head>
<body>
<h2>Test Form POST (Tanpa Auth)</h2>
<form method="POST" action="">
    <input type="text" name="test_field" value="test" />
    <button type="submit">Kirim POST</button>
</form>
</body>
</html>

