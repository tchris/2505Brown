<?php
session_start();
if (!isset($_SESSION['warehouse_logged_in'])) {
  header('Location: warehouse_login.php');
  exit;
}
require 'database.php';

// Filter logic
$status = isset($_GET['status']) ? $_GET['status'] : 'all';

if ($status === 'all') {
  $stmt = $mysqli->prepare("SELECT * FROM invoice ORDER BY inv_date DESC");
} else {
  $stmt = $mysqli->prepare("SELECT * FROM invoice WHERE status = ? ORDER BY inv_date DESC");
  $stmt->bind_param("s", $status);
}

$stmt->execute();
$orders = $stmt->get_result();
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
  <style>
    /* Basic status badge colors */
    .status-badge {
      display: inline-block;
      padding: 4px 8px;
      border-radius: 5px;
      font-size: 0.85em;
      text-transform: uppercase;
    }
    .status-badge.Pending { background: orange; color: black; }
    .status-badge.Shipped { background: limegreen; color: black; }
    .status-badge.Returned { background: crimson; color: white; }
    .orders-list {
      display: flex;
      flex-direction: column;
      gap: 20px;
      margin-top: 20px;
    }
    .order-card {
      border: 1px solid #0ff;
      padding: 20px;
      background: #000;
      color: #0ff;
    }
    .order-actions a {
      margin-right: 10px;
    }
  </style>
</head>
<body>
<div class="page-wrapper">

  <!-- Hero Wrapper -->
  <div class="hero">
    <img src="img/CP-Bike.png" class="hero__image">
    <h1 class="hero__title">TRON Bike Shop – Warehouse Orders</h1>
    <h2 class="hero__menu">
      <a href="index_warehouse.php">Warehouse Home</a> |
      <a href="warehouse_inventory.php">View Inventory</a> |
      <a href="logout.php">Logout</a>
    </h2>
  </div>

  <main>
    <h2 class="section-heading">Customer Orders</h2>

    <!-- Filter -->
    <form method="get" class="filter-bar">
      <label for="status">Filter by Status:</label>
      <select name="status" id="status">
        <option value="all" <?= $status === 'all' ? 'selected' : '' ?>>All</option>
        <option value="Pending" <?= $status === 'Pending' ? 'selected' : '' ?>>Pending</option>
        <option value="Shipped" <?= $status === 'Shipped' ? 'selected' : '' ?>>Shipped</option>
        <option value="Returned" <?= $status === 'Returned' ? 'selected' : '' ?>>Returned</option>
      </select>
      <button type="submit" class="buy-button">Apply Filter</button>
    </form>

    <!-- Order Cards -->
    <div class="orders-list">
      <?php while ($order = $orders->fetch_assoc()): ?>
        <div class="order-card">
          <h3>Order #<?= $order['inv_id'] ?></h3>
          <p><strong>Customer:</strong> <?= htmlspecialchars($order['cname']) ?></p>
          <p><strong>Order Date:</strong> <?= $order['inv_date'] ?></p>
          <p><strong>Ship Date:</strong> <?= $order['ship_date'] ?></p>
          <p><strong>Status:</strong>
            <span class="status-badge <?= htmlspecialchars($order['status'] ?? 'Pending') ?>">
              <?= htmlspecialchars($order['status'] ?? 'Pending') ?>
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
