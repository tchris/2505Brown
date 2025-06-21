<!DOCTYPE html>
<html lang="en">
<?php
require 'database.php'; // ✅ Connect once
$hardtails = $mysqli->query("SELECT * FROM Mountain_Bike WHERE Category = 'Hardtail' ORDER BY RAND() LIMIT 1");
?>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>TRON Cycles – Bike Shop of the Future</title>
    <link rel="stylesheet" href="static/styles.css">
</head>
<body>   
   
   <!-- Page Content Wrapper -->
    <div class="page-wrapper">

        <!-- Floating Profile -->
        <div class="floating-welcome">
            <img src="img/CP-Bike.png" class="avatar">
            <h1>TRON Bike Shop</h1>
            <p class="title">Hardtail Bikes</p>
        </div>

        <!-- Experience -->
      
<main class="dual-section-container">
    
    <section class="left-panel">
        <a href="/2505Chartreuse/storeallproducts.php" class="panel-link">
            <h2>Hardtail Bikes</h2>
            <?php
            while ($row = $hardtails->fetch_assoc()) {
                echo '<div class="bike-card">';
                echo '<img src="img/' . htmlspecialchars($row['picture']) . '" alt="' . htmlspecialchars($row['name']) . '" style="width:100%; border-radius:10px;">';
                echo '<p><strong>' . htmlspecialchars($row['name']) . '</strong></p>';
                echo '<p>$' . number_format($row['price'], 2) . '</p>';
                echo '</div>';
            }
                ?>
        </a>
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
