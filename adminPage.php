<?php include 'connect.php';
session_start();
if (!isset($_SESSION['username']) OR ($_SESSION['level_access'] != 'admin')) {
    header("Location: index.php?error=login+required");
    exit();
}

$query = "SELECT l.id_lagu, l.judul, l.filename, a.nama_album, ar.nama_artis, a.cover_album
          FROM lagu l
          JOIN album a ON l.id_album = a.id_album
          JOIN artis ar ON a.id_artis = ar.id_artis";
$result = mysqli_query($connect, $query);
$tracks = [];

while ($row = mysqli_fetch_assoc($result)) {
    $tracks[] = [
        "judul" => $row["judul"],
        "artis" => $row["nama_artis"],
        "url" => $row["filename"],
        "cover" => $row["cover_album"],
    ];
}

include 'header.php';
include 'sidebar.php';
?>

<main class="app-content" style="padding: 20px;">
    <h1 id="content-heading">Halaman Admin</h1>
    <br>
    <div class="admin-actions">
        <a href="manageData.php" class="button primary" style="text-align: center;">Kelola Data <br> (Artis, Album, Lagu)</a>
        <a href="logout.php" class="button secondary">Log Out</a>
    </div>

    <section id="songtables">
        <br>
        <h2>Daftar Lagu</h2>
        <div class="song-cards-container">
        <?php
        mysqli_data_seek($result, 0);
        while($row = mysqli_fetch_assoc($result)) {
            $judul = htmlspecialchars($row['judul']);
            $artis = htmlspecialchars($row['nama_artis']);
            $album = htmlspecialchars($row['nama_album']);
            $cover = htmlspecialchars($row['cover_album']);
            $filename = htmlspecialchars($row['filename']);
            $laguId = $row['id_lagu'];
            $audioId = "audio_" . $laguId;
            echo "<div class='song-card'>";
                echo "<img src='$cover' alt='Cover Album $album' class='album-cover'>";
                echo "<div class='song-info'>";
                    echo "<p><strong>Judul:</strong> $judul</p>";
                    echo "<p><strong>Artis:</strong> $artis</p>";
                    echo "<p><strong>Album:</strong> $album</p>";
                echo "</div>";
                echo "<div class='song-actions'>";
                    echo "<audio id='$audioId' src='songs/$filename' aria-label='Audio preview for $judul' style='display:none;'></audio>";
                    echo "<button class='button primary' onclick=\"playPauseTrack('$audioId', this)\">Play</button> ";
                    echo "<a class='button secondary' href='edit.php?id=$laguId'>Edit</a> ";
                    echo "<a class='button danger' href='delete.php?id=$laguId'>Hapus</a>";
                echo "</div>";
            echo "</div>";
        }
        ?>
        </div>
    </section>
</main>

<script>
    function playPauseTrack(audioId, button) {
        const audio = document.getElementById(audioId);

        document.querySelectorAll('audio').forEach(a => {
            if (a !== audio) {
                a.pause();
                a.currentTime = 0;
                const otherBtn = document.querySelector(`button[onclick*='${a.id}']`);
                if (otherBtn) otherBtn.textContent = "Play";
            }
        });

        if(audio.paused) {
            audio.play();
            button.textContent = 'Pause';
        } else {
            audio.pause();
            button.textContent = 'Play';
        }
    }
</script>

<?php include 'footer.php'; ?>