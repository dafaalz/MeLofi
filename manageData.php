<?php
include 'connect.php';
session_start();
if (!isset($_SESSION['username']) OR ($_SESSION['level_access'] != 'admin')) {
    header("Location: index.php?error=login+required");
    exit();
}

$artis = mysqli_query($connect, "SELECT * FROM artis");

$album = mysqli_query($connect, "SELECT album.*, artis.nama_artis 
                                 FROM album
                                 JOIN artis ON album.id_artis = artis.id_artis");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="header-content">
        <h1>Kelola Data Musik</h1>
        <nav>
            <a href="adminPage.php" class="button secondary">Kembali ke Dashboard</a>
            <a href="logout.php" class="button secondary">Log Out</a>
        </nav>
    </div>
</header>
<main>

    <section>
        <h2>Tambah Artis</h2>
        <form action="upload_artis.php" method="post" enctype="multipart/form-data">
            <label>Nama Artis</label>
            <input type="text" name="nama_artis" required>
            <label>Foto Profil</label>
            <input type="file" name="foto_profil" accept="image/*">
            <label>Deskripsi</label>
            <textarea name="deskripsi"></textarea>
            <input type="submit" value="Tambah Artis" class="button primary">
        </form>
    </section>

    <section>
        <h2>Tambah Album</h2>
        <form action="upload_album.php" method="post" enctype="multipart/form-data">
            <label>Pilih Artis</label>
            <select name="id_artis" required>
                <option value="">-- Pilih Artis --</option>
                <?php while($ar = mysqli_fetch_assoc($artis)) { ?>
                    <option value="<?= $ar['id_artis'] ?>"><?= htmlspecialchars($ar['nama_artis']) ?></option>
                <?php } ?>
            </select>
            <label>Nama Album</label>
            <input type="text" name="nama_album" required>
            <label>Cover Album</label>
            <input type="file" name="cover_album" accept="image/*">
            <label>Deskripsi</label>
            <textarea name="deskripsi"></textarea>
            <input type="submit" value="Tambah Album" class="button primary">
        </form>
    </section>

    <section>
        <h2>Tambah Lagu</h2>
        <form action="upload_lagu.php" method="post" enctype="multipart/form-data">
            <label>Pilih Artis</label>
            <select id="selectArtis" name="id_artis" required>
                <option value="">-- Pilih Artis --</option>
                <?php mysqli_data_seek($artis, 0); while($ar = mysqli_fetch_assoc($artis)) { ?>
                    <option value="<?= $ar['id_artis'] ?>"><?= htmlspecialchars($ar['nama_artis']) ?></option>
                <?php } ?>
            </select>
            
            <label>Pilih Album</label>
            <select id="selectAlbum" name="id_album" required>
                <option value="">-- Pilih Album --</option>
            </select>
            
            <label>Judul Lagu</label>
            <input type="text" name="judul" required>
            <label>File Lagu (.mp3)</label>
            <input type="file" name="filename" accept="audio/mp3" required>
            <input type="submit" value="Tambah Lagu" class="button primary">
        </form>
    </section>

</main>

<script>
// load album berdasarkan artis
document.getElementById('selectArtis').addEventListener('change', function() {
    const artisId = this.value;
    const albumSelect = document.getElementById('selectAlbum');
    albumSelect.innerHTML = '<option>Loading...</option>';

    fetch('get_album.php?id_artis=' + artisId)
        .then(response => response.json())
        .then(data => {
            albumSelect.innerHTML = '<option value="">-- Pilih Album --</option>';
            data.forEach(album => {
                const opt = document.createElement('option');
                opt.value = album.id_album;
                opt.textContent = album.nama_album;
                albumSelect.appendChild(opt);
            });
        });
});
</script>
</body>
</html>