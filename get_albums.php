<?php
include 'connect.php';

if (isset($_GET['id_artis'])) {
    $id_artis = intval($_GET['id_artis']);
    $query = "SELECT * FROM album WHERE id_artis = $id_artis";
    $result = mysqli_query($connect, $query);

    echo '<option value="">-- Pilih Album --</option>';
    while ($album = mysqli_fetch_assoc($result)) {
        echo '<option value="' . $album['id_album'] . '">' . $album['nama_album'] . '</option>';
    }
} else {
    echo '<option value="">-- Pilih Album --</option>';
}
?>