<?php
$host = "localhost";
$user = "root";
$password = "12345678";
$database = "artikel";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
