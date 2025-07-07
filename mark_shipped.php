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

// Mark order as shipped
$stmt = $mysqli->prepare("UPDATE invoice SET status = 'Shipped', ship_date = CURDATE() WHERE inv_id = ?");
$stmt->bind_param("i", $inv_id);
$stmt->execute();
$stmt->close();

// Redirect back
header("Location: warehouse_orders.php");
exit;
?>
