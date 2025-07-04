<?php
session_start();
if (!isset($_SESSION['manager_logged_in'])) {
  header('Location: login.php');
  exit;
}
require 'database.php'; // ✅ Connect once
$hardtails = $mysqli->query("SELECT * FROM Mountain_Bike WHERE Category = 'Hardtail'");
?>

<!DOCTYPE html>
<html lang="en">
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
      <h2 class="hero__menu"><a href="/2505Chartreuse/hardtail.php">Hardtail</a> | <a href="/2505Chartreuse/fullsuspension.php">Full Suspension</a> | <a href="/2505Chartreuse/accessories.php">Accessories Galore</a></h2>
      <h2 class="hero__menu mobile-menu"><a href="/2505Chartreuse/hardtail.php">Hardtail</a><br><a href="/2505Chartreuse/fullsuspension.php">Full Suspension</a><br><a href="/2505Chartreuse/accessories.php">Accessories Galore</a></h2>
    </div>

    <main>
      <h2 class="section-heading"><a href="/2505Chartreuse/hardtail_manager.php">Manage Hardtail Bikes</a></h2>

      <?php
      while ($row = $hardtails->fetch_assoc()) {
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
    <footer>
      <p>© 2025 Bikes R\' Us. All rights reserved.</p>
    </footer>

  </div> <!-- end .page-wrapper -->
</body>
</html>
