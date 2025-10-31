<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include "connect.php";
session_start();

// Sanitize inputs
$username = mysqli_real_escape_string($connect, $_POST['username']);
$password = md5($_POST['password']); // Consider using password_hash() in production

if(isset($_POST['Login'])) {
    // Login logic
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($connect, $query);

    if($result AND mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $username;
        $_SESSION['level_access'] = $row['level_access'];
        $_SESSION['user_id'] = $row['id_user'];
        
        // Redirect based on user level
        if($row['level_access'] == 'admin') {
            header("Location: adminPage.php");
            exit();
        } else {
            header("Location: library.php");
            exit();
        }
    } else {
        header("Location: index.php?error=login+gagal");
        exit();
    }
    
} else if (isset($_POST['Register'])) {
    // Registration logic
    
    // Validate inputs
    if (empty($username) || empty($_POST['password'])) {
        header("Location: index.php?error=field+kosong");
        exit();
    }
    
    // Check if username already exists
    $checkQuery = "SELECT * FROM users WHERE username = '$username'";
    $checkResult = mysqli_query($connect, $checkQuery);

    if($checkResult && mysqli_num_rows($checkResult) > 0) {
        header("Location: index.php?error=username+digunakan");
        exit();
    }
    
    // Insert new user
    $insertQuery = "INSERT INTO users (username, password, level_access) VALUES ('$username', '$password', 'user')";
    $insertResult = mysqli_query($connect, $insertQuery);

    if($insertResult) {
        // Auto-login after registration
        $userId = mysqli_insert_id($connect);
        $_SESSION['username'] = $username;
        $_SESSION['level_access'] = 'user';
        $_SESSION['user_id'] = $userId;
        
        header("Location: library.php?success=registration+successful");
        exit();
    } else {
        header("Location: index.php?error=gagal+register");
        exit();
    }
    
} else {
    header("Location: index.php?error=invalid+request");
    exit();
}
?>
