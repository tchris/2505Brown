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

        <!-- Hero Wrapper -->
    <div class="hero">
        <img src="img/CP-Bike.png" class="hero__image">
      <h1 class="hero__title">TRON Bike Shop – Warehouse Portal</h1>
      <h2 class="hero__menu">
        <a href="index_warehouse.php">Warehouse Home</a> |
        <a href="logout_warehouse.php">Logout</a>
      </h2>
    </div>

        <!-- Product Grid -->
        <main>
            <section class="multiple-product">
                <?php
                if ($products && $products->num_rows > 0) {
                    while ($row = $products->fetch_assoc()) {
                       echo '<div class="product-card">';
                       echo '<h2>' . htmlspecialchars($row['category']) . '</h2>';
                       echo '<img src="img/' . htmlspecialchars($row['picture']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                       echo '<p><strong>' . htmlspecialchars($row['name']) . '</strong></p>';
                       echo '<p>$' . number_format($row['price'], 2) . '</p>';
                       echo '<p>Stock: ' . intval($row['stock_qty']) . '</p>';
                       echo '<form action="update_stock.php" method="post" style="margin-top: 5px;">';
                       echo '<input type="hidden" name="product_id" value="' . $row['id'] . '">';
                       echo '<button type="submit" name="change" value="-1">-1</button>';
                       echo '<button type="submit" name="change" value="1">+1</button>';
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
            <?php include 'templates/footer.php'; ?>

    </div> <!-- end .page-wrapper -->
</body>
</html>
