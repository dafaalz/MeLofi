<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_artis = intval($_POST['id_artis']);
    $nama_album = mysqli_real_escape_string($connect, $_POST['nama_album']);
    $deskripsi = mysqli_real_escape_string($connect, $_POST['deskripsi']);

    // handle upload cover album
    $cover_album = null;
    if (isset($_FILES['cover_album']) && $_FILES['cover_album']['error'] == 0) {
        $filename = basename($_FILES['cover_album']['name']);
        $target_dir = "cover/";
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($_FILES['cover_album']['tmp_name'], $target_file)) {
            $cover_album = $target_file; // simpan path ke DB
        } else {
            echo "❌ Gagal upload cover album.";
            exit;
        }
    }

    // simpan ke database
    $query = "INSERT INTO album (id_artis, nama_album, cover_album, deskripsi)
              VALUES ($id_artis, '$nama_album', " . ($cover_album ? "'$cover_album'" : "NULL") . ", '$deskripsi')";
    
    if (mysqli_query($connect, $query)) {
        echo "✅ Album berhasil ditambahkan. <a href='manageData.php'>Kembali</a>";
    } else {
        echo "❌ Gagal menambahkan album: " . mysqli_error($connect);
    }
}
?>