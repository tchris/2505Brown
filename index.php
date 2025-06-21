<!DOCTYPE html>
<html lang="en">
<?php
require 'database.php'; // ✅ Connect once
$hardtails = $mysqli->query("SELECT * FROM Mountain_Bike WHERE Category = 'Hardtail' ORDER BY RAND() LIMIT 1");
$fatbikes = $mysqli->query("SELECT * FROM Mountain_Bike WHERE Category = 'Full Suspens' ORDER by RAND() LIMIT 1");
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
        <div class="hero">
            <img src="img/CP-Bike.png" class="hero__image">
            <h1 class="hero__title"><a href="https://alittlespace.org/2505Chartreuse/">TRON Bike Shop</a></h1>
            <h2 class="hero__menu"><a href="/2505Chartreuse/hardtail.php">Hardtail</a> |<a href="/2505Chartreuse/fullsuspension.php"> Full Suspension</a> | <a href="/2505Chartreuse/accessories.php">Accessories Galore</a></h2>
        </div>

        <!-- Experience -->
      
<main>
    
    <section class="multiple-product">
        <div class="product-card">
            <a href="/hardtail.php">
            <h2><a href="/2505Chartreuse/hardtail.php">Hardtail Bikes</a></h2>
            <?php
            while ($row = $hardtails->fetch_assoc()) {
                echo '<div class="product-card">';
                echo '<img src="img/' . htmlspecialchars($row['picture']) . '" alt="' . htmlspecialchars($row['name']) . '" style="width:100%; border-radius:10px;">';
                echo '<p><strong>' . htmlspecialchars($row['name']) . '</strong></p>';
                echo '<p>$' . number_format($row['price'], 2) . '</p>';
                echo '</div>';
            }
                ?>
            </a>
        </div>
        
        <div class="product-card">
            <h2><a href="/2505Chartreuse/fullsuspension.php">Full Suspension Bikes</a></h2>
            <?php
            while ($row = $fatbikes->fetch_assoc()) {
                echo '<img src="img/' . htmlspecialchars($row['picture']) . '" alt="' . htmlspecialchars($row['name']) . '" style="width:100%; border-radius:10px;">';
                echo '<p><strong>' . htmlspecialchars($row['name']) . '</strong></p>';
                echo '<p>$' . number_format($row['price'], 2) . '</p>';
                echo '</div>';
            }
                ?>
            </a>
        </div>

        <div class="product-card">
            <h2><a href="/2505Chartreuse/accessories.php">Accessories</a></h2>
            <?php
            while ($row = $accessories->fetch_assoc()) {
                echo '<div class="bike-card">';
                echo '<img src="img/' . htmlspecialchars($row['picture']) . '" alt="' . htmlspecialchars($row['name']) . '" style="width:100%; border-radius:10px;">';
                echo '<p><strong>' . htmlspecialchars($row['name']) . '</strong></p>';
                echo '<p>$' . number_format($row['price'], 2) . '</p>';
                echo '</div>';
            }
                ?>
            </a>
        </div>
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
