<?php
session_start();
require 'database.php';

// Only managers can access
if (!isset($_SESSION['manager_logged_in'])) {
  header('Location: login.php');
  exit;
}

// Initialize message
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $price = floatval($_POST['price']);
  $descr = trim($_POST['descr']);
  $category = $_POST['category'];

  // Handle image upload
  if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'img/';
    $uploadedName = basename($_FILES['picture']['name']);
    $targetPath = $uploadDir . $uploadedName;

    $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif', 'image/webp'];
    if (in_array($_FILES['picture']['type'], $allowedTypes)) {
      if (move_uploaded_file($_FILES['picture']['tmp_name'], $targetPath)) {
        // Insert into database
        $stmt = $mysqli->prepare("
          INSERT INTO Mountain_Bike (category, descr, name, price, picture)
          VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("sssds", $category, $descr, $name, $price, $uploadedName);
        $stmt->execute();
        $stmt->close();

        $message = "<p style='color: green;'>✅ Product added successfully with image!</p>";
      } else {
        $message = "<p style='color: red;'>⚠️ Failed to move uploaded image.</p>";
      }
    } else {
      $message = "<p style='color: red;'>⚠️ Invalid image format.</p>";
    }
  } else {
    $message = "<p style='color: red;'>⚠️ Image is required.</p>";
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

      <form method="post" enctype="multipart/form-data">
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

        <label>Upload Product Image:</label><br>
        <input type="file" name="picture" accept="image/*" onchange="previewImage(this)" required><br><br>

        <img id="image-preview" src="#" alt="Image Preview" style="display:none; max-width:200px; margin-top:1rem;"><br><br>

        <button type="submit" class="confirm-button">Add Product</button>
      </form>
    </main>

    <hr class="cyberpunk-hr">

    <!-- Footer -->
    <?php include 'templates/footer.php'; ?>
  </div>

  <script>
    function previewImage(input) {
      const preview = document.getElementById('image-preview');
      const file = input.files[0];
      if (file) {
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
      }
    }
  </script>
</body>
</html>
