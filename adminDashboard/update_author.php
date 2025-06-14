<?php
$mysqli = new mysqli("localhost", "root", "12345678", "artikel");
if ($mysqli->connect_errno) {
    die("Gagal koneksi: " . $mysqli->connect_error);
}

$id = $_POST['id'] ?? 0;
$nickname = $_POST['nickname'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$profile_picture = null;

if ($id == 0 || trim($nickname) === '' || trim($email) === '') {
    die("Data tidak valid.");
}

// Ambil data author lama untuk foto lama (jika perlu hapus)
$result = $mysqli->query("SELECT profile_picture FROM author WHERE id = $id");
if ($result->num_rows == 0) {
    die("Author tidak ditemukan.");
}
$row = $result->fetch_assoc();
$old_picture = $row['profile_picture'];

// Upload gambar baru jika ada
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
    $profile_picture = $filename;

    // Hapus gambar lama jika ada
    if ($old_picture && file_exists("uploads/$old_picture")) {
        unlink("uploads/$old_picture");
    }
} else {
    // Kalau tidak upload baru, tetap pakai gambar lama
    $profile_picture = $old_picture;
}

// Jika password kosong, jangan update password
if (trim($password) === '') {
    $stmt = $mysqli->prepare("UPDATE author SET nickname = ?, email = ?, profile_picture = ? WHERE id = ?");
    $stmt->bind_param("sssi", $nickname, $email, $profile_picture, $id);
} else {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $mysqli->prepare("UPDATE author SET nickname = ?, email = ?, password = ?, profile_picture = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $nickname, $email, $hashed_password, $profile_picture, $id);
}

if ($stmt->execute()) {
    header("Location: author.php");
    exit;
} else {
    echo "Gagal update author: " . $stmt->error;
}

$stmt->close();
$mysqli->close();
?>
