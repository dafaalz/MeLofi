<?php include 'connect.php';
session_start();
if (!isset($_SESSION['username']) OR ($_SESSION['level_access'] != 'admin')) {
    header("Location: index.php?error=login+gagal");
    exit();
}

if ($_SESSION['level_access'] == 'user' ) {
    header("Location: index.php?error=akses+ditolak");
    exit();
}

$query = "SELECT * FROM lagu";
$result = mysqli_query($connect, $query);
$tracks = [];

while ($row = mysqli_fetch_assoc($result)) {
    $tracks[] = [
        "judul" => $row["judul"],
        "artis" => $row["artis"],
        "url" => $row["filename"],
        "cover" => $row["cover_album"],
    ];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="header-content">
            <h1>Halaman Admin</h1>
            <nav>
                <a href="logout.php" class="button secondary">Log Out</a>
            </nav>
        </div>
    </header>
    <main>
        <section id="upload">
            <h2>Upload Lagu</h2>
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="judul">Judul Lagu</label>
                    <input type="text" id="judul" name="judul" required>
                </div>
                <div class="form-group">
                    <label for="artis">Artis</label>
                    <input type="text" id="artis" name="artis" required>
                </div>
                <div class="form-group">
                    <label for="album">Album</label>
                    <input type="text" id="album" name="album" required>
                </div>
                <div class="form-group">
                    <label for="cover">Cover Album</label>
                    <input type="file" id="cover" name="cover" accept="image/*" required>
                </div>
                <div class="form-group">
                    <label for="filename">File Lagu (.mp3)</label>
                    <input type="file" id="filename" name="filename" accept="audio/mp3" required>
                </div>
                <div class="form-group">
                    <input type="submit" value="Upload Lagu" class="button primary">
                </div>
            </form>
        </section>

        <section id="songtables">
            <h2>Daftar Lagu</h2>
            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Artis</th>
                        <th>Album</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    mysqli_data_seek($result, 0);
                    $i = 0;
                    while($row = mysqli_fetch_assoc($result)) {
                        $judul = htmlspecialchars($row['judul']);
                        $artis = htmlspecialchars($row['artis']);
                        $album = htmlspecialchars($row['album']);
                        $cover = htmlspecialchars($row['cover_album']);
                        $filename = htmlspecialchars($row['filename']);
                        $laguId = $row['id_lagu'];
                        $audioId = "audio_" . $laguId;
                        echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["judul"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["artis"]) . "</td>";
                            echo "<td>";
                                echo "<img src='cover/" . htmlspecialchars($row["cover_album"]). "' alt='Cover Album " . htmlspecialchars($row["album"]) . "' class='album-cover'>";
                                echo "<p>" . htmlspecialchars($row["album"]) . "</p>";
                            echo "</td>";
                            echo "<td class='actions'>";
                            echo "<audio id='$audioId' src='songs/$filename' aria-label='Audio preview for " . htmlspecialchars($row["judul"]) . "'></audio>";
                            echo "<button class='button primary' onclick=\"playPauseTrack('$audioId', this)\">Play</button>";
                            echo "<a class='button secondary' href='edit.php?id=$laguId'>Edit</a>";
                            echo "<a class='button danger' href='delete.php?id=$laguId'>Hapus</a>";
                            echo "</td>";
                        echo "</tr>";
                        $i++;
                    }
                    ?>
                </tbody>
            </table>
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
</body>
</html>