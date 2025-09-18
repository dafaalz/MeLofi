<?php include 'connect.php';
session_start();

if(!isset($_SESSION['username'])) {
    header("Location: index.php?error=belum+login");
    exit();
}


$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Musik</title>
    <link rel="stylesheet" href="style.css">
</head>
<script>
    function buySong(id) {
        fetch('buy.php', {
            method: 'POST',
            body: new URLSearchParams({ lagu_id: id })
        }).then(r => r.json())
        .then(data => {
            if(data.success) {
                alert("Berhasil dibeli!")
                location.reload()
            }
        })
    }
</script>
<body>
    <h2>Toko Musik - Pilih Lagu untuk Dibelli <button class="button primary"> <a href="library.php">Kembali ke Library</a></button> </h2>
    <table>
        <thead>
            <tr>
                <th>Judul</th>
                <th>Artis</th>
                <th>Album</th>
                <th>Preview</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM lagu WHERE id_lagu NOT IN (
            SELECT lagu_id FROM transaksi WHERE user_id = $user_id)";
            $result = mysqli_query($connect, $query);
            while($row = mysqli_fetch_assoc($result)) {
                $judul = htmlspecialchars($row['judul']);
                $artis = htmlspecialchars($row['artis']);
                $album = htmlspecialchars($row['album']);
                $cover = htmlspecialchars($row['cover_album']);
                $filename = htmlspecialchars($row['filename']);
                $laguId = $row['id_lagu'];
                $audioId = "audio_" . $laguId;

                echo "<tr>";
                    echo "<td>$judul</td>";
                    echo "<td>$artis</td>";
                    echo "<td><img src='cover/$cover' style='height:100px;'><br>$album</td>";

                    echo "<td>
                            <audio id='$audioId' src='songs/$filename'></audio>
                            <button class='button primary' onclick=\"playPauseTrack('$audioId', this)\">Play</button>
                        </td>"; 
                    echo "<td><form action='buy.php' method= 'POST'>
                            <input type='hidden' name='lagu_id' value='$laguId'>
                            <input type='submit' value='Beli'>  
                        </form></td>";
                        echo "<td>$laguId</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

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
                butotn.textContent = 'Play';
            }
        }
    </script>
</body>
</html>