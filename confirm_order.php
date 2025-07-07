<?php
session_start();
require 'database.php';

date_default_timezone_set("America/Denver");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<p>Invalid request.</p>";
    exit;
}

// Pull cart from session
$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    echo "<p>Cart is empty.</p>";
    exit;
}

// Customer info
$cname = $_POST['cname'];
$caddy = $_POST['caddy'];
$ccity = $_POST['ccity'];
$cstate = $_POST['cstate'];
$czip = $_POST['czip'];
$cphone = $_POST['cphone'];
$cemail = $_POST['cemail'];

$sname = $_POST['sname'];
$saddy = $_POST['saddy'];
$scity = $_POST['scity'];
$sstate = $_POST['sstate'];
$szip = $_POST['szip'];
$sphone = $_POST['sphone'];
$semail = $_POST['semail'];

// Totals and promo data
$subtotal        = floatval($_POST['subtotal']);
$discount_code   = trim($_POST['discount_code']);
$discount_pct    = floatval($_POST['discount_pct']);
$discount_amount = floatval($_POST['discount_amount']);
$tax             = floatval($_POST['tax']);
$total           = floatval($_POST['total']);

// Build order data
$order_items = [];
$bike_ids = implode(',', array_map('intval', array_keys($cart)));
$result = $mysqli->query("SELECT * FROM Mountain_Bike WHERE id IN ($bike_ids)");

while ($bike = $result->fetch_assoc()) {
    $id = $bike['id'];
    $qty = $cart[$id];
    $price = $bike['price'];

    $order_items[] = [
        'bike_id' => $id,
        'qty' => $qty,
        'price_each' => $price
    ];
}

// Save to database
$stmt = $mysqli->prepare("
  INSERT INTO invoice (cname, caddy, ccity, cstate, czip, cphone, cemail,
                       sname, saddy, scity, sstate, szip, sphone, semail,
                       subtotal, discount_code, discount_pct, discount_amount, tax, total, created_at)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
");

$stmt->bind_param("sssssssssssssssssdd", 
  $cname, $caddy, $ccity, $cstate, $czip, $cphone, $cemail,
  $sname, $saddy, $scity, $sstate, $szip, $sphone, $semail,
  $subtotal, $discount_code, $discount_pct, $discount_amount, $tax, $total
);
$stmt->execute();
$invoice_id = $stmt->insert_id;
$stmt->close();

// Save line items
$line_stmt = $mysqli->prepare("INSERT INTO invoice_products (invoice_id, product_id, quantity, price_each) VALUES (?, ?, ?, ?)");
foreach ($order_items as $item) {
    $line_stmt->bind_param("iiid", $invoice_id, $item['bike_id'], $item['qty'], $item['price_each']);
    $line_stmt->execute();
}
$line_stmt->close();

// Clear cart
unset($_SESSION['cart']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Confirmation – TRON Cycles</title>
  <link rel="stylesheet" href="static/base.css">
  <link rel="stylesheet" href="static/components.css">
</head>
<body>
  <div class="page-wrapper">
    <?php include 'templates/hero.php'; ?>

    <main class="invoice-box">
      <h1>✅ Order Confirmed</h1>
      <p>Thank you, <?= htmlspecialchars($cname) ?>! Your order has been successfully submitted.</p>
      <p><strong>Invoice #<?= $invoice_id ?></strong></p>

      <h2>Order Summary</h2>
      <ul>
        <?php foreach ($order_items as $item): ?>
          <li><?= $item['qty'] ?> × Product #<?= $item['bike_id'] ?> @ $<?= number_format($item['price_each'], 2) ?> each</li>
        <?php endforeach; ?>
      </ul>
      <hr>
      <p><strong>Subtotal:</strong> $<?= number_format($subtotal, 2) ?></p>
      <?php if ($discount_amount > 0): ?>
        <p><strong>Discount (<?= htmlspecialchars($discount_code) ?> – <?= $discount_pct ?>%):</strong> −$<?= number_format($discount_amount, 2) ?></p>
      <?php endif; ?>
      <p><strong>Tax:</strong> $<?= number_format($tax, 2) ?></p>
      <p><strong>Total:</strong> $<?= number_format($total, 2) ?></p>

      <div class="button-container">
        <a href="index.php" class="buy-button">Return to Home</a>
      </div>
    </main>

    <hr class="cyberpunk-hr">
    <?php include 'templates/footer.php'; ?>
  </div>
</body>
</html>
