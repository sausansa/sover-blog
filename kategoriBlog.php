<?php
include 'koneksi.php';

$kategori = $_GET['nama'] ?? '';

// Jika tidak ada nama kategori di URL
if (empty($kategori)) {
    echo "Kategori tidak ditemukan.";
    exit;
}

// Ambil artikel berdasarkan kategori
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
    c.name = ?
GROUP BY 
    a.id
ORDER BY 
    a.date DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $kategori);
$stmt->execute();
$result = $stmt->get_result();
$articles = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Artikel Kategori <?= htmlspecialchars($kategori); ?></title>
    <link rel="stylesheet" href="kategori.css">
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
    <a href="login.php">Blog</a>
  </div>
</div>
<!-- Welcome Section -->
<div class="welcome-section">
    <h1>Kategori: <?= htmlspecialchars($kategori); ?></h1>
</div>

<div class="konten-utama">
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Card Pencarian -->
        <div class="card-box">
            <h3>Cari Artikel</h3>
            <form action="index.php" method="GET">
                <input type="text" name="search" placeholder="Ketik kata kunci...">
                <button type="submit">Cari</button>
            </form>
        </div>

        <!-- Card Kategori -->
        <div class="card-box">
            <h3>Kategori</h3>
            <ul>
                <li><a href="kategoriBlog.php?nama=Hiburan">Hiburan</a></li>
                <li><a href="kategoriBlog.php?nama=Pendidikan">Pendidikan</a></li>
                <li><a href="kategoriBlog.php?nama=Alam">Alam</a></li>
                <li><a href="kategoriBlog.php?nama=Taman">Taman</a></li>
            </ul>
        </div>

        <!-- Card Tentang -->
        <div class="card-box">
            <h3>Tentang Website</h3>
            <p>Website ini menyajikan berbagai artikel menarik seputar teknologi, pendidikan, kesehatan, dan lifestyle. Temukan informasi terbaru dan terpercaya yang ditulis oleh penulis berpengalaman.</p>
        </div>
    </div>

    <!-- Konten Artikel -->
    <div class="konten-artikel">
        <?php if (count($articles) > 0): ?>
            <?php foreach ($articles as $row): ?>
                <div class="artikel-card">
                    <h3><?= $row['article_title']; ?></h3>
                    <div class="author-profile">
                        <img src="images/<?= $row['author_profile_picture']; ?>" alt="Gambar Penulis" class="profile-picture">
                        <p class="nama-penulis"><?= $row['authors']; ?></p>
                    </div>
                    <h5><?= $row['article_date']; ?> | <?= $row['categories']; ?></h5>
                    <img src="images/<?= $row['picture']; ?>" alt="Gambar Artikel" class="artikel-gambar-kecil">
                    <p><?= substr($row['article_content'], 0, 150); ?>...</p>
                    <a href="detail.php?id=<?= $row['article_id']; ?>" class="selengkapnya-btn">Selengkapnya</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Tidak ada artikel dalam kategori ini.</p>
        <?php endif; ?>
    </div>
</div>

<footer class="footer">
    <p></p>
</footer>

</body>
</html>
