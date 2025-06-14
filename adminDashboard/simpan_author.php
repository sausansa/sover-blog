<?php
$mysqli = new mysqli("localhost", "root", "12345678", "artikel");
if ($mysqli->connect_errno) {
    die("Gagal koneksi: " . $mysqli->connect_error);
}

// Ambil data POST
$nickname = $_POST['nickname'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Validasi sederhana
if (trim($nickname) === '' || trim($email) === '' || trim($password) === '') {
    die("Data tidak lengkap.");
}

// Upload gambar profile (jika ada)
$profile_picture = null;
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
    $allowed = ['image/jpeg', 'image/png'];
    $fileType = $_FILES['profile_picture']['type'];

    if (!in_array($fileType, $allowed)) {
        die("Format gambar harus JPG atau PNG.");
    }

    $ext = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . "." . $ext;
    $destination = "uploads/" . $filename;

    if (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $destination)) {
        die("Gagal upload gambar.");
    }

    $profile_picture = $destination; // Simpan path lengkap
}

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert ke DB
$stmt = $mysqli->prepare("INSERT INTO author (nickname, email, password, profile_picture) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    die("Prepare failed: " . $mysqli->error);
}
$stmt->bind_param("ssss", $nickname, $email, $hashed_password, $profile_picture);

if ($stmt->execute()) {
    header("Location: author.php");
    exit;
} else {
    echo "Gagal simpan author: " . $stmt->error;
}

$stmt->close();
$mysqli->close();
?>
