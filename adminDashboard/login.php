<?php
session_start();
if (isset($_SESSION['author_id'])) {
    header("Location: artikel.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Login Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body style="display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background-color: #F3EAE0;">
    <div class="container" style="max-width: 400px; background-color: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        <h3 class="mb-4 text-center" style="color: #1A3D63;">Login Admin</h3>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?=htmlspecialchars($_GET['error'])?></div>
        <?php endif; ?>

        <form method="POST" action="proses_login.php">
            <div class="mb-3">
                <label for="email" class="form-label" style="color: #1A3D63;">Email</label>
                <input type="email" class="form-control" name="email" id="email" required />
            </div>
            <div class="mb-3">
                <label for="password" class="form-label" style="color: #1A3D63;">Password</label>
                <input type="password" class="form-control" name="password" id="password" required />
            </div>
            <button type="submit" class="btn w-100" style="background-color: #1A3D63; color: white;">Login</button>
        </form>
    </div>
</body>
</html>
