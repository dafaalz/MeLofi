<?php
include 'connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?error=belum+login");
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_POST['song_ids']) || !isset($_POST['payment_method'])) {
    header("Location: store.php?error=invalid+purchase");
    exit();
}

$song_ids = explode(',', $_POST['song_ids']);
$payment_method = mysqli_real_escape_string($connect, $_POST['payment_method']);

$purchased_songs = [];
$already_owned = [];
$failed = [];

foreach ($song_ids as $song_id) {
    $song_id = intval($song_id);
    
    // Check if already owned
    $checkQuery = "SELECT * FROM transaksi WHERE user_id = $user_id AND lagu_id = $song_id";
    $checkResult = mysqli_query($connect, $checkQuery);
    
    if (mysqli_num_rows($checkResult) > 0) {
        $already_owned[] = $song_id;
        continue;
    }
    
    // Insert purchase record
    $insertQuery = "INSERT INTO transaksi (user_id, lagu_id, purchase_date, payment_method) 
                    VALUES ($user_id, $song_id, NOW(), '$payment_method')";
    
    if (mysqli_query($connect, $insertQuery)) {
        $purchased_songs[] = $song_id;
    } else {
        $failed[] = $song_id;
    }
}

// Build redirect message
$message = "";
if (count($purchased_songs) > 0) {
    $message .= count($purchased_songs) . " song(s) purchased successfully! ";
}
if (count($already_owned) > 0) {
    $message .= count($already_owned) . " song(s) already owned. ";
}
if (count($failed) > 0) {
    $message .= count($failed) . " song(s) failed to purchase.";
}

// Clear cart after successful purchase
if (count($purchased_songs) > 0) {
    echo "<script>localStorage.removeItem('cart');</script>";
}

header("Location: library.php?success=" . urlencode($message));
exit();
?>
