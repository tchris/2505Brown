<?php
session_start();
if (!isset($_SESSION['warehouse_logged_in'])) {
  header('Location: warehouse_login.php');
  exit;
}
require 'database.php';

// Fetch orders
$orders = $mysqli->query("SELECT * FROM invoice ORDER BY inv_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>TRON Cycles – Warehouse Orders</title>
  <link rel="stylesheet" href="static/base.css">
  <link rel="stylesheet" href="static/layout.css">
  <link rel="stylesheet" href="static/components.css">
  <link rel="icon" href="img/favicon.ico" type="image/x-icon">
</head>
<body>
<div class="page-wrapper">

  <!-- Hero Wrapper -->
  <div class="hero">
    <img src="img/CP-Bike.png" class="hero__image">
    <h1 class="hero__title">TRON Bike Shop – Warehouse Orders</h1>
    <h2 class="hero__menu">
      <a href="index_warehouse.php">Warehouse Home</a> |
      <a href="warehouse.php">View Inventory</a> |
      <a href="logout.php">Logout</a>
    </h2>
  </div>

  <main>
    <h2 class="section-heading">Customer Orders</h2>

    <!-- Filter Example -->
    <form method="get" class="filter-bar">
      <label for="status">Filter by Status:</label>
      <select name="status" id="status">
        <option value="all">All</option>
        <option value="pending">Pending</option>
        <option value="shipped">Shipped</option>
        <option value="returned">Returned</option>
      </select>
      <button type="submit" class="buy-button">Apply Filter</button>
    </form>

    <!-- Order Cards Layout -->
    <div class="orders-list">
      <?php while ($order = $orders->fetch_assoc()): ?>
        <div class="order-card">
          <h3>Order #<?= $order['inv_id'] ?></h3>
          <p><strong>Customer:</strong> <?= htmlspecialchars($order['cname']) ?></p>
          <p><strong>Order Date:</strong> <?= $order['inv_date'] ?></p>
          <p><strong>Ship Date:</strong> <?= $order['ship_date'] ?></p>
          <p><strong>Status:</strong>
            <span class="status-badge">
              <?= isset($order['status']) ? htmlspecialchars($order['status']) : 'Pending' ?>
            </span>
          </p>
          <div class="order-actions">
            <a href="view_order.php?inv_id=<?= $order['inv_id'] ?>" class="buy-button">View Details</a>
            <a href="mark_shipped.php?inv_id=<?= $order['inv_id'] ?>" class="buy-button">Mark Shipped</a>
            <a href="process_return.php?inv_id=<?= $order['inv_id'] ?>" class="buy-button">Process Return</a>
          </div>
        </div>
      <?php endwhile; ?>
    </div>

  </main>

  <hr class="cyberpunk-hr">

  <!-- Footer -->
  <footer>
    <p>© 2025 Bikes R' Us. All rights reserved.</p>
  </footer>

</div>
</body>
</html>
