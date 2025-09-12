<?php include 'connect.php';
session_start();

if(!isset($_SESSION['user_id'])) {
    header("Location: index/php?error=belum+login");
    exit();
}

$user_id = $_SESSION['user_id'];
$lagu_id = $_POST['lagu_id'];

$cekQuery = "SELECT * FROM transaksi WHERE user_id = $user_id AND lagu_id = $lagu_id";
$cek = mysqli_query($connect, $cekQuery);

if(mysqli_num_rows($cek) > 0 ) {
    header("Location:store.php?error=sudah+dibeli");
    exit();
}

$query = "INSERT INTO transaksi (user_id, lagu_id) VALUES ($user_id, $lagu_id)";
if(mysqli_query($connect, $query)) {
    header("Location: library.php?sukses=berhasil+dibeli");
} else {
    echo "Gagal membeli lagu: " . mysqli_error($connect);
}