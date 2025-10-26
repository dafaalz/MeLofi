<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_artis = mysqli_real_escape_string($connect, $_POST['nama_artis']);
    $deskripsi = mysqli_real_escape_string($connect, $_POST['deskripsi']);

    // handle upload foto profil
    $foto_profil = null;
    if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] == 0) {
        $filename = basename($_FILES['foto_profil']['name']);
        $target_dir = "artis/";
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($_FILES['foto_profil']['tmp_name'], $target_file)) {
            $foto_profil = $target_file; // simpan path ke DB
        } else {
            echo "<script>alert('❌ Gagal upload foto profil.'); window.location='manageData.php';</script>";
            exit;
        }
    }

    // simpan ke database
    $query = "INSERT INTO artis (nama_artis, foto_profil, deskripsi)
              VALUES ('$nama_artis', " . ($foto_profil ? "'$foto_profil'" : "NULL") . ", '$deskripsi')";
    if (mysqli_query($connect, $query)) {
        echo "<script>alert('✅ Artis berhasil ditambahkan.'); window.location='manageData.php';</script>";
    } else {
        echo "<script>alert('❌ Gagal menambahkan artis: " . mysqli_error($connect) . "'); window.location='manageData.php';</script>";
    }
}
?>