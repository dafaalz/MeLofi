<?php
include 'connect.php';
session_start();

header('Content-Type: application/json');

if (!isset($_GET['q'])) {
    echo json_encode([]);
    exit();
}

$query = mysqli_real_escape_string($connect, $_GET['q']);

$results = [
    'songs' => [],
    'artists' => [],
    'albums' => []
];

// Search Songs
$songQuery = "SELECT l.id_lagu, l.judul, a.nama_album as album, ar.nama_artis as artis, a.cover_album as cover
              FROM lagu l
              JOIN album a ON l.id_album = a.id_album
              JOIN artis ar ON a.id_artis = ar.id_artis
              WHERE l.judul LIKE '%$query%'
              LIMIT 10";

$songResult = mysqli_query($connect, $songQuery);
while ($row = mysqli_fetch_assoc($songResult)) {
    $results['songs'][] = $row;
}

// Search Artists
$artistQuery = "SELECT id_artis, nama_artis, foto_profil
                FROM artis
                WHERE nama_artis LIKE '%$query%'
                LIMIT 10";

$artistResult = mysqli_query($connect, $artistQuery);
while ($row = mysqli_fetch_assoc($artistResult)) {
    $results['artists'][] = $row;
}

// Search Albums
$albumQuery = "SELECT alb.id_album, alb.nama_album, alb.cover_album, ar.nama_artis as artis
               FROM album alb
               JOIN artis ar ON alb.id_artis = ar.id_artis
               WHERE alb.nama_album LIKE '%$query%'
               LIMIT 10";

$albumResult = mysqli_query($connect, $albumQuery);
while ($row = mysqli_fetch_assoc($albumResult)) {
    $results['albums'][] = $row;
}

echo json_encode($results);
?>
