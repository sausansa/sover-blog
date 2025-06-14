<?php
$host = "localhost";
$user = "root";
$pass = "12345678"; // Kosongkan jika memang tidak ada password
$db = "artikel";

$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

?>
