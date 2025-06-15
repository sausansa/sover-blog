<?php
include 'koneksi.php';

if (!isset($_GET['id'])) {
    echo "Artikel tidak ditemukan.";
    exit;
}

$id = intval($_GET['id']);

// Ambil data artikel utama
$sql = "
SELECT 
    a.id AS article_id,
    a.title AS article_title,
    a.date AS article_date,
    a.content AS article_content,
    GROUP_CONCAT(DISTINCT au.nickname ORDER BY au.nickname) AS authors,
    GROUP_CONCAT(DISTINCT c.name ORDER BY c.name) AS categories,
    a.picture,
    (
        SELECT au2.profile_picture
        FROM article_author aa2
        JOIN author au2 ON aa2.author_id = au2.id
        WHERE aa2.article_id = a.id
        ORDER BY au2.nickname ASC
        LIMIT 1
    ) AS author_profile_picture
FROM 
    article a
JOIN 
    article_author aa ON a.id = aa.article_id
JOIN 
    author au ON aa.author_id = au.id
JOIN 
    article_category ac ON a.id = ac.article_id
JOIN 
    category c ON ac.category_id = c.id
WHERE 
    a.id = $id
GROUP BY 
    a.id
";

$result = $conn->query($sql);
if ($result->num_rows === 0) {
    echo "Artikel tidak ditemukan.";
    exit;
}
$row = $result->fetch_assoc();

// Ambil artikel terkait berdasarkan kategori (ambil kategori pertama)
$kategoriUtama = explode(',', $row['categories'])[0]; // ambil kategori pertama
$sqlTerkait = "
SELECT a.id, a.title 
FROM article a
JOIN article_category ac ON a.id = ac.article_id
JOIN category c ON ac.category_id = c.id
WHERE c.name = ? AND a.id != ?
ORDER BY a.date DESC
LIMIT 5
";
$stmt = $conn->prepare($sqlTerkait);
$stmt->bind_param("si", $kategoriUtama, $id);
$stmt->execute();
$relatedResult = $stmt->get_result();
$artikelTerkait = $relatedResult->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $row['article_title']; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navbar -->
<div class="navbar">
  <div class="logo-container">
    <img src="images/logo.png" class="logo" alt="Logo">
  </div>
  <div class="nav-links">
    <a href="http://sover-blog.great-site.net/">Home</a>
    <a href="tentang.php">Tentang</a>
    <a href="kontak.php">Kontak</a>
    <a href="adminDashboard/login.php">Blog</a>
  </div>
</div>

<!-- Konten Utama -->
<div class="konten-utama" style="display: flex; gap: 20px; padding: 20px;">
    <!-- Sidebar -->
    <div class="sidebar" style="width: 30%;">
        <!-- Form Pencarian -->
        <div class="card-box">
            <h3>Cari Artikel</h3>
            <form action="#" method="GET">
                <input type="text" name="search" placeholder="Ketik kata kunci...">
                <button type="submit">Cari</button>
            </form>
        </div>

        <!-- Artikel Terkait -->
        <div class="card-box">
            <h3>Artikel Terkait</h3>
            <?php if (count($artikelTerkait) > 0): ?>
                <ul>
                    <?php foreach ($artikelTerkait as $related): ?>
                        <li><a href="detail.php?id=<?= $related['id']; ?>"><?= htmlspecialchars($related['title']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Tidak ada artikel terkait.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Detail Artikel -->
    <div class="detail-container" >
        <h1><?= $row['article_title']; ?></h1>

        <div class="author-profile">
            <img src="<?= $row['author_profile_picture']; ?>" alt="Foto Penulis" class="profile-picture">
            <p class="nama-penulis"><?= $row['authors']; ?></p>
        </div>

        <h4><?= $row['article_date']; ?> | <?= $row['categories']; ?></h4>

        <img src="images/<?= $row['picture']; ?>" alt="Gambar Artikel" class="artikel-gambar">

        <div class="isi-artikel">
            <p><?= nl2br($row['article_content']); ?></p>
        </div>

        <a href="home.php" class="tombol-kembali">← Kembali ke daftar artikel</a>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
    <p>&copy; <?= date("Y"); ?> Blog Kita. Semua hak dilindungi.</p>
</footer>

</body>
</html>
