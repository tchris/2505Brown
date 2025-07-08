<?php
session_start();
require 'database.php';

date_default_timezone_set("America/Denver");

// Pull cart from session
$cart = $_SESSION['cart'] ?? [];

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($cart)) {
    echo "<p>Invalid access or empty cart.</p>";
    exit;
}

// Grab checkout form data from POST
$cname   = $_POST['cname'];
$caddy   = $_POST['caddy'];
$ccity   = $_POST['ccity'];
$cstate  = $_POST['cstate'];
$czip    = $_POST['czip'];
$cphone  = $_POST['cphone'];
$cemail  = $_POST['cemail'];
$sname   = $_POST['sname'];
$saddy   = $_POST['saddy'];
$scity   = $_POST['scity'];
$sstate  = $_POST['sstate'];
$szip    = $_POST['szip'];
$sphone  = $_POST['sphone'];
$semail  = $_POST['semail'];

// Optional promo code (PHP-based validation)
$discount_code = $_POST['discount_code'] ?? '';
$discount_pct = 0;
$discount_message = '';
$now = date('Y-m-d');

if (!empty($discount_code)) {
    $stmt = $mysqli->prepare("SELECT discount_pct FROM promotions WHERE code = ? AND start_date <= ? AND end_date >= ?");
    $stmt->bind_param("sss", $discount_code, $now, $now);
    $stmt->execute();
    $stmt->bind_result($found_pct);
    if ($stmt->fetch()) {
        $discount_pct = floatval($found_pct);
        $discount_message = "✅ {$discount_pct}% discount applied!";
    } else {
        $discount_message = "❌ Invalid or expired code.";
    }
    $stmt->close();
}

// Pull items
$bike_ids = implode(',', array_map('intval', array_keys($cart)));
$result = $mysqli->query("SELECT * FROM Mountain_Bike WHERE id IN ($bike_ids)");

$subtotal = 0;
$items = [];

while ($bike = $result->fetch_assoc()) {
    $id = $bike['id'];
    $qty = $cart[$id];
    $price = $bike['price'];
    $line_total = $qty * $price;

    $items[] = [
        'bike_id' => $id,
        'qty' => $qty,
        'price_each' => $price,
        'name' => $bike['name']
    ];

    $subtotal += $line_total;
}

// Apply discount + tax + final total
$discount_amount = round($subtotal * ($discount_pct / 100), 2);
$taxable_total = $subtotal - $discount_amount;
$tax_rate = 0.08;
$tax = round($taxable_total * $tax_rate, 2);
$total = round($taxable_total + $tax, 2);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Invoice Preview – TRON Cycles</title>
  <link rel="stylesheet" href="static/base.css">
  <link rel="stylesheet" href="static/components.css">
  <link rel="stylesheet" href="static/layout.css">
</head>
<body>
  <!-- Hero Wrapper -->
  <?php include 'templates/hero.php'; ?>

  <form action="confirm_order.php" method="post">
  <main class="invoice-box">
    <h1>Invoice Preview</h1>
    <p><strong>Date:</strong> <?= date("Y-m-d") ?></p>

    <!-- Discount Input Field -->
    <div class="form-group">
      <label for="discount_code">Promo Code:</label>
      <input type="text" name="discount_code" id="discount_code" placeholder="Enter promo code" value="<?= htmlspecialchars($discount_code) ?>">
      <button type="submit" class="buy-button">Apply</button>
      <span class="status-text"><?= $discount_message ?></span>
    </div>

    <!-- Billing -->
    <!-- [UNCHANGED: all billing, shipping, order summary sections remain as you posted above] -->

    <!-- Order Summary -->
    <div class="section">
      <h2>Order Summary</h2>
      <table>
        <thead>
          <tr>
            <th>Qty</th>
            <th>Description</th>
            <th>Each</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($items as $item): ?>
          <tr>
            <td><?= $item['qty'] ?></td>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td>$<?= number_format($item['price_each'], 2) ?></td>
            <td>$<?= number_format($item['qty'] * $item['price_each'], 2) ?></td>
          </tr>
          <?php endforeach; ?>
          <tr class="total-row">
            <td colspan="3">Subtotal</td>
            <td>$<?= number_format($subtotal, 2) ?></td>
          </tr>
          <tr class="total-row">
            <td colspan="3">Discount (<?= $discount_pct ?>%)</td>
            <td>−$<?= number_format($discount_amount, 2) ?></td>
          </tr>
          <tr class="total-row">
            <td colspan="3">Sales Tax (8%)</td>
            <td>$<?= number_format($tax, 2) ?></td>
          </tr>
          <tr class="total-row">
            <td colspan="3">Total</td>
            <td>$<?= number_format($total, 2) ?></td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Hidden inputs for confirm_order.php -->
    <input type="hidden" name="discount_pct" value="<?= $discount_pct ?>">
    <input type="hidden" name="discount_amount" value="<?= $discount_amount ?>">
    <input type="hidden" name="subtotal" value="<?= htmlspecialchars($subtotal) ?>">
    <input type="hidden" name="tax" value="<?= htmlspecialchars($tax) ?>">
    <input type="hidden" name="total" value="<?= htmlspecialchars($total) ?>">

    <div class="button-container">
      <button type="submit" class="confirm-button">SUBMIT ORDER</button>
    </div>
  </main>
  </form>

  <script src="static/js/invoice.js"></script>
  <hr class="cyberpunk-hr">
  <?php include 'templates/footer.php'; ?>
</body>
</html>
