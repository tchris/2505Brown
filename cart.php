<?php
session_start();
require 'database.php';

$cart = $_SESSION['cart'] ?? [];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart – TRON Cycles</title>
    <link rel="stylesheet" href="static/base.css">
    <link rel="stylesheet" href="static/components.css">
    <link rel="stylesheet" href="static/layout.css">
</head>
<body>

        <div class="hero">
            <img src="img/CP-Bike.png" class="hero__image">
            <h1 class="hero__title"><a href="https://alittlespace.org/2505Chartreuse/">TRON Bike Shop</a></h1>
            <h2 class="hero__menu"><a href="/2505Chartreuse/hardtail.php">Hardtail</a> |<a href="/2505Chartreuse/fullsuspension.php"> Full Suspension</a> | <a href="/2505Chartreuse/accessories.php">Accessories Galore</a></h2>
            <h2 class="hero__menu mobile-menu"><a href="/2505Chartreuse/hardtail.php">Hardtail</a><br><a href="/2505Chartreuse/fullsuspension.php">Full Suspension</a><br><a href="/2505Chartreuse/accessories.php">Accessories Galore</a></h2>
        </div>
        
    <main>
        <h1 class="section-heading">Your Cart</h1>

        <?php if (empty($cart)): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <?php
            $bike_ids = implode(',', array_map('intval', array_keys($cart)));
            $result = $mysqli->query("SELECT * FROM Mountain_Bike WHERE id IN ($bike_ids)");

            if (!$result || $result->num_rows === 0): ?>
                <p>There was a problem retrieving your cart items.</p>
            <?php else: ?>
                <ul class="cart-list">
                    <?php
                    $total = 0;
                    while ($bike = $result->fetch_assoc()):
                        $id = $bike['id'];
                        $qty = $cart[$id];
                        $line_total = $bike['price'] * $qty;
                        $total += $line_total;
                    ?>
                        <li class="cart-item">
                            <span class="item-name"><?= htmlspecialchars($bike['name']) ?></span>
                            <form action="update_cart.php" method="post" class="cart-controls">
                                <input type="hidden" name="id" value="<?= $id ?>">

                                <!-- Decrease -->
                                <button type="submit" name="action" value="decrease">−</button>

                                <!-- Quantity Display -->
                                <span class="qty-display"><?= $qty ?></span>

                                <!-- Increase -->
                                <button type="submit" name="action" value="increase">+</button>

                                <!-- Remove -->
                                <button type="submit" name="action" value="remove">Remove</button>
                            </form>

                            <span class="line-total">$<?= number_format($line_total, 2) ?></span>
                        </li>


                    <?php endwhile; ?>
                </ul>

                <p class="cart-total"><strong>Total: $<?= number_format($total, 2) ?></strong></p>

                <a href="checkout.php" class="buy-button">Proceed to Checkout</a>
            <?php endif; ?>
        <?php endif; ?>
    </main>
</body>
</html>
