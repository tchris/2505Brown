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
        <?php include 'templates/hero.php'; ?>

        <!-- Experience -->
      
<main>
    
    <div class="single-product-card">
    <img src="img/<?= htmlspecialchars($product['picture']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">

    <div class="single-product-details">
        <h2 class="product-name"><?= htmlspecialchars($product['name']) ?></h2>
        <div class="single-product-card-description">
            <p><?= nl2br(htmlspecialchars($product['descr'])) ?></p>
        </div>
        <p class="product-price">$<?= number_format($product['price'], 2) ?></p>
        <a href="/2505Chartreuse/add_to_cart.php?id=<?= $product['id'] ?>" class="buy-button">Buy It Now</a>
    </div>
</div>

</main>

        
        <hr class="cyberpunk-hr">

            <!-- Footer -->
            <?php include 'templates/footer.php'; ?>
        
        </div>

    </div> <!-- end .page-wrapper -->

</body>
</html>
