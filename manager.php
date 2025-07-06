<?php
session_start();
if (!isset($_SESSION['manager_logged_in'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>TRON Cycles – Manager Dashboard</title>
  <link rel="stylesheet" href="static/base.css">
  <link rel="stylesheet" href="static/layout.css">
  <link rel="stylesheet" href="static/components.css">
  <link rel="icon" href="img/favicon.ico" type="image/x-icon">
</head>
<body>
  <div class="page-wrapper">

    <!-- Hero -->
    <div class="hero">
      <img src="img/CP-Bike.png" class="hero__image">
      <h1 class="hero__title">TRON Bike Shop – Manager Portal</h1>
      <h2 class="hero__menu">
        <a href="index_manager.php">Inventory Hub</a> |  
        <a href="logout.php">Logout</a>
      </h2>
    </div>

    <!-- Manager Controls -->
    <main>
      <section class="manager-controls">
        <h2 class="section-title">Management Actions</h2>
        
        <div class="manager-card">
          <a href="index_manager.php" class="buy-button">Edit Inventory</a>
        </div>

        <div class="manager-card">
          <a href="view_reports.php" class="buy-button">View Sales Reports</a>
        </div>

        <div class="manager-card">
          <a href="manage_discounts.php" class="buy-button">Manage Discounts</a>
        </div>

        <div class="manager-card">
          <a href="manage_users.php" class="buy-button">Manage Users</a>
        </div>

      </section>
    </main>

    <hr class="cyberpunk-hr">

    <!-- Footer -->
    <footer>
      <p>© 2025 Bikes R' Us. All rights reserved.</p>
    </footer>

  </div>
</body>
</html>
