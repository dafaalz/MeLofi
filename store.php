<?php include 'connect.php';
session_start();

if(!isset($_SESSION['username'])) {
    header("Location: index.php?error=belum+login");
    exit();
}

$user_id = $_SESSION['user_id'];

include 'header.php';
include 'sidebar.php';
?>

<main class="app-content">
    <h2>Toko Musik - Pilih Lagu untuk Dibelli <button class="button primary" onclick="window.location.href='library.php'">Kembali ke Library</button> </h2>
    
    <div class="song-cards-container">
        <?php
        $query = "SELECT l.id_lagu, l.judul, l.filename, a.nama_album, a.cover_album, ar.nama_artis 
                  FROM lagu l 
                  JOIN album a ON l.id_album = a.id_album 
                  JOIN artis ar ON a.id_artis = ar.id_artis 
                  WHERE l.id_lagu NOT IN (
                    SELECT lagu_id FROM transaksi WHERE user_id = $user_id
                  )";
        $result = mysqli_query($connect, $query);
        while($row = mysqli_fetch_assoc($result)) {
            $judul = htmlspecialchars($row['judul']);
            $artis = htmlspecialchars($row['nama_artis']);
            $album = htmlspecialchars($row['nama_album']);
            $cover = htmlspecialchars($row['cover_album']);
            $filename = "songs/" . htmlspecialchars($row['filename']);
            $laguId = $row['id_lagu'];
            $audioId = "audio_" . $laguId;

            echo "<div class='song-card'>";
                echo "<img class='album-cover' src='$cover' alt='Cover Album'>";
                echo "<div class='song-info'>";
                    echo "<p><strong>Judul:</strong> $judul</p>";
                    echo "<p><strong>Artis:</strong> $artis</p>";
                    echo "<p><strong>Album:</strong> $album</p>";
                echo "</div>";
                echo "<div class='song-actions'>";
                    echo "<audio id='$audioId' src='$filename'></audio>";
                    echo "<button class='button primary' onclick=\"playPauseTrack('$audioId', this)\">Play</button>";
                    echo "<form action='buy.php' method='POST' style='display:inline-block; margin-left:10px;'>";
                        echo "<input type='hidden' name='lagu_id' value='$laguId'>";
                        echo "<input type='submit' value='Beli' class='button success'>";
                    echo "</form>";
                echo "</div>";
            echo "</div>";
        }
        ?>
    </div>

    <script>
        function playPauseTrack(audioId, button) {
            const audio = document.getElementById(audioId);

            document.querySelectorAll('audio').forEach( a => {
                if (a !== audio) {
                    a.pause();
                    a.currentTime = 0; 

                    const otherBtn = document.querySelector(`button[onclick*='${a.id}']`)
                    if (otherBtn) otherBtn.textContent = 'Play';
                }
            })

            if (audio.paused) {
                audio.play();
                button.textContent = 'Pause';
            } else {
                audio.pause();
                button.textContent = 'Play';
            }
        }
    </script>
</main>

<?php include 'footer.php'; ?>