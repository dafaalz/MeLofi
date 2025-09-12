<?php
include 'connect.php';
session_start();

if(!isset($_SESSION['user_id'])) {
    header("Location: index.php?error=belum+login");
    exit();
}

$user = intval($_SESSION['user_id']);

$query = "SELECT l.id_lagu, l.judul, l.artis, l.album, l.filename, l.cover_album
          FROM lagu l
          JOIN transaksi t ON l.id_lagu = t.lagu_id
          WHERE t.user_id = $user";

$result = mysqli_query($connect, $query);

$tracks = [];

while($row = mysqli_fetch_assoc($result)) {
    $tracks[] = [
        "judul" => $row["judul"],
        "artis" => $row["artis"],
        "album" => $row["album"],
        "url" => "songs/" . $row["filename"],
        "cover" => "cover/" .$row["cover_album"]
    ];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="container">
        <div id="header">
            <h2>Library</h2>
        </div>

        <div class="player">
            <div class="detail">
                <div class="track-art"></div>
                <div class="track-name"></div>
                <div class="track-artist"></div>
            </div>
            <div class="buttons">
                <button class="button" onclick="prevClick()">Prev</button>
                <button class="button primary" id="playPause" onclick="playPauseTrack()">Play</button>
                <button class="button" onclick="nextTrack">Next</button>
            </div>
            <input class="slider" type="range" id="seek_slider" min="0" max="100" value="0">
            <input class="slider" type="range" id="vol_slider" min="0" max="100" value="100">
            <div class="controls">
                <button class="button" onclick="shuffleQueue()">Shuffle</button>
                <button class="button" onclick="clearQueue()">Clear Queue</button>
            </div>      

            <div id="queue">
                <h3>Queue</h3>
                <ul id="queueList"></ul>
            </div>
        </div>

        <div id="songtables">
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
                    $i = 0;
                    foreach ($tracks as $row) {
                        echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["judul"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["artis"]) . "</td>";
                            echo "<td>";
                                echo "<img src='" . htmlspecialchars($row["cover"]) . "' style='height: 50px;'>";
                                echo "<p>" . htmlspecialchars($row["album"]) . "</p>";
                            echo "</td>";
                            echo "<td>";
                                echo "<button class=\"button\" onclick=\"loadTrack($i); audio.play()\">Play</button>";
                                echo "<button class=\"button\" onclick=\"addToQueue($i); audio.play()\">Add To Queue</button>";
                            echo "</td>";
                        echo "</tr>";
                        $i++;
                    }
                    
                    ?>
                </tbody>
            </table>
        </div>

        <button class="button primary"><a href="store.php">Beli Lagu</a></button>
        <button class="button primary"><a href="logout.php">Log Out</a></button>
    </div>

    <script>
        const trackList = <?php echo json_encode($tracks, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
        const audio = new Audio();
        let trackIndex = 0;
        let queue = [];

        function loadTrack(i) {
            if (!trackList[i]) return;
            audio.src = trackList[i].url;
            document.querySelector(".track-name").textContent = trackList[i].judul;
            document.querySelector(".track-artist").textContent = trackList[i].artis;

            const trackArtDiv = document.querySelector(".track-art");
            trackArtDiv.style.backgroundImage = `url('${trackList[i].cover}')`;
            trackArtDiv.style.backgroundPosition = 'center';
            trackArtDiv.style.backgroundSize = 'cover';
        }

        function playPauseTrack() {
            if (audio.src === "") loadTrack(trackIndex);
            if (audio.paused) {
                document.getElementById("playPause").innerHTML="Pause";
                audio.play();
            } else {
                document.getElementById("playPause").innerHTML="Play";
                audio.pause();
            }
        }

        function nextTrack() {
            if (queue.length > 0) {
                const next = queue.shift();
                trackIndex = next;
                updateQueueDisplay();
                loadTrack(trackIndex);
                audio.play();
            } else {
                trackIndex = (trackIndex + 1) % trackList.length;
                loadTrack(trackIndex);
                audio.play();
            }
        }

        function prevClick() {
            trackIndex = (trackIndex - 1 + trackList.length) % trackList.length;
            loadTrack(trackIndex);
            audio.play();
        }

        function addToQueue(i) {
            queue.push(i);
            updateQueueDisplay();
        }

        function shuffleQueue() {
            for (let i = queue.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [queue[i], queue[j]] = [queue[j], queue[i]];
            }
            updateQueueDisplay();
        }

        function clearQueue() {
            queue = [];
            updateQueueDisplay();
        }

        function updateQueueDisplay() {
            const queueList = document.getElementById("queueList");
            queueList.innerHTML = "";
            queue.forEach(i => {
                const li = document.createElement("li");
                li.textContent = trackList[i].judul + " - " + trackList[i].artis;
                queueList.appendChild(li);
            });
        }

        window.onload = () => {
            if (trackList.length > 0) loadTrack(trackIndex);

            const seekSlider = document.getElementById("seek_slider");
            const volSlider = document.getElementById("vol_slider");

            audio.addEventListener("timeupdate", () => {
                if (!isNaN(audio.duration)) {
                    seekSlider.value = (audio.currentTime / audio.duration) * 100;
                }
            });

            seekSlider.addEventListener("input", () => {
                if (!isNaN(audio.duration)) {
                    audio.currentTime = (seekSlider.value / 100) * audio.duration;
                }
            });

            volSlider.value = 100;
            audio.volume = 1;
            volSlider.addEventListener("input", () => {
                audio.volume = volSlider.value / 100;
            });

            audio.addEventListener("ended", nextTrack);
        };
    </script>
</body>
</html>