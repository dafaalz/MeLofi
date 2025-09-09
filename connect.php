<?php
$hostname = "localhost:8889";
$username = "root";
$password = "root";
$database = "melofi";

$connect = mysqli_connect($hostname, $username, $password, $database);
if(!$connect) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>