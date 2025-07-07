<?php
session_start();
require 'database.php';

if (!isset($_SESSION['warehouse_logged_in']) || $_SESSION['warehouse_logged_in'] !== true) {
    header('Location: warehouse_login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);
    $change = intval($_POST['change']); // +1 or -1

    $stmt = $mysqli->prepare("UPDATE Mountain_Bike SET stock_qty = GREATEST(stock_qty + ?, 0) WHERE id = ?");
    $stmt->bind_param('ii', $change, $product_id);
    $stmt->execute();
    $stmt->close();
}

header("Location: warehouse.php");
exit;
