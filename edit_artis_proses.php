<?php
include 'connect.php';
session_start();

if (!isset($_SESSION['username']) || $_SESSION['level_access'] != 'admin') {
    header("Location: index.php?error=login+required");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nama_artis = mysqli_real_escape_string($connect, $_POST['nama_artis']);
    $deskripsi = mysqli_real_escape_string($connect, $_POST['deskripsi']);

    // Update artis
    $query = "UPDATE artis SET nama_artis='$nama_artis', deskripsi='$deskripsi' WHERE id_artis=$id";

    if (mysqli_query($connect, $query)) {
        // Redirect ke halaman manageData
        header("Location: manageData.php?success=Artis+updated");
        exit();
    } else {
        echo "Error updating artis: " . mysqli_error($connect);
    }
}
?>