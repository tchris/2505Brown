<?php
session_start();
if (!isset($_SESSION['manager_logged_in'])) {
  header('Location: login.php');
  exit;
}

require 'database.php'; // ✅ Connect once
$hardtails = $mysqli->query("SELECT * FROM Mountain_Bike WHERE Category = 'Hardtail' ORDER BY RAND() LIMIT 1");
$fullsuspension = $mysqli->query("SELECT * FROM Mountain_Bike WHERE Category = 'Full Suspens' ORDER BY RAND() LIMIT 1");
$accessories = $mysqli->query("SELECT * FROM Mountain_Bike WHERE Category = 'Accessories' ORDER BY RAND() LIMIT 1");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>TRON Cycles – Manager Portal</title>
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
      <h1 class="hero__title"><a href="/2505Chartreuse/index_manager.php">TRON Bike Shop – Manager Portal</a></h1>
      <h2 class="hero__menu">
        <a href="add_product.php">Add Product</a> |
        <a href="/2505Chartreuse/hardtail_manager.php">Manage Hardtails</a> |
        <a href="/2505Chartreuse/fullsuspension_manager.php">Manage Full Suspension</a> |
        <a href="/2505Chartreuse/accessories_manager.php">Manage Accessories</a> |
        <a href="/2505Chartreuse/logout.php">Logout</a>
      </h2>
    </div>

    <main>
      <section class="multiple-product">

        <?php
        while ($row = $hardtails->fetch_assoc()) {
          echo '<div class="product-card">';
          echo '<a href="/2505Chartreuse/hardtail_manager.php" class="product-card__link">';
          echo '<h2>Manage Hardtail Bikes</h2>';
          echo '<img src="img/' . htmlspecialchars($row['picture']) . '" alt="' . htmlspecialchars($row['name']) . '">';
          echo '<p><strong>' . htmlspecialchars($row['name']) . '</strong></p>';
          echo '<p>$' . number_format($row['price'], 2) . '</p>';
          echo '</a>';
          echo '</div>';
        }
        ?>

        <?php
        while ($row = $fullsuspension->fetch_assoc()) {
          echo '<div class="product-card">';
          echo '<a href="/2505Chartreuse/fullsuspension_manager.php" class="product-card__link">';
          echo '<h2>Manage Full Suspension Bikes</h2>';
          echo '<img src="img/' . htmlspecialchars($row['picture']) . '" alt="' . htmlspecialchars($row['name']) . '">';
          echo '<p><strong>' . htmlspecialchars($row['name']) . '</strong></p>';
          echo '<p>$' . number_format($row['price'], 2) . '</p>';
          echo '</a>';
          echo '</div>';
        }
        ?>

        <?php
        while ($row = $accessories->fetch_assoc()) {
          echo '<div class="product-card">';
          echo '<a href="/2505Chartreuse/accessories_manager.php" class="product-card__link">';
          echo '<h2>Manage Accessories</h2>';
          echo '<img src="img/' . htmlspecialchars($row['picture']) . '" alt="' . htmlspecialchars($row['name']) . '">';
          echo '<p><strong>' . htmlspecialchars($row['name']) . '</strong></p>';
          echo '<p>$' . number_format($row['price'], 2) . '</p>';
          echo '</a>';
          echo '</div>';
        }
        ?>

      </section>
    </main>

    <hr class="cyberpunk-hr">

            <!-- Footer -->
            <?php include 'templates/footer.php'; ?>

  </div>
</body>
</html>
