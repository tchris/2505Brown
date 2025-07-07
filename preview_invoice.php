<?php
session_start();
require 'database.php';

date_default_timezone_set("America/Denver");

$cart = $_SESSION['cart'] ?? [];
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($cart)) {
    echo "<p>Invalid access or empty cart.</p>";
    exit;
}

// Collect customer billing/shipping POST data
$cname = $_POST['cname']; $caddy = $_POST['caddy']; $ccity = $_POST['ccity']; $cstate = $_POST['cstate'];
$czip = $_POST['czip']; $cphone = $_POST['cphone']; $cemail = $_POST['cemail'];
$sname = $_POST['sname']; $saddy = $_POST['saddy']; $scity = $_POST['scity'];
$sstate = $_POST['sstate']; $szip = $_POST['szip']; $sphone = $_POST['sphone']; $semail = $_POST['semail'];

// Discount Code
$discount_code = trim($_POST['discount_code'] ?? '');
$discount_amount = 0.0;
$discount_pct = 0.0;
$promo_applied = false;

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

// Check for valid promotion
if ($discount_code) {
    $today = date('Y-m-d');
    $promo_stmt = $mysqli->prepare("SELECT * FROM promotions WHERE code = ? AND start_date <= ? AND end_date >= ?");
    $promo_stmt->bind_param("sss", $discount_code, $today, $today);
    $promo_stmt->execute();
    $promo_result = $promo_stmt->get_result();

    if ($promo = $promo_result->fetch_assoc()) {
        $discount_pct = floatval($promo['discount_pct']);
        $discount_amount = $subtotal * ($discount_pct / 100);
        $promo_applied = true;
    }
    $promo_stmt->close();
}

$taxable = $subtotal - $discount_amount;
$tax_rate = 0.08;
$tax = $taxable * $tax_rate;
$total = $taxable + $tax;
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
  <?php include 'templates/hero.php'; ?>

  <form action="confirm_order.php" method="post">
    <main class="invoice-box">
      <h1>Invoice Preview</h1>
      <p><strong>Date:</strong> <?= date("Y-m-d") ?></p>

      <!-- Discount Code -->
      <div class="form-group">
        <label>Discount Code (optional):</label>
        <input type="text" name="discount_code" value="<?= htmlspecialchars($discount_code) ?>">
        <?php if ($discount_code && $promo_applied): ?>
          <p style="color: green;">✅ Promo “<?= htmlspecialchars($discount_code) ?>” applied: <?= $discount_pct ?>% off</p>
        <?php elseif ($discount_code): ?>
          <p style="color: red;">⚠️ Promo not valid or expired</p>
        <?php endif; ?>
      </div>

      <!-- Billing and Shipping Sections (unchanged) -->
      <!-- ... same fields from your form go here ... -->

      <!-- Order Summary -->
      <div class="section">
        <h2>Order Summary</h2>
        <table>
          <thead>
            <tr><th>Qty</th><th>Description</th><th>Each</th><th>Total</th></tr>
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
            <tr class="total-row"><td colspan="3">Subtotal</td><td>$<?= number_format($subtotal, 2) ?></td></tr>
            <?php if ($promo_applied): ?>
              <tr class="total-row"><td colspan="3">Promo (<?= $discount_pct ?>% off)</td><td>−$<?= number_format($discount_amount, 2) ?></td></tr>
            <?php endif; ?>
            <tr class="total-row"><td colspan="3">Sales Tax (8%)</td><td>$<?= number_format($tax, 2) ?></td></tr>
            <tr class="total-row"><td colspan="3">Total</td><td>$<?= number_format($total, 2) ?></td></tr>
          </tbody>
        </table>
      </div>

      <!-- Hidden Fields -->
      <?php
      foreach ([
        'cname', 'caddy', 'ccity', 'cstate', 'czip', 'cphone', 'cemail',
        'sname', 'saddy', 'scity', 'sstate', 'szip', 'sphone', 'semail'
      ] as $field) {
          echo "<input type='hidden' name='$field' value='" . htmlspecialchars($$field) . "'>\n";
      }
      ?>
      <input type="hidden" name="subtotal" value="<?= $subtotal ?>">
      <input type="hidden" name="discount_code" value="<?= htmlspecialchars($discount_code) ?>">
      <input type="hidden" name="discount_pct" value="<?= $discount_pct ?>">
      <input type="hidden" name="discount_amount" value="<?= $discount_amount ?>">
      <input type="hidden" name="tax" value="<?= $tax ?>">
      <input type="hidden" name="total" value="<?= $total ?>">

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
