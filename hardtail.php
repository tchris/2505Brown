<!DOCTYPE html>
<html lang="en">
<?php
require 'database.php'; // ✅ Connect once
$hardtails = $mysqli->query("SELECT * FROM Mountain_Bike WHERE Category = 'Hardtail'");
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
      
<main>
             

        <h2 class="section-heading"><a href="/2505Chartreuse/hardtail.php">Hardtail Bikes</a></h2>
        <?php
        while ($row = $hardtails->fetch_assoc()) {
                echo '<section class="single-product">';

                echo '<div class="product-card">';
                    echo '<a href="/2505Chartreuse/storeoneproduct.php?id=' . $row['id'] . '" class="product-card__link">';
                    echo '<img src="img/' . htmlspecialchars($row['picture']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                    echo '<p><strong>' . htmlspecialchars($row['name']) . '</strong></p>';
                    echo '<p>$' . number_format($row['price'], 2) . '</p>';
                    echo '<a href="/2505Chartreuse/add_to_cart.php?id=' . $row['id'] . '" class="buy-button">Buy It Now</a>';
                    echo '</a>';
                echo '</div>';

                echo '<div class="single-product-card-description">';
                    $description = $row['descr'] ?? 'No description available.';
                    echo '<p>' . htmlspecialchars($description) . '</p>';
                echo '</div>';

            echo '</section>';
        }
        ?>

    
</main>

        
        <hr class="cyberpunk-hr">

            <!-- Footer -->
            <?php include 'templates/footer.php'; ?>
        
        </div>

    </div> <!-- end .page-wrapper -->

</body>
</html>
