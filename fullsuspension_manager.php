<?php
session_start();
if (!isset($_SESSION['manager_logged_in'])) {
  header('Location: login.php');
  exit;
}
require 'database.php';
$fullsuspension = $mysqli->query("SELECT * FROM Mountain_Bike WHERE Category = 'Full Suspens'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>TRON Cycles – Manage Full Suspension Bikes</title>
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
  <h1 class="hero__title">TRON Bike Shop – Manager Portal</h1>
  <h2 class="hero__menu">
    <a href="manager.php">Manager Home</a> | 
    <a href="index_manager.php">Manage Inventory</a> | 
    <a href="hardtail_manager.php">Hardtail</a> | 
    <a href="fullsuspension_manager.php">Full Suspension</a> | 
    <a href="accessories_manager.php">Accessories</a> | 
    <a href="logout.php">Logout</a>
  </h2>
</div>


    <main>
      <h2 class="section-heading">Manage Full Suspension Bikes</h2>

      <?php
      while ($row = $fullsuspension->fetch_assoc()) {
        echo '<section class="single-product">';

        echo '<div class="product-card">';
        echo '<a href="/2505Chartreuse/storeoneproduct.php?id=' . $row['id'] . '" class="product-card__link">';
        echo '<img src="img/' . htmlspecialchars($row['picture']) . '" alt="' . htmlspecialchars($row['name']) . '">';
        echo '<p><strong>' . htmlspecialchars($row['name']) . '</strong></p>';
        echo '<p>$' . number_format($row['price'], 2) . '</p>';
        echo '</a>';
        echo '<a href="/2505Chartreuse/edit_product.php?id=' . $row['id'] . '" class="edit-button">Edit Product</a>';
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
</body>
</html>
