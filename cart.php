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