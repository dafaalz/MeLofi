<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include "connect.php";
session_start();

$username = $_POST['username'];
$password = md5($_POST['password']);

if(isset($_POST['Login'])) {
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($connect, $query);

    if($result AND mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $username;
        $_SESSION['level_access'] = $row['level_access'];
        $_SESSION['user_id'] = $row['id_user'];
        
        if($row['level_access'] == 'admin') {
            header("Location: adminPage.php");
            exit();
        } else if ($row['level_access'] == 'user') {
            header("Location: library.php");
            exit();
        }
    } else {
        header("Location: index.php?error=login+gagal");
        exit();
    }
} else if (isset($_POST['Register'])) {
    $checkQuery = "SELECT * FROM users WHERE username = '$username'";
    $checkResult = mysqli_query($connect, $checkQuery);

    if($checkResult && mysqli_num_rows($checkResult) > 0) {
        header("Location: index.php?error=username+digunakan");
        exit();
    } else {
        $insertQuery = "INSERT INTO users (`id_user`, `username`, `password`, `level_access`) VALUES (NULL, '$username', '$password', 'user')";
        $insertResult = mysqli_query($connect, $insertQuery);

        if($insertResult) {
            echo "<h2>Berhasil menambahkan user $username pada data user. Silahkan <a href='index.php'>login kembali</a>.</h2>";
        } else {
            header("Location: index.php?error=gagal+register");
            exit();
        }
    }
} else {
    header("Location: index.php?error=login+gagal");
    echo "AC";
    exit();
}
?>
