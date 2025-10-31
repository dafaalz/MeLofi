<?php include 'connect.php';
session_start();

if(!isset($_SESSION['username'])) {
    header("Location: index.php?error=belum+login");
    exit();
}

$user_id = $_SESSION['user_id'];

// Pagination and filter settings
$items_per_page = 12;
$current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($current_page - 1) * $items_per_page;

// Filter and search
$search = isset($_GET['search']) ? mysqli_real_escape_string($connect, $_GET['search']) : '';
$artist_filter = isset($_GET['artist']) ? intval($_GET['artist']) : 0;
$album_filter = isset($_GET['album']) ? intval($_GET['album']) : 0;

// Build query with filters
$where_clauses = ["l.id_lagu NOT IN (SELECT lagu_id FROM transaksi WHERE user_id = $user_id)"];

if ($search != '') {
    $where_clauses[] = "(l.judul LIKE '%$search%' OR ar.nama_artis LIKE '%$search%' OR a.nama_album LIKE '%$search%')";
}

if ($artist_filter > 0) {
    $where_clauses[] = "ar.id_artis = $artist_filter";
}

if ($album_filter > 0) {
    $where_clauses[] = "a.id_album = $album_filter";
}

$where_sql = "WHERE " . implode(" AND ", $where_clauses);

// Count total items
$count_query = "SELECT COUNT(*) as total 
                FROM lagu l 
                JOIN album a ON l.id_album = a.id_album 
                JOIN artis ar ON a.id_artis = ar.id_artis 
                $where_sql";
$count_result = mysqli_query($connect, $count_query);
$total_items = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_items / $items_per_page);

// Get songs with pagination
$query = "SELECT l.id_lagu, l.judul, l.filename, a.id_album, a.nama_album, a.cover_album, ar.id_artis, ar.nama_artis 
          FROM lagu l 
          JOIN album a ON l.id_album = a.id_album 
          JOIN artis ar ON a.id_artis = ar.id_artis 
          $where_sql
          ORDER BY l.id_lagu DESC
          LIMIT $items_per_page OFFSET $offset";
$result = mysqli_query($connect, $query);

// Get filter options
$artists_query = "SELECT DISTINCT ar.id_artis, ar.nama_artis FROM artis ar ORDER BY ar.nama_artis";
$artists = mysqli_query($connect, $artists_query);

$albums_query = "SELECT DISTINCT a.id_album, a.nama_album, ar.nama_artis 
                 FROM album a 
                 JOIN artis ar ON a.id_artis = ar.id_artis 
                 ORDER BY a.nama_album";
$albums = mysqli_query($connect, $albums_query);

include 'header.php';
include 'sidebar.php';
?>

