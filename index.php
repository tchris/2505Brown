<!DOCTYPE html>
<html lang="en">
<?php
require 'database.php'; // ✅ Connect once
$hardtails = $mysqli->query("SELECT * FROM Mountain_Bike WHERE Category = 'Hardtail'");
$fatbikes = $mysqli->query("SELECT * FROM Mountain_Bike WHERE Category = 'Fat_bike'");
?>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>TRON Cycles – Bike Shop of the Future</title>
    <link rel="stylesheet" href="static/styles.css">
    <?php require 'database.php';?>
</head>
<body>   
   
   <!-- Page Content Wrapper -->
    <div class="page-wrapper">

        <!-- Floating Profile -->
        <div class="floating-welcome">
            <img src="img/CP-Bike.png" class="avatar">
            <h1>TRON Bike Shop</h1>
            <p class="title">Hardtail | Softtail | Accessories Galore</p>
        </div>

        <!-- Experience -->
      
<main class="dual-section-container">
    
    <section class="left-panel">
        <a href="/2505Chartreuse/storeallproducts.php">
            <h2>Hardtail Bikes</h2>
        </a>
    </section>

    <section class="right-panel">
        <a href="/2505Chartreuse/storeallproducts.php">
            <h2>Fat Bikes</h2>
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
