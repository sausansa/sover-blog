<?php

$mysqli = new mysqli("localhost", "root", "12345678", "artikel");
if ($mysqli->connect_errno) {
    die("Gagal koneksi: " . $mysqli->connect_error);
}

// Ambil data author
$result = $mysqli->query("SELECT * FROM author ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Author Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        /* Karena sidebar.php sudah fixed 200px dengan padding p-3, total sekitar 220px */
        .main-content {
            margin-left: 220px; /* sesuaikan dengan sidebar */
            padding: 20px;
        }
        /* Optional: supaya modal gambarnya bulat kecil */
        img.profile-pic {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content" style="margin-left: 200px; padding: 20px; background-color: #F3EAE0; min-height: 100vh;">
    <h2 class="mb-4">Daftar Author</h2>

    <!-- Tombol tambah author -->
    <button class="btn mb-3" data-bs-toggle="modal" data-bs-target="#modalTambahAuthor"
        style="background-color: #1A3D63; color: white; border: none;">
        Tambah Author
    </button>

    <!-- Tabel Author -->
    <table class="table table-bordered align-middle" style="background-color: white; border-color: #1A3D63;">
        <thead style="background-color: #1A3D63; color: #F3EAE0;">
            <tr>
                <th>ID</th>
                <th>Nickname</th>
                <th>Email</th>
                <th>Profile Picture</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>

            <?php while($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['nickname']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td>
                    <?php if ($row['profile_picture']): ?>
                    <img src="uploads/<?= htmlspecialchars($row['profile_picture']) ?>" alt="Profile" class="profile-pic">
                    <?php else: ?>
                    <span class="text-muted">-</span>
                    <?php endif; ?>
                </td>
                <td>
                    <!-- Tombol Ubah -->
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditAuthor<?= $row['id'] ?>">Ubah</button>

                    <!-- Tombol Hapus -->
                    <a href="hapus_author.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus author ini?');">Hapus</a>
                </td>
            </tr>

            <!-- Modal Edit Author -->
            <div class="modal fade" id="modalEditAuthor<?= $row['id'] ?>" tabindex="-1" aria-labelledby="modalEditAuthorLabel<?= $row['id'] ?>" aria-hidden="true">
              <div class="modal-dialog">
                <form action="update_author.php" method="POST" enctype="multipart/form-data">
                  <input type="hidden" name="id" value="<?= $row['id'] ?>">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="modalEditAuthorLabel<?= $row['id'] ?>">Ubah Author</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="mb-3">
                        <label class="form-label">Nickname</label>
                        <input type="text" name="nickname" class="form-control" value="<?= htmlspecialchars($row['nickname']) ?>" required>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($row['email']) ?>" required>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Password (biarkan kosong jika tidak diubah)</label>
                        <input type="password" name="password" class="form-control" placeholder="Password baru">
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Profile Picture (jpg/png)</label>
                        <input type="file" name="profile_picture" accept="image/png, image/jpeg" class="form-control">
                        <?php if ($row['profile_picture']): ?>
                            <small>Gambar saat ini:</small><br>
                            <img src="uploads/<?= htmlspecialchars($row['profile_picture']) ?>" style="width:80px; height:80px; object-fit:cover; border-radius:50%;">
                        <?php endif; ?>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>

            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal Tambah Author -->
<div class="modal fade" id="modalTambahAuthor" tabindex="-1" aria-labelledby="modalTambahAuthorLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="simpan_author.php" method="POST" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTambahAuthorLabel">Tambah Author</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nickname</label>
            <input type="text" name="nickname" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Profile Picture (jpg/png)</label>
            <input type="file" name="profile_picture" accept="image/png, image/jpeg" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan Author</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
