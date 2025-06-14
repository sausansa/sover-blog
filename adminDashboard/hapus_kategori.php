<?php
$mysqli = new mysqli("localhost", "root", "12345678", "artikel");
if ($mysqli->connect_errno) {
    die("Gagal koneksi: " . $mysqli->connect_error);
}

// Ambil id dari URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id > 0) {
    // Prepare statement hapus
    $stmt = $mysqli->prepare("DELETE FROM category WHERE id = ?");
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Jika berhasil hapus, redirect ke halaman kategori
        header("Location: kategori.php");
        exit;
    } else {
        echo "Gagal menghapus kategori: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "ID kategori tidak valid.";
}

$mysqli->close();
?>
