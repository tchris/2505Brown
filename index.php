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
   
   <!-- Page Content Wrapper -->
    <div class="page-wrapper">

        <!-- Hero Wrapper -->
        <?php include 'templates/hero.php'; ?>

        <!-- Experience -->
      
<main>
    
    <section class="multiple-product">     
        <?php
        while ($row = $hardtails->fetch_assoc()) {
            echo '<div class="product-card">';
            echo '<a href="/2505Chartreuse/storeoneproduct.php?id=' . $row['id'] . '" class="product-card__link">';
            echo     '<h2>Hardtail Bikes</h2>';
            echo     '<img src="img/' . htmlspecialchars($row['picture']) . '" alt="' . htmlspecialchars($row['name']) . '">';
            echo     '<p><strong>' . htmlspecialchars($row['name']) . '</strong></p>';
            echo     '<p>$' . number_format($row['price'], 2) . '</p>';
            echo '<a href="/2505Chartreuse/add_to_cart.php?id=' . $row['id'] . '" class="buy-button">Buy It Now</a>';
            echo '</a>';
            echo '</div>';
        }
        ?>

        <?php
        while ($row = $fullsuspension->fetch_assoc()) {
            echo '<div class="product-card">';
            echo '<a href="/2505Chartreuse/storeoneproduct.php?id=' . $row['id'] . '" class="product-card__link">';
            echo     '<h2>Full Suspension Bikes</h2>';
            echo     '<img src="img/' . htmlspecialchars($row['picture']) . '" alt="' . htmlspecialchars($row['name']) . '">';
            echo     '<p><strong>' . htmlspecialchars($row['name']) . '</strong></p>';
            echo     '<p>$' . number_format($row['price'], 2) . '</p>';
            echo '<a href="/2505Chartreuse/add_to_cart.php?id=' . $row['id'] . '" class="buy-button">Buy It Now</a>';
            echo '</a>';
            echo '</div>';
        }
        ?>
        
        <?php
        while ($row = $accessories->fetch_assoc()) {
            echo '<div class="product-card">';
            echo '<a href="/2505Chartreuse/storeoneproduct.php?id=' . $row['id'] . '" class="product-card__link">';
            echo     '<h2>Bike Accessories</h2>';
            echo     '<img src="img/' . htmlspecialchars($row['picture']) . '" alt="' . htmlspecialchars($row['name']) . '">';
            echo     '<p><strong>' . htmlspecialchars($row['name']) . '</strong></p>';
            echo     '<p>$' . number_format($row['price'], 2) . '</p>';
            echo '<a href="/2505Chartreuse/add_to_cart.php?id=' . $row['id'] . '" class="buy-button">Buy It Now</a>';
            echo '</a>';
            echo '</div>';
        }
        ?>
    </section>
    
</main>

        
        <hr class="cyberpunk-hr">


        <!-- Footer -->
        
            <footer>
                <p>© 2025 Bikes R' Us. All rights reserved.</p>
            </footer>
        
        </div>

    </div> <!-- end .page-wrapper -->

</body>
</html>
