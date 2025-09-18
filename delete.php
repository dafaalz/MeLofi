<?php
 include 'connect.php';

session_start();

if(!isset($_SESSION['level_access']) OR $_SESSION['level_access'] != 'admin') {
    header("Location: index.php?error=akses+ditolak");
    exit();
}

$id = $_GET['id'];

$query = "SELECT filename, cover_album FROM lagu WHERE id_lagu=$id";
$result = mysqli_query($connect, $query);
$row = mysqli_fetch_assoc($result);

if($row) {
    unlink("songs/" . $row['filename']);
    unlink("cover/" . $row['cover_album']);
}


$delete = "DELETE FROM lagu WHERE id_lagu = $id";
mysqli_query($connect, $delete);

header("Location: adminPage.php");
exit();
?>