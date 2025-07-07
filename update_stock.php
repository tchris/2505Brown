<?php
session_start();
if (!isset($_SESSION['warehouse_logged_in']) || $_SESSION['warehouse_logged_in'] !== true) {
  header('Location: warehouse_login.php');
  exit;
}

require 'database.php';

$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$change = isset($_POST['change']) ? intval($_POST['change']) : 0;

if ($product_id <= 0 || ($change !== -1 && $change !== 1)) {
  die("Invalid request");
}

// Update the stock_qty safely
$stmt = $mysqli->prepare("
  UPDATE Mountain_Bike
  SET stock_qty = GREATEST(stock_qty + ?, 0)
  WHERE id = ?
");
$stmt->bind_param("ii", $change, $product_id);
$stmt->execute();
$stmt->close();

// Redirect back to warehouse inventory page
header('Location: warehouse_inventory.php');
exit;
?>
