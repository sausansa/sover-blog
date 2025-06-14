<?php
$mysqli = new mysqli("localhost", "root", "12345678", "artikel");
if ($mysqli->connect_errno) {
    die("Gagal koneksi: " . $mysqli->connect_error);
}

// Ambil data POST
$id = $_POST['id'] ?? 0;
$name = $_POST['name'] ?? '';
$description = $_POST['description'] ?? '';

// Validasi sederhana
if ($id == 0 || trim($name) === '') {
    die("Data tidak valid.");
}

// Prepare query update
$stmt = $mysqli->prepare("UPDATE category SET name = ?, description = ? WHERE id = ?");
if (!$stmt) {
    die("Prepare failed: " . $mysqli->error);
}

$stmt->bind_param("ssi", $name, $description, $id);

if ($stmt->execute()) {
    header("Location: kategori.php");
    exit;
} else {
    echo "Gagal update kategori: " . $stmt->error;
}

$stmt->close();
$mysqli->close();
?>
