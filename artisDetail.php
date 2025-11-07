<?php
include 'connect.php';
include 'header.php';
include 'sidebar.php';

$id_artis = $_GET['id'] ?? 0;

// Ambil data artis
$artis = mysqli_query($connect, "SELECT * FROM artis WHERE id_artis = $id_artis");
$artis_data = mysqli_fetch_assoc($artis);

// Ambil semua album artis
$album_result = mysqli_query($connect, "SELECT * FROM album WHERE id_artis = $id_artis");

// Ambil semua lagu artis
$lagu_result = mysqli_query($connect, "
    SELECT l.*, a.nama_album 
    FROM lagu l
    JOIN album a ON l.id_album = a.id_album
    WHERE a.id_artis = $id_artis
");
?>

<main class="app-content">
    <div class="artis-detail-container">
        <div class="artis-header">
            <img src="<?= htmlspecialchars($artis_data['foto_profil'] ?? 'default.jpg') ?>" alt="Foto Profil" class="artis-foto">
            <div class="artis-info">
                <h1><?= htmlspecialchars($artis_data['nama_artis']) ?></h1>
                <p><?= nl2br(htmlspecialchars($artis_data['deskripsi'])) ?></p>
            </div>
        </div>

        <hr class="divider">
        <h2>Album oleh <?= htmlspecialchars($artis_data['nama_artis']) ?></h2>
        <div class="album-list">
            <?php while ($album = mysqli_fetch_assoc($album_result)) { ?>
                <div class="album-card" onclick="window.location='albumDetail.php?id=<?= $album['id_album'] ?>'">
                    <img src="<?= htmlspecialchars($album['cover_album']) ?>" alt="Cover Album" class="album-cover">
                    <p class="album-title"><?= htmlspecialchars($album['nama_album']) ?></p>
                </div>
            <?php } ?>
        </div>

        <hr class="divider">
        <h2>Lagu oleh <?= htmlspecialchars($artis_data['nama_artis']) ?></h2>
        <div class="song-cards-container">
            <?php while ($lagu = mysqli_fetch_assoc($lagu_result)) { ?>
                <div class="song-card">
                    <img src="<?= htmlspecialchars($lagu['cover_album'] ?? 'default.jpg') ?>" alt="Cover Album" class="album-cover">
                    <div class="song-info">
                        <p><strong><?= htmlspecialchars($lagu['judul']) ?></strong></p>
                        <p><?= htmlspecialchars($lagu['nama_album']) ?></p>
                    </div>
                    <div class="song-actions">
                        <button class="button btn-primary btn-sm" onclick="playPreview('<?= htmlspecialchars($lagu['file_lagu']) ?>')">Preview</button>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</main>


<script>
function playPreview(file) {
    const audio = new Audio(file);
    audio.play();
    setTimeout(() => audio.pause(), 30000); // 30 detik preview
}
</script>

<?php include 'footer.php'; ?>
