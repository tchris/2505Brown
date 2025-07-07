<?php
session_start();
require 'database.php';

// Only managers can access
if (!isset($_SESSION['manager_logged_in'])) {
  header('Location: login.php');
  exit;
}

// Initialize variables
$message = "";

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $price = floatval($_POST['price']);
  $descr = trim($_POST['descr']);
  $category = $_POST['category'];
  $picture = trim($_POST['picture']);

  if ($name && $price > 0 && $category && $picture) {
    $stmt = $mysqli->prepare("
      INSERT INTO Mountain_Bike (category, descr, name, price, picture)
      VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("sssds", $category, $descr, $name, $price, $picture);
    $stmt->execute();
    $stmt->close();

    $message = "<p style='color: green;'>✅ Product added successfully!</p>";
  } else {
    $message = "<p style='color: red;'>⚠️ Please fill out all fields correctly.</p>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Add New Product – Manager</title>
  <link rel="stylesheet" href="static/base.css">
  <link rel="stylesheet" href="static/layout.css">
  <link rel="stylesheet" href="static/components.css">
</head>
<body>
  <div class="page-wrapper">
    <div class="hero">
      <img src="img/CP-Bike.png" class="hero__image">
      <h1 class="hero__title">TRON Bike Shop – Manager Portal</h1>
      <h2 class="hero__menu">
        <a href="manager.php">Manager Home</a> | 
        <a href="index_manager.php">Manage Inventory</a> | 
        <a href="add_product.php">Add Product</a> |
        <a href="logout.php">Logout</a>
      </h2>
    </div>

    <main>
      <h2 class="section-heading">Add New Product</h2>
      <?= $message ?>

      <form method="post">
        <label>Product Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Price ($):</label><br>
        <input type="number" name="price" step="0.01" required><br><br>

        <label>Description:</label><br>
        <textarea name="descr" rows="5" cols="50" required></textarea><br><br>

        <label>Category:</label><br>
        <select name="category" required>
          <option value="">-- Select --</option>
          <option value="Hardtail">Hardtail</option>
          <option value="Full Suspens">Full Suspension</option>
          <option value="Accessories">Accessories</option>
        </select><br><br>

        <label>Picture Filename (e.g., HT-4.png):</label><br>
        <input type="text" name="picture" required><br><br>

        <button type="submit" class="confirm-button">Add Product</button>
      </form>
    </main>

    <hr class="cyberpunk-hr">

            <!-- Footer -->
            <?php include 'templates/footer.php'; ?>
  </div>
</body>
</html>
