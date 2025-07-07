<?php
session_start();
if (!isset($_SESSION['warehouse_logged_in'])) {
  header('Location: warehouse_login.php');
  exit;
}
require 'database.php';

$inv_id = isset($_GET['inv_id']) ? intval($_GET['inv_id']) : 0;
if ($inv_id <= 0) {
  die('Invalid Order ID');
}

// Fetch order info
$stmt = $mysqli->prepare("SELECT * FROM invoice WHERE inv_id = ?");
$stmt->bind_param("i", $inv_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Fetch line items
$items = $mysqli->query("
  SELECT m.name, p.qty, m.price
  FROM invoice_products p 
  JOIN Mountain_Bike m ON p.bike_id = m.id 
  WHERE p.inv_id = $inv_id
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Order #<?= $inv_id ?></title>
  <link rel="stylesheet" href="static/base.css">
  <link rel="stylesheet" href="static/layout.css">
  <link rel="stylesheet" href="static/components.css">
</head>
<body>
<div class="page-wrapper">
  <div class="hero">
    <h1>View Order #<?= $inv_id ?></h1>
    <h2><a href="warehouse_orders.php">Back to Orders</a></h2>
  </div>
  <main>
    <h2>Customer: <?= htmlspecialchars($order['cname']) ?></h2>
    <p>Order Date: <?= $order['inv_date'] ?></p>
    <p>Ship Date: <?= $order['ship_date'] ?></p>
    <p>Status: <?= htmlspecialchars($order['status'] ?? 'Pending') ?></p>
    <h3>Items:</h3>
    <ul>
      <?php while ($item = $items->fetch_assoc()): ?>
        <li><?= $item['qty'] ?> × <?= htmlspecialchars($item['name']) ?> — $<?= number_format($item['price'], 2) ?></li>
      <?php endwhile; ?>
    </ul>
  </main>
</div>
</body>
</html>
