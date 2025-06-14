<?php
// Koneksi ke database
$mysqli = new mysqli("localhost", "root", "12345678", "artikel"); // pastikan nama database sesuai

if ($mysqli->connect_errno) {
    die("Gagal koneksi: " . $mysqli->connect_error);
}

// Ambil data dari POST
$name = $_POST['name'] ?? '';
$description = $_POST['description'] ?? '';

// Validasi sederhana
if (trim($name) === '') {
    die("Nama kategori tidak boleh kosong.");
}

// Query simpan
$query = "INSERT INTO category (name, description) VALUES (?, ?)";
$stmt = $mysqli->prepare($query);

if (!$stmt) {
    die("Prepare failed: " . $mysqli->error);
}

$stmt->bind_param("ss", $name, $description);

if ($stmt->execute()) {
    header("Location: kategori.php");
    exit;
} else {
    echo "Gagal menyimpan kategori: " . $stmt->error;
}

$stmt->close();
$mysqli->close();
?>
