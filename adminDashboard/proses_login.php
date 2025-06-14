<?php
session_start();
$mysqli = new mysqli("localhost", "root", "12345678", "artikel");
if ($mysqli->connect_errno) {
    die("Koneksi gagal: " . $mysqli->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $mysqli->prepare("SELECT * FROM author WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        $_SESSION['author_id'] = $user['id'];
        $_SESSION['nickname'] = $user['nickname'];
        header("Location: artikel.php"); // atau halaman utama admin kamu
        exit;
    } else {
        echo "⚠️ Password salah.";
    }
} else {
    echo "⚠️ Email tidak ditemukan.";
}
?>
