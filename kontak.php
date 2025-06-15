<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kontak Kami</title>
    <link rel="stylesheet" href="tentang.css">
</head>
<body>

<!-- Navbar -->
<div class="navbar">
  <div class="logo-container">
    <img src="images/logo.png" class="logo" alt="Logo">
  </div>
  <div class="nav-links">
    <a href="artikel.php">Home</a>
    <a href="tentang.php">Tentang</a>
    <a href="kontak.php">Kontak</a>
    <a href="adminDashboard/login.php">Blog</a>
  </div>
</div>

<!-- Konten -->
<div class="konten-utama" style="max-width: 800px; margin-top: 30px; padding: 20px;">
    <h1>Hubungi Kami</h1>
    <form method="POST" action="#">
        <label for="nama">Nama:</label>
        <input type="text" id="nama" name="nama" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="pesan">Pesan:</label>
        <textarea id="pesan" name="pesan" rows="5" required></textarea>

        <button type="submit">Kirim</button>
    </form>

    <div style="margin-top: 30px;">
        <h3>Atau hubungi kami melalui:</h3>
        <p>Email: sausanshalihah29@gmail.com</p>
        <p>Instagram: <a href="https://instagram.com/sausnsa" target="_blank">sausnsa</a></p>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
    <p>&copy; <?= date("Y"); ?> Blog Kita. Semua hak dilindungi.</p>
</footer>

</body>
</html>
