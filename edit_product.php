<?php
session_start();
require 'database.php';

// Must be logged in as manager
if (!isset($_SESSION['manager_logged_in'])) {
  header('Location: login.php');
  exit;
}

// Validate product ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
  echo "<p>Invalid product ID.</p>";
  exit;
}

// Fetch product
$stmt = $mysqli->prepare("SELECT * FROM Mountain_Bike WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) {
  echo "<p>Product not found.</p>";
  exit;
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $price = floatval($_POST['price']);
    $descr = $_POST['descr'];
    $category = $_POST['category'];
    $picture = $_POST['picture'];

    $update_stmt = $mysqli->prepare("
      UPDATE Mountain_Bike 
      SET name = ?, price = ?, descr = ?, category = ?, picture = ?
      WHERE id = ?
    ");
    $update_stmt->bind_param("sdsssi", $name, $price, $descr, $category, $picture, $id);
    $update_stmt->execute();
    echo "<p style='color: green;'>Product updated!</p>";
    $update_stmt->close();
  }

  // Handle delete
  if (isset($_POST['delete'])) {
    $delete_stmt = $mysqli->prepare("DELETE FROM Mountain_Bike WHERE id = ?");
    $delete_stmt->bind_param("i", $id);
    $delete_stmt->execute();
    $delete_stmt->close();
    echo "<p style='color: red;'>Product deleted!</p>";
    echo "<a href='index_manager.php'>Return to Manager Dashboard</a>";
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Product – Manager</title>
  <link rel="stylesheet" href="static/base.css">
</head>
<body>
  <h1>Edit Product ID #<?= htmlspecialchars($product['id']) ?></h1>

  <form method="post">
    <label>Name:</label><br>
    <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required><br><br>

    <label>Price:</label><br>
    <input type="text" name="price" value="<?= htmlspecialchars($product['price']) ?>" required><br><br>

    <label>Description:</label><br>
    <textarea name="descr" rows="5" cols="50"><?= htmlspecialchars($product['descr']) ?></textarea><br><br>

    <label>Category:</label><br>
    <select name="category">
      <option value="Hardtail" <?= $product['category'] == 'Hardtail' ? 'selected' : '' ?>>Hardtail</option>
      <option value="Full Suspens" <?= $product['category'] == 'Full Suspens' ? 'selected' : '' ?>>Full Suspension</option>
      <option value="Accessories" <?= $product['category'] == 'Accessories' ? 'selected' : '' ?>>Accessories</option>
    </select><br><br>

    <label>Picture Filename:</label><br>
    <input type="text" name="picture" value="<?= htmlspecialchars($product['picture']) ?>" required><br><br>

    <button type="submit" name="update">Update Product</button>
    <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this product?');">Delete Product</button>
  </form>

  <p><a href="index_manager.php">← Back to Manager Home</a></p>
</body>
</html>
