<?php
// Secure session setup
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_secure', 1); // Only if your site uses HTTPS

session_start();

// Prevent direct access without login
if (!isset($_SESSION['warehouse_logged_in']) || $_SESSION['warehouse_logged_in'] !== true) {
    header('Location: warehouse_login.php');
    exit;
}

// Prevent caching of the warehouse page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="en">
<?php
require 'database.php'; // ✅ Connect once

$products = $mysqli->query("SELECT * FROM Mountain_Bike ORDER BY category, name");
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

    <div class="page-wrapper">

        <!-- Hero -->
        <div class="hero">
            <!-- Logout Button -->
            <div style="position: absolute; top: 20px; right: 20px;">
                <form action="logout_wh.php" method="post">
                    <button type="submit" style="padding: 8px 12px; background-color: #ff4444; color: white; border: none; border-radius: 4px; cursor: pointer;">
                        Logout
                    </button>
                </form>
            </div>

            <img src="img/CP-Bike.png" class="hero__image">
            <h1 class="hero__title"><a href="https://alittlespace.org/2505Chartreuse/">TRON Bike Shop</a></h1>
            <h2 class="hero__menu"><a href="/2505Chartreuse/hardtail.php">Hardtail</a> | <a href="/2505Chartreuse/fullsuspension.php">Full Suspension</a> | <a href="/2505Chartreuse/accessories.php">Accessories Galore</a></h2>
            <h2 class="hero__menu mobile-menu"><a href="/2505Chartreuse/hardtail.php">Hardtail</a><br><a href="/2505Chartreuse/fullsuspension.php">Full Suspension</a><br><a href="/2505Chartreuse/accessories.php">Accessories Galore</a></h2>
        </div>

        <!-- Product Grid -->
        <main>
            <section class="multiple-product">
                <?php
                if ($products && $products->num_rows > 0) {
                    while ($row = $products->fetch_assoc()) {
                        echo '<div class="product-card">';
                        echo '<a href="/2505Chartreuse/storeoneproduct.php?id=' . $row['id'] . '" class="product-card__link">';
                        echo '<h2>' . htmlspecialchars($row['category']) . '</h2>';
                        echo '<img src="img/' . htmlspecialchars($row['picture']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                        echo '<p><strong>' . htmlspecialchars($row['name']) . '</strong></p>';
                        echo '<p>$' . number_format($row['price'], 2) . '</p>';
                        echo '</a>';
                        echo '<p>Stock: ' . intval($row['stock_qty']) . '</p>';
                        echo '<form action="update_stock.php" method="post" style="display:inline-block; margin-top: 5px;">';
                        echo '<input type="hidden" name="product_id" value="' . $row['id'] . '">';
                        echo '<button type="submit" name="change" value="-1" style="padding:5px 10px; margin-right:5px;">-1</button>';
                        echo '<button type="submit" name="change" value="1" style="padding:5px 10px;">+1</button>';
                        echo '</form>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No bikes found in inventory.</p>';
                }
                ?>
            </section>
        </main>

        <hr class="cyberpunk-hr">

        <!-- Footer -->
        <footer>
            <p>© 2025 Bikes R' Us. All rights reserved.</p>
        </footer>

    </div> <!-- end .page-wrapper -->
</body>
</html>
