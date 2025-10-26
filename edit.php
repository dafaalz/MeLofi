<?php
include 'connect.php';
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
$artis_query = "SELECT * FROM artis";
$artis_result = mysqli_query($connect, $artis_query);

$album_query = "SELECT album.id_album, album.nama_album, artis.nama_artis FROM album JOIN artis ON album.id_artis = artis.id_artis";
$album_result = mysqli_query($connect, $album_query);

include 'header.php';
include 'sidebar.php';
?>

<main class="app-content">
    <div class="edit-container">
        <h2 class="page-title">Edit Info Lagu</h2>
        <form action="edit_proses.php" method="post">
            <input type="hidden" name="id" value="<?php echo $row['id_lagu'];?>" required>

            <div class="form-group">
                <label for="judul">Judul</label>
                <input type="text" id="judul" name="judul" class="form-control" value="<?php echo $row['judul'];?>" required>
            </div>

            <div class="form-group">
                <label for="id_artis">Artis</label>
                <select name="id_artis" id="id_artis" class="select-box" required>
                    <option value="">-- Pilih Artis --</option>
                    <?php while($artis = mysqli_fetch_assoc($artis_result)) { ?>
                        <option value="<?php echo $artis['id_artis']; ?>">
                            <?php echo $artis['nama_artis']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="id_album">Album</label>
                <select name="id_album" id="id_album" class="select-box" required>
                    <option value="">-- Pilih Album --</option>
                </select>
            </div>

            <div class="form-actions">
                <input type="submit" value="Simpan Perubahan" class="btn-primary">
                <a href="adminPage.php" class="btn-secondary">Kembali ke Admin Page</a>
            </div>
        </form>
    </div>

    <script>
    document.getElementById('id_artis').addEventListener('change', function() {
        var idArtis = this.value;
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_albums.php?id_artis=' + idArtis, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('id_album').innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    });
    </script>
</main>

<?php include 'footer.php'; ?>