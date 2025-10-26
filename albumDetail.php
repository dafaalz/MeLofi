<?php
include 'connect.php';
include 'header.php';
include 'sidebar.php';

$id_album = $_GET['id'] ?? 0;

// Ambil data album dan artis
$album_query = mysqli_query($connect, "
    SELECT a.*, r.nama_artis, r.id_artis 
    FROM album a
    JOIN artis r ON a.id_artis = r.id_artis
    WHERE a.id_album = $id_album
");
$album_data = mysqli_fetch_assoc($album_query);

// Ambil lagu dalam album
$lagu_query = mysqli_query($connect, "SELECT * FROM lagu WHERE id_album = $id_album");
?>

<main class="app-content">
    <div class="album-detail-container">
        <div class="album-header">
            <img src="<?= htmlspecialchars($album_data['cover_album'] ?? 'default.jpg') ?>" alt="Cover Album" class="album-cover-large">
            <div class="album-info">
                <h1><?= htmlspecialchars($album_data['nama_album']) ?></h1>
                <p><a href="artisDetail.php?id=<?= $album_data['id_artis'] ?>">oleh <?= htmlspecialchars($album_data['nama_artis']) ?></a></p>
                <p><?= nl2br(htmlspecialchars($album_data['deskripsi'])) ?></p>
            </div>
        </div>

        <hr class="divider">
        <h2>Daftar Lagu</h2>
        <div class="song-list">
            <?php while ($lagu = mysqli_fetch_assoc($lagu_query)) { ?>
                <div class="song-card">
                    <p><strong><?= htmlspecialchars($lagu['judul']) ?></strong></p>
                </div>
            <?php } ?>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>