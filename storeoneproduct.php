<!DOCTYPE html>
<html lang="en">
<?php
require 'database.php'; // ✅ still connects once

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']); // safely cast to integer

    // Fetch that one product by ID
    $stmt = $mysqli->prepare("SELECT * FROM Mountain_Bike WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        echo "<p>Product not found.</p>";
        
        exit;
    }
} else {
    echo "<p>No product specified.</p>";
    exit;
}
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
            <h2 class="hero__menu mobile-menu"><a href="/2505Chartreuse/hardtail.php">Hardtail</a><br><a href="/2505Chartreuse/fullsuspension.php">Full Suspension</a><br><a href="/2505Chartreuse/accessories.php">Accessories Galore</a></h2>
        </div>

        <!-- Experience -->
      
<main>
    
   <div class="single-product-card">
    <div class="bike-info">
        <img src="img/<?= htmlspecialchars($product['picture']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
        <h2><?= htmlspecialchars($product['name']) ?></h2>
        <p>$<?= number_format($product['price'], 2) ?></p>
    </div>
    <div class="single-bike-card-description">
        <p><?= nl2br(htmlspecialchars($product['descr'])) ?></p>
    </div>
</div>

    
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
