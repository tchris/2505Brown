<?php
session_start();
$bike_id = $_GET['id'] ?? null;

if ($bike_id && is_numeric($bike_id)) {
    // Initialize the cart if it doesn't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Add or increment bike ID in cart
    if (isset($_SESSION['cart'][$bike_id])) {
        $_SESSION['cart'][$bike_id]++;
    } else {
        $_SESSION['cart'][$bike_id] = 1;
    }

    header("Location: cart.php"); // Redirect to cart view
    exit;
} else {
    echo "Invalid product.";
}
