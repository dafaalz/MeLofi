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
                echo "✅ Lagu berhasil diupload dan disimpan. <a href='manageData.php'>kembali</a>";
            } else {
                echo "❌ Gagal menyimpan lagu ke database. <a href='manageData.php'>kembali</a>";
            }
        } else {
            echo "❌ Gagal memindahkan file lagu.";
        }
    } else {
        echo "❌ Terjadi kesalahan saat upload file.";
    }
}
?>