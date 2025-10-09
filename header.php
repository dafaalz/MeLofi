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
    <a href="adminPage.php" class="nav-link">Admin Page</a>
  </div>
  <div class="nav-right">
    <button class="nav-icon">🔍</button>
    <button class="nav-icon">💬</button>
    <button class="nav-icon">🔔</button>
    <button class="nav-icon">⛶</button>
    <div class="user-menu">
      <img src="../assets/img/user2-160x160.jpg" alt="User" class="user-avatar" />
      <span>Alexander Pierce</span>
    </div>
  </div>
 </nav>
 <script>
 document.addEventListener("DOMContentLoaded", () => {
   const toggleButton = document.querySelector(".sidebar-toggle");
   const sidebar = document.querySelector(".sidebar");
   const mainContent = document.querySelector("main.app-content");

   toggleButton.addEventListener("click", () => {
     sidebar.classList.toggle("collapsed");
     mainContent.classList.toggle("collapsed");
   });
 });
 </script>