<?php 
  $username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="wrapper">
<nav class="app-header">
  <div class="nav-left">
    <button class="sidebar-toggle">☰</button>
    <a href="index.php" class="nav-link">Home</a>
    <?php if($_SESSION['level_access'] == 'admin') {
      echo "<a href=\"adminPage.php\" class=\"nav-link\">Admin Page</a>";
      };?>
    <a href="library.php" class="nav-link">Library</a>
    <a href="store.php" class="nav-link">Store</a>
  </div>
  <div class="nav-right">
    <button class="nav-icon">🔍</button>
    <!--
    <button class="nav-icon">💬</button>
    <button class="nav-icon">🔔</button>
    <button class="nav-icon">⛶</button>
    -->
    <div class="user-menu">
        <span class="username"><?php echo $username ?></span>
        <div class="user-dropdown">
            <a href="logout.php">Log Out</a>
        </div>
    </div>
</style>
<style>
.user-menu {
    position: relative;
    display: inline-block;
    cursor: pointer;
}

.user-menu .user-dropdown {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    background-color: #fff;
    min-width: 120px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    border-radius: 6px;
    z-index: 100;
}

.user-menu .user-dropdown a {
    display: block;
    padding: 10px 15px;
    color: #111;
    text-decoration: none;
}

.user-menu .user-dropdown a:hover {
    background-color: #f0f0f0;
}

.user-menu:hover .user-dropdown {
    display: block;
}
</style>
  </div>
 </nav>