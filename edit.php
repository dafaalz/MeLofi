<?php include 'connect.php';
session_start();

if (!isset($_SESSION['username']) OR ($_SESSION['level_access'] != 'admin')) {
    header("Location: index.php?error=login+gagal");
    exit();
}

$id = $_GET['id'];
$query = "SELECT * FROM lagu WHERE id_lagu = $id";
$result = mysqli_query($connect, $query);
$row = mysqli_fetch_assoc($result);
?>

<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <title>Edit Lagu</title>
    </head>
    <body>
        <div id="container">
            <h2>Edit Info Lagu</h2>
            <form action="edit_proses.php" method="post">
                <input type="hidden" name="id" value="<?php echo $row['id_lagu'];?>" required>
                <label>Judul</label>
                <input type="text" name="judul" value="<?php echo $row['judul'];?>" required>
                <label>Artis</label>
                <input type="text" name="artis" value="<?php echo $row['artis'];?>" required>
                <label>Album</label>
                <input type="text" name="album" value="<?php echo $row['album'];?>" required>
                <input type="submit" value="Simpan Perubahan">
            </form>
            <p><a href="adminPage.php">Kembali ke Admin Page</a></p>
        </div>
    </body>
</html>