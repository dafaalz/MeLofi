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

// Fetch albums with artis names for dropdown
$album_query = "SELECT album.id_album, album.nama_album, artis.nama_artis FROM album JOIN artis ON album.id_artis = artis.id_artis";
$album_result = mysqli_query($connect, $album_query);
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
                <label>Album</label>
                <select name="id_album" required>
                    <?php while($album = mysqli_fetch_assoc($album_result)) { ?>
                        <option value="<?php echo $album['id_album']; ?>" <?php if ($album['id_album'] == $row['id_album']) echo 'selected'; ?>>
                            <?php echo $album['nama_album'] . ' - ' . $album['nama_artis']; ?>
                        </option>
                    <?php } ?>
                </select>
                <input type="submit" value="Simpan Perubahan">
            </form>
            <p><a href="adminPage.php">Kembali ke Admin Page</a></p>
        </div>
    </body>
</html>