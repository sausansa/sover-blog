<?php session_start(); ?>
<div class="sidebar p-3" style="width: 200px; min-height: 100vh; position: fixed; background-color: #1A3D63; color: #F3EAE0;">
    <h4 style="color: #F3EAE0;">Admin Panel</h4>
    <ul class="nav flex-column">
        <?php if (isset($_SESSION['author_id'])): ?>
            <li class="nav-item">
                <a class="nav-link" href="artikel.php" style="color: #F3EAE0;">Artikel</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="kategori.php" style="color: #F3EAE0;">Kategori</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="author.php" style="color: #F3EAE0;">Author</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php" style="color: #F3EAE0;">Logout</a>
            </li>
            <?php if (isset($_SESSION['author_nickname'])): ?>
                <li class="nav-item">
                    <a class="nav-link text-warning" href="logout.php" style="color: #F3EAE0;">
                        Logout (<?= htmlspecialchars($_SESSION['author_nickname']) ?>)
                    </a>
                </li>
            <?php endif; ?>
        <?php else: ?>
            <li class="nav-item">
                <a class="nav-link" href="login.php" style="color: #F3EAE0;">Login</a>
            </li>
        <?php endif; ?>
    </ul>
</div>
