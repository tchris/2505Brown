<?php
session_start();

// ✅ Warehouse login guard
if (!isset($_SESSION['warehouse_logged_in'])) {
  header('Location: login_warehouse.php');
  exit;
}

require 'database.php'; // Good to include if you need it later (optional here)
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>TRON Cycles – Warehouse Portal</title>
  <link rel="stylesheet" href="static/base.css">
  <link rel="stylesheet" href="static/layout.css">
  <link rel="stylesheet" href="static/components.css">
  <link rel="icon" href="img/favicon.ico" type="image/x-icon">
</head>
<body>
  <!-- Page Content Wrapper -->
  <div class="page-wrapper">

    <!-- Hero Wrapper -->
    <div class="hero">
      <img src="img/CP-Bike.png" class="hero__image">
      <h1 class="hero__title">TRON Bike Shop – Warehouse Portal</h1>
      <h2 class="hero__menu">
        <a href="index_warehouse.php">Warehouse Home</a> |
        <a href="logout_warehouse.php">Logout</a>
      </h2>
    </div>

    <main>
      <section class="multiple-product">
        <div class="product-card">
          <a href="warehouse_inventory.php" class="product-card__link">
            <h2>View Inventory / Stock</h2>
            <p>See all bikes, stock counts, and available accessories.</p>
          </a>
        </div>

        <div class="product-card">
          <a href="warehouse_orders.php" class="product-card__link">
            <h2>View Orders</h2>
            <p>Check current customer orders, shipping status, and returns.</p>
          </a>
        </div>
      </section>
    </main>

    <hr class="cyberpunk-hr">

    <!-- Footer -->
    <?php include 'templates/footer.php'; ?>
  </div>
</body>
</html>
