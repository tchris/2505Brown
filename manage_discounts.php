<?php
session_start();
if (!isset($_SESSION['manager_logged_in'])) {
  header('Location: login.php');
  exit;
}

require 'database.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $code = $_POST['code'];
  $discount_pct = $_POST['discount_pct'];
  $start_date = $_POST['start_date'];
  $end_date = $_POST['end_date'];
  $applies_to = $_POST['applies_to'];
  $applies_value = $_POST['applies_value'];

  $stmt = $mysqli->prepare("INSERT INTO promotions (code, discount_pct, start_date, end_date, applies_to, applies_value) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sdssss", $code, $discount_pct, $start_date, $end_date, $applies_to, $applies_value);
  $stmt->execute();
}

// Get existing promotions
$promos = $mysqli->query("SELECT * FROM promotions ORDER BY start_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>TRON Cycles – Manage Discounts</title>
  <link rel="stylesheet" href="static/base.css">
  <link rel="stylesheet" href="static/layout.css">
  <link rel="stylesheet" href="static/components.css">
</head>
<body>
  <div class="page-wrapper">

    <!-- Hero with manager nav -->
    <div class="hero">
      <img src="img/CP-Bike.png" class="hero__image">
      <h1 class="hero__title">TRON Bike Shop – Manager Portal</h1>
      <h2 class="hero__menu">
        <a href="manager.php">Manager Home</a> | 
        <a href="index_manager.php">Manage Inventory</a> | 
        <a href="manage_discounts.php">Manage Discounts</a> | 
        <a href="logout.php">Logout</a>
      </h2>
    </div>

    <main>
      <h2 class="section-title">Create New Promotion</h2>
      <form method="POST">
        <label>Promo Code:</label><br>
        <input type="text" name="code" required><br><br>

        <label>Discount Percentage:</label><br>
        <input type="number" name="discount_pct" step="0.01" required><br><br>

        <label>Start Date:</label><br>
        <input type="date" name="start_date" required><br><br>

        <label>End Date:</label><br>
        <input type="date" name="end_date" required><br><br>

        <label>Applies To (bike/accessory/category):</label><br>
        <input type="text" name="applies_to" required><br><br>

        <label>Value (e.g., bike ID, SKU, or category name):</label><br>
        <input type="text" name="applies_value" required><br><br>

        <button type="submit" class="buy-button">Create Promotion</button>
      </form>

      <hr class="cyberpunk-hr">

      <h2 class="section-title">Current Promotions</h2>
      <?php
      if ($promos->num_rows > 0) {
        while ($row = $promos->fetch_assoc()) {
          echo '<div class="promo-card">';
          echo '<p><strong>Code:</strong> ' . htmlspecialchars($row['code']) . '</p>';
          echo '<p><strong>Discount:</strong> ' . htmlspecialchars($row['discount_pct']) . '%</p>';
          echo '<p><strong>Start:</strong> ' . htmlspecialchars($row['start_date']) . '</p>';
          echo '<p><strong>End:</strong> ' . htmlspecialchars($row['end_date']) . '</p>';
          echo '<p><strong>Applies To:</strong> ' . htmlspecialchars($row['applies_to']) . ' - ' . htmlspecialchars($row['applies_value']) . '</p>';
          echo '</div><hr>';
        }
      } else {
        echo '<p>No promotions found.</p>';
      }
      ?>
    </main>

    <hr class="cyberpunk-hr">

            <!-- Footer -->
            <?php include 'templates/footer.php'; ?>
  </div>
</body>
</html>
