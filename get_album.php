<?php
include 'connect.php';

// cek parameter id_artis
if (!isset($_GET['id_artis'])) {
    echo json_encode([]);
    exit;
}

$id_artis = intval($_GET['id_artis']);

// ambil album sesuai artis
$query = "SELECT id_album, nama_album FROM album WHERE id_artis = $id_artis";
$result = mysqli_query($connect, $query);

$albums = [];
while ($row = mysqli_fetch_assoc($result)) {
    $albums[] = [
        'id_album' => $row['id_album'],
        'nama_album' => $row['nama_album']
    ];
}

// kembalikan sebagai JSON
header('Content-Type: application/json');
echo json_encode($albums);
?>