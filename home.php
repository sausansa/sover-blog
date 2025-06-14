<?php
include 'koneksi.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search = $conn->real_escape_string($search); // hindari SQL injection

$searchCondition = "";
if ($search !== "") {
    $searchCondition = "WHERE (
        a.title LIKE '%$search%' OR
        a.content LIKE '%$search%' OR
        au.nickname LIKE '%$search%' OR
        c.name LIKE '%$search%' OR
        DATE(a.date) LIKE '%$search%'
    )";
}

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
FROM article a
LEFT JOIN article_author aa ON a.id = aa.article_id
LEFT JOIN author au ON aa.author_id = au.id
LEFT JOIN article_category ac ON a.id = ac.article_id
LEFT JOIN category c ON ac.category_id = c.id
$searchCondition
GROUP BY a.id
ORDER BY a.date DESC
";

$result = $conn->query($sql);
$articles = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Artikel</title>
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
<!-- Welcome Section -->
<div class="welcome-section">
    <h1>Welcome to Sover</h1>
</div>

 
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
        <!-- Card Kategori -->
        <div class="card-box">
            <h3>Kategori</h3>
            <ul>
               <ul>
    <li><a href="kategoriBlog.php?nama=Hiburan">Hiburan</a></li>
    <li><a href="kategoriBlog.php?nama=Pendidikan">Pendidikan</a></li>
    <li><a href="kategoriBlog.php?nama=Taman">Taman</a></li>
    <li><a href="kategoriBlog.php?nama=Alam">Alam</a></li>
</ul>

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
            <div class="artikel-card artikel-utama">
                <h2><?= $row['article_title']; ?></h2>

                <div class="author-profile">
                    <img src="<?= $row['author_profile_picture']; ?>" alt="Gambar Penulis" class="profile-picture">
                    <p class="nama-penulis"><?= $row['authors']; ?></p>
                </div>

                <h4><?= $row['article_date']; ?> | <?= $row['categories']; ?></h4>

                <img src="images/<?= $row['picture']; ?>" alt="Gambar Artikel" class="artikel-gambar-utama">

                <p><?= substr($row['article_content'], 0, 200); ?>...</p>

                <a href="detail.php?id=<?= $row['article_id']; ?>" class="selengkapnya-btn">Selengkapnya</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Tidak ada artikel yang tersedia.</p>
    <?php endif; ?>
</div>





   
    <footer class="footer">
    <p></p>
</footer>

</body>
</html>