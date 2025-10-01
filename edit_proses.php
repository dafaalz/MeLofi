<?php include 'connect.php';
session_start();

if(!isset($_SESSION['username']) || $_SESSION['level_access'] != 'admin') {
    header("Location: index.php?error=akses+ditolak");
    exit();
}

$id = $_POST['id'];
$judul = $_POST['judul'];
$id_album = $_POST['id_album'];

$query = "UPDATE lagu SET judul='$judul', id_album='$id_album' WHERE id_lagu='$id'";
mysqli_query($connect, $query);

header("Location: adminPage.php");
exit();
?>