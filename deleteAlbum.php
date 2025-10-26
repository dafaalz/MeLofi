<?php
include 'connect.php';
session_start();

// Pastikan hanya admin yang bisa menghapus
if (!isset($_SESSION['username']) || $_SESSION['level_access'] != 'admin') {
    header("Location: index.php?error=login+required");
    exit();
}

// Ambil ID album
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Optional: hapus file cover album di server
    $queryFile = "SELECT cover_album FROM album WHERE id_album=$id";
    $resultFile = mysqli_query($connect, $queryFile);
    if ($row = mysqli_fetch_assoc($resultFile) && !empty($row['cover_album'])) {
        $filePath = './album/' . $row['cover_album'];
        if (file_exists($filePath)) unlink($filePath);
    }

    // Hapus album dari DB
    $query = "DELETE FROM album WHERE id_album=$id";
    if (mysqli_query($connect, $query)) {
        header("Location: manageData.php?success=Album+deleted");
        exit();
    } else {
        echo "Error deleting album: " . mysqli_error($connect);
    }
} else {
    header("Location: manageData.php?error=Invalid+ID");
    exit();
}
?>