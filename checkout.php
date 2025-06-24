<?php
session_start();
require 'database.php';

$cart = $_SESSION['cart'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout – TRON Cycles</title>
    <link rel="stylesheet" href="static/base.css">
    <link rel="stylesheet" href="static/components.css">
    <link rel="stylesheet" href="static/layout.css">
</head>
<body>

    <div class="hero">
        <img src="img/CP-Bike.png" class="hero__image">
        <h1 class="hero__title"><a href="https://alittlespace.org/2505Chartreuse/">TRON Bike Shop</a></h1>
        <h2 class="hero__menu">
            <a href="/2505Chartreuse/hardtail.php">Hardtail</a> |
            <a href="/2505Chartreuse/fullsuspension.php">Full Suspension</a> |
            <a href="/2505Chartreuse/accessories.php">Accessories Galore</a>
        </h2>
        <h2 class="hero__menu mobile-menu">
            <a href="/2505Chartreuse/hardtail.php">Hardtail</a><br>
            <a href="/2505Chartreuse/fullsuspension.php">Full Suspension</a><br>
            <a href="/2505Chartreuse/accessories.php">Accessories Galore</a>
        </h2>
    </div>

    <main>
        <h1 class="section-heading">Checkout</h1>

        <?php if (empty($cart)): ?>
            <p>Your cart is empty. <a href="index.php">Return to shopping</a></p>
        <?php else: ?>
            <form action="storeinvoice.php" method="post" class="checkout-form">
                <h2>Billing Info</h2>
                <input name="cname" placeholder="Name" required>
                <input name="caddy" placeholder="Address" required>
                <input name="cstate" placeholder="State" required>
                <input name="czip" placeholder="Zip" required>
                <input name="cphone" placeholder="Phone" required>
                <input name="cemail" placeholder="Email" required>

                <h2>Shipping Info</h2>
                <input name="sname" placeholder="Name" required>
                <input name="saddy" placeholder="Address" required>
                <input name="sstate" placeholder="State" required>
                <input name="szip" placeholder="Zip" required>
                <input name="sphone" placeholder="Phone" required>
                <input name="semail" placeholder="Email" required>

                <button type="submit" class="buy-button">Submit Order</button>
            </form>
        <?php endif; ?>
    </main>
</body>
</html>