<main class="app-content">
    <div class="store-header">
        <h2>Music Store</h2>
        <button class="button primary" onclick="window.location.href='library.php'">Go to Library</button>
    </div>

    <!-- Filter and Search Section -->
    <div class="filter-section">
        <form method="GET" action="store.php" class="filter-form">
            <div class="filter-group">
                <input type="text" name="search" placeholder="Search songs, artists, albums..." 
                       value="<?php echo htmlspecialchars($search); ?>" class="filter-input">
            </div>
            
            <div class="filter-group">
                <select name="artist" class="filter-select">
                    <option value="0">All Artists</option>
                    <?php while($artist = mysqli_fetch_assoc($artists)) { ?>
                        <option value="<?php echo $artist['id_artis']; ?>" 
                                <?php echo ($artist_filter == $artist['id_artis']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($artist['nama_artis']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            
            <div class="filter-group">
                <select name="album" class="filter-select">
                    <option value="0">All Albums</option>
                    <?php while($album = mysqli_fetch_assoc($albums)) { ?>
                        <option value="<?php echo $album['id_album']; ?>" 
                                <?php echo ($album_filter == $album['id_album']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($album['nama_album']) . ' - ' . htmlspecialchars($album['nama_artis']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            
            <div class="filter-actions">
                <button type="submit" class="button primary">Apply Filters</button>
                <a href="store.php" class="button secondary">Clear</a>
            </div>
        </form>
    </div>

    <!-- Results info -->
    <div class="results-info">
        <p>Showing <?php echo $offset + 1; ?>-<?php echo min($offset + $items_per_page, $total_items); ?> of <?php echo $total_items; ?> songs</p>
    </div>

    <!-- Songs Grid -->
    <div class="song-cards-container">
        <?php
        if (mysqli_num_rows($result) == 0) {
            echo "<p style='text-align:center; grid-column: 1/-1; padding: 2rem;'>No songs found matching your criteria.</p>";
        }
        
        while($row = mysqli_fetch_assoc($result)) {
            $judul = htmlspecialchars($row['judul']);
            $artis = htmlspecialchars($row['nama_artis']);
            $album = htmlspecialchars($row['nama_album']);
            $cover = htmlspecialchars($row['cover_album']);
            $filename = "songs/" . htmlspecialchars($row['filename']);
            $laguId = $row['id_lagu'];
            $audioId = "audio_" . $laguId;
            $id_artis = $row['id_artis'];
            $id_album = $row['id_album'];
            $price = 0.99; // You can make this dynamic from database

            echo "<div class='song-card' id='song-$laguId'>";
                echo "<img class='album-cover' src='$cover' alt='Cover Album'>";
                echo "<div class='song-info'>";
                    echo "<p><strong>Judul:</strong> $judul</p>";
                    echo "<p><strong>Artis:</strong> <a href='artisDetail.php?id=$id_artis'>$artis</a></p>";
                    echo "<p><strong>Album:</strong> <a href='albumDetail.php?id=$id_album'>$album</a></p>";
                    echo "<p class='song-price'><strong>Price:</strong> $$price</p>";
                echo "</div>";
                echo "<div class='song-actions'>";
                    echo "<audio id='$audioId' src='$filename'></audio>";
                    // Preview button with 30-second limit
                    echo "<button class='button secondary' onclick=\"previewTrack('$audioId', this, $laguId)\">Preview</button>";
                    // Add to cart button
                    echo "<button class='button success' onclick=\"addToCart($laguId, '$judul', '$artis', $price, '$cover')\">Add to Cart</button>";
                echo "</div>";
                echo "<div class='preview-timer' id='timer-$laguId' style='display:none;'></div>";
            echo "</div>";
        }
        ?>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
    <div class="pagination">
        <?php if ($current_page > 1): ?>
            <a href="?page=<?php echo $current_page - 1; ?>&search=<?php echo urlencode($search); ?>&artist=<?php echo $artist_filter; ?>&album=<?php echo $album_filter; ?>" 
               class="button secondary">Previous</a>
        <?php endif; ?>
        
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <?php if ($i == $current_page): ?>
                <span class="button primary current-page"><?php echo $i; ?></span>
            <?php elseif ($i == 1 || $i == $total_pages || abs($i - $current_page) <= 2): ?>
                <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&artist=<?php echo $artist_filter; ?>&album=<?php echo $album_filter; ?>" 
                   class="button secondary"><?php echo $i; ?></a>
            <?php elseif (abs($i - $current_page) == 3): ?>
                <span class="button secondary disabled">...</span>
            <?php endif; ?>
        <?php endfor; ?>
        
        <?php if ($current_page < $total_pages): ?>
            <a href="?page=<?php echo $current_page + 1; ?>&search=<?php echo urlencode($search); ?>&artist=<?php echo $artist_filter; ?>&album=<?php echo $album_filter; ?>" 
               class="button secondary">Next</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</main>

<style>
.store-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.filter-section {
    background: #ffffff;
    border: 1px solid #eaeaea;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.filter-form {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr auto;
    gap: 1rem;
    align-items: center;
}

.filter-input,
.filter-select {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #eaeaea;
    border-radius: 8px;
    font-size: 0.95rem;
}

.filter-actions {
    display: flex;
    gap: 0.5rem;
}

.results-info {
    margin-bottom: 1.5rem;
    color: #555;
    text-align: center;
}

.song-price {
    color: #28a745;
    font-size: 1.1rem;
}

.preview-timer {
    text-align: center;
    font-size: 0.9rem;
    color: #ff4444;
    margin-top: 0.5rem;
    font-weight: 600;
}

/* Pagination Styles */
.pagination {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 2rem;
    flex-wrap: wrap;
}

.pagination .button {
    min-width: 40px;
    text-align: center;
}

.pagination .current-page {
    background: #111111;
    color: #ffffff;
}

.pagination .disabled {
    cursor: default;
    opacity: 0.5;
}

@media (max-width: 768px) {
    .filter-form {
        grid-template-columns: 1fr;
    }
    
    .filter-actions {
        flex-direction: column;
    }
}
</style>

<script>
let currentlyPlaying = null;
let previewTimers = {};

function previewTrack(audioId, button, songId) {
    const audio = document.getElementById(audioId);
    const timerDiv = document.getElementById('timer-' + songId);
    
    // Stop other previews
    if (currentlyPlaying && currentlyPlaying !== audio) {
        currentlyPlaying.pause();
        currentlyPlaying.currentTime = 0;
        const otherBtn = document.querySelector(`button[onclick*='${currentlyPlaying.id}']`);
        if (otherBtn) otherBtn.textContent = 'Preview';
        
        // Clear other timers
        for (let id in previewTimers) {
            clearInterval(previewTimers[id]);
            const otherTimer = document.getElementById('timer-' + id);
            if (otherTimer) otherTimer.style.display = 'none';
        }
    }
    
    if (audio.paused) {
        audio.play();
        button.textContent = 'Stop';
        timerDiv.style.display = 'block';
        currentlyPlaying = audio;
        
        // 30-second preview limit
        let timeLeft = 30;
        timerDiv.textContent = `Preview: ${timeLeft}s remaining`;
        
        previewTimers[songId] = setInterval(() => {
            timeLeft--;
            timerDiv.textContent = `Preview: ${timeLeft}s remaining`;
            
            if (timeLeft <= 0) {
                audio.pause();
                audio.currentTime = 0;
                button.textContent = 'Preview';
                timerDiv.style.display = 'none';
                clearInterval(previewTimers[songId]);
                delete previewTimers[songId];
                
                // Show purchase prompt
                alert('Preview ended! Add this song to your cart to enjoy the full version.');
            }
        }, 1000);
        
        // Also stop when audio ends naturally
        audio.onended = () => {
            audio.currentTime = 0;
            button.textContent = 'Preview';
            timerDiv.style.display = 'none';
            if (previewTimers[songId]) {
                clearInterval(previewTimers[songId]);
                delete previewTimers[songId];
            }
        };
    } else {
        audio.pause();
        audio.currentTime = 0;
        button.textContent = 'Preview';
        timerDiv.style.display = 'none';
        if (previewTimers[songId]) {
            clearInterval(previewTimers[songId]);
            delete previewTimers[songId];
        }
        currentlyPlaying = null;
    }
}
</script>

<?php include 'footer.php'; ?>
