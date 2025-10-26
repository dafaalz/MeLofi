<?php
include 'connect.php';
session_start();

if (!isset($_SESSION['username']) || $_SESSION['level_access'] != 'admin') {
    header("Location: index.php?error=login+required");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nama_album = mysqli_real_escape_string($connect, $_POST['nama_album']);
    $id_artis = intval($_POST['id_artis']);
    $deskripsi = mysqli_real_escape_string($connect, $_POST['deskripsi']);

    // Update album
    $query = "UPDATE album 
              SET nama_album='$nama_album', id_artis=$id_artis, deskripsi='$deskripsi' 
              WHERE id_album=$id";

    if (mysqli_query($connect, $query)) {
        // Redirect ke halaman manageData
        header("Location: manageData.php?success=Album+updated");
        exit();
    } else {
        echo "Error updating album: " . mysqli_error($connect);
    }
}
?>