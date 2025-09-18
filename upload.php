<?php include 'connect.php';

if($_SERVER['REQUEST_METHOD'] == 'POST')  {
    $judul = $_POST['judul'];
    $artis = $_POST['artis'];
    $album = $_POST['album'];
    $cover = $_FILES['cover'];
    $file_lagu = $_FILES['filename'];

    if($cover['error'] == 0) {
        $nama_cover = basename($album) . ".jpg";
        $target_dir = "cover/";
        $target_file = $target_dir . $nama_cover;

        if(move_uploaded_file($cover['tmp_name'], $target_file)){

        } else {
            echo "Gagal menyimpan cover ke database. <a href='adminPage.php'>kembali</a>";
        }
    } else {
        echo "Gagal mengupload cover. <a href='adminPage.php'>kembali</a>";
    }

    if($file_lagu['error'] == 0) {
        $nama_file = basename($file_lagu['name']);
        $target_dir = "songs/";
        $target_file = $target_dir . $nama_file;
        $nama_cover = basename($album) . ".jpg";

        if(move_uploaded_file($file_lagu['tmp_name'], $target_file)) {
            $query = "INSERT INTO lagu(judul, filename, artis, album, cover_album) VALUES ('$judul', '$nama_file', '$artis', '$album', '$nama_cover')";
            $result = mysqli_query($connect, $query);

            if($result) {
                echo "Lagu berhasil diupload dan disimpan. <a href='adminPage.php'>kembali</a>";
            } else {
                echo "Gagal menyimpan lagu ke database. <a href='adminPage.php'>kembali</a>";
            }
        } else {
            echo "Gagal memindahkan file.";
        }
    } else {
        echo "Terjadi kesalahan saat upload file.";
    }
}
?>
