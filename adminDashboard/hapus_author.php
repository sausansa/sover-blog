<?php
$mysqli = new mysqli("localhost", "root", "12345678", "artikel");
if ($mysqli->connect_errno) {
    die("Gagal koneksi: " . $mysqli->connect_error);
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id > 0) {
    // Cari file gambar dulu
    $result = $mysqli->query("SELECT profile_picture FROM author WHERE id = $id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $profile_picture = $row['profile_picture'];

        if ($profile_picture && file_exists("uploads/$profile_picture")) {
            unlink("uploads/$profile_picture");
        }
    }

    // Hapus data author
    $stmt = $mysqli->prepare("DELETE FROM author WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: author.php");
        exit;
    } else {
        echo "Gagal hapus author: " . $stmt->error;
    }
} else {
    echo "ID tidak valid.";
}

$mysqli->close();
?>
