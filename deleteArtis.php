<?php
include 'connect.php';
session_start();

// Pastikan hanya admin yang bisa menghapus
if (!isset($_SESSION['username']) || $_SESSION['level_access'] != 'admin') {
    header("Location: index.php?error=login+required");
    exit();
}

// Ambil ID artis
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Optional: hapus file foto profil di server
    $queryFile = "SELECT foto_profil FROM artis WHERE id_artis=$id";
    $resultFile = mysqli_query($connect, $queryFile);
    if ($row = mysqli_fetch_assoc($resultFile) && !empty($row['foto_profil'])) {
        $filePath = 'uploads/artis/' . $row['foto_profil'];
        if (file_exists($filePath)) unlink($filePath);
    }

    // Hapus artis dari DB
    $query = "DELETE FROM artis WHERE id_artis=$id";
    if (mysqli_query($connect, $query)) {
        header("Location: manageData.php?success=Artis+deleted");
        exit();
    } else {
        echo "Error deleting artis: " . mysqli_error($connect);
    }
} else {
    header("Location: manageData.php?error=Invalid+ID");
    exit();
}
?>