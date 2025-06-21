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
    <link rel="stylesheet" href="static/styles.css">
</head>
<body>   
   
    <div class="page-wrapper">

        <!-- Floating Profile -->
        <div class="floating-welcome">
            <img src="img/CP-Bike.png" class="avatar">
            <h1><a href="https://alittlespace.org/2505Chartreuse/">TRON Bike Shop</a></h1>
            <p class="title"><a href="/2505Chartreuse/hardtail.php">Hardtail</a> |<a href="/2505Chartreuse/fullsuspension.php"> Full Suspension</a> | <a href="/2505Chartreuse/accessories.php">Accessories Galore</a></p>
        </div>
        <!-- Experience -->
      
<main>
    
    <section class="single-panel">
        <h2><a href="/2505Chartreuse/hardtail.php" class="panel-link">Hardtail Bikes</a></h2>
        <?php
        while ($row = $hardtails->fetch_assoc()) {
            echo '<div class="single-bike-card">';

                echo '<div class="bike-info">';
                    echo '<img src="img/' . htmlspecialchars($row['picture']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                    echo '<p><strong>' . htmlspecialchars($row['name']) . '</strong></p>';
                    echo '<p>$' . number_format($row['price'], 2) . '</p>';
                echo '</div>';

                echo '<div class="single-bike-card-description">';
                    $description = $row['descr'] ?? 'No description available.';
                    echo '<p>' . htmlspecialchars($description) . '</p>';
                echo '</div>';

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
