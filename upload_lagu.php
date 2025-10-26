<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = mysqli_real_escape_string($connect, $_POST['judul']);
    $id_album = intval($_POST['id_album']);
    $file_lagu = $_FILES['filename'];

    if ($file_lagu['error'] == 0) {
        $nama_file = basename($file_lagu['name']);
        $target_dir = "songs/";
        $target_file = $target_dir . $nama_file;

        if (move_uploaded_file($file_lagu['tmp_name'], $target_file)) {
            $query = "INSERT INTO lagu (judul, filename, id_album) 
                      VALUES ('$judul', '$nama_file', $id_album)";
            $result = mysqli_query($connect, $query);

            if ($result) {
                echo "<script>alert('✅ Lagu berhasil diupload dan disimpan.'); window.location='manageData.php';</script>";
            } else {
                echo "<script>alert('❌ Gagal menyimpan lagu ke database.'); window.location='manageData.php';</script>";
            }
        } else {
            echo "<script>alert('❌ Gagal memindahkan file lagu.'); window.location='manageData.php';</script>";
        }
    } else {
        echo "<script>alert('❌ Terjadi kesalahan saat upload file.'); window.location='manageData.php';</script>";
    }
}
?>