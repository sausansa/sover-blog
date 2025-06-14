<?php
$k = new mysqli("localhost","root","12345678","artikel");
if (isset($_POST['ubah'])) {
    $id = intval($_POST['id']);
    $judul = $k->real_escape_string($_POST['title']);
    $konten = $k->real_escape_string($_POST['content']);
    if (isset($_FILES['picture']) && $_FILES['picture']['size']>0) {
        // validasi & upload gambar baru...
        // update field picture
    }
    $sql = "UPDATE article SET title='$judul', content='$konten' WHERE id=$id";
    $k->query($sql);
}
header("Location: artikel.php");
