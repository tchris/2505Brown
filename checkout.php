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
            <form action="checkout.php" method="post" class="checkout-form">

    <h2>Billing Info</h2>
    <div class="form-group">
        <label for="cname">Name</label>
        <input id="cname" name="cname" required>
    </div>
    <div class="form-group">
            <label for="caddy">Address</label>
            <input id="caddy" name="caddy" required>
        </div>
        <div class="form-group">
            <label for="cstate">State</label>
            <input id="cstate" name="cstate" required>
        </div>
        <div class="form-group">
            <label for="czip">Zip</label>
            <input id="czip" name="czip" required>
        </div>
        <div class="form-group">
            <label for="cphone">Phone</label>
            <input id="cphone" name="cphone" required>
        </div>
        <div class="form-group">
            <label for="cemail">Email</label>
            <input id="cemail" name="cemail" required>
        </div>

        <h2>Shipping Info</h2>
        <div class="form-group">
            <label for="sname">Name</label>
            <input id="sname" name="sname" required>
        </div>
        <div class="form-group">
            <label for="saddy">Address</label>
            <input id="saddy" name="saddy" required>
        </div>
        <div class="form-group">
            <label for="sstate">State</label>
            <input id="sstate" name="sstate" required>
        </div>
        <div class="form-group">
            <label for="szip">Zip</label>
            <input id="szip" name="szip" required>
        </div>
        <div class="form-group">
            <label for="sphone">Phone</label>
            <input id="sphone" name="sphone" required>
        </div>
        <div class="form-group">
            <label for="semail">Email</label>
            <input id="semail" name="semail" required>
        </div>

        <button type="submit" class="buy-button">Preview Invoice</button>
    </form>

        <?php endif; ?>
    </main>
</body>
</html>
