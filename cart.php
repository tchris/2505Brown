<!DOCTYPE html>
<html lang="en">
<?php
require 'database.php'; // ✅ Connect once
$hardtails = $mysqli->query("SELECT * FROM Mountain_Bike WHERE Category = 'Hardtail' ORDER BY RAND() LIMIT 1");
$fullsuspension = $mysqli->query("SELECT * FROM Mountain_Bike WHERE Category = 'Full Suspens' ORDER by RAND() LIMIT 1");
$accessories = $mysqli->query("SELECT * FROM Mountain_Bike WHERE Category = 'Accessories' ORDER by RAND() LIMIT 1");
?>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>TRON Cycles – Bike Shop of the Future</title>
    <link rel="stylesheet" href="static/base.css">
    <link rel="stylesheet" href="static/layout.css">
    <link rel="stylesheet" href="static/components.css">
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
</head>

<body>
    <main>
        
<?php
session_start();
require 'database.php';

$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
    exit;
}

$bike_ids = implode(',', array_keys($cart));
$result = $mysqli->query("SELECT * FROM Mountain_Bike WHERE id IN ($bike_ids)");

$total = 0;

echo "<h2>Your Cart</h2>";
echo "<ul>";

while ($bike = $result->fetch_assoc()) {
    $id = $bike['id'];
    $qty = $cart[$id];
    $line_total = $bike['price'] * $qty;
    $total += $line_total;

    echo "<li>{$bike['name']} × $qty – \$" . number_format($line_total, 2) . "</li>";
}
echo "</ul>";
echo "<p><strong>Total: \$" . number_format($total, 2) . "</strong></p>";

echo '<a href="checkout.php">Proceed to Checkout</a>';
    
    </main>
</body>