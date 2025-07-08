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

// Calculate tax + total
$tax_rate = 0.08; // Example 8%
$tax = $subtotal * $tax_rate;
$total = $subtotal + $tax;
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

  <!-- Discount Input Field-->
    <div class="form-group">
      <label for="discount_code">Promo Code:</label>
      <input type="text" name="discount_code" id="discount_code" placeholder="Enter promo code">
      <button type="button" id="applyDiscount" class="buy-button">Apply</button>
      <span id="discountStatus" class="status-text"></span>
    </div>

    <!-- Billing -->
    <div class="section">
      <h2>Bill To:</h2>
      <div class="form-group">
        <label>Name</label>
        <input name="cname" id="cname" required value="<?= htmlspecialchars($cname) ?>">
      </div>
      <div class="form-group">
        <label>Address</label>
        <input name="caddy" id="caddy" required value="<?= htmlspecialchars($caddy) ?>">
      </div>
      <div class="form-row">
        <div class="form-group city">
          <label>City</label>
          <input name="ccity" id="ccity" required value="<?= htmlspecialchars($ccity) ?>">
        </div>
        <div class="form-group state">
          <label>State</label>
          <input name="cstate" id="cstate" maxlength="2" required value="<?= htmlspecialchars($cstate) ?>">
        </div>
        <div class="form-group zip">
          <label>Zip</label>
          <input name="czip" id="czip" maxlength="10" required value="<?= htmlspecialchars($czip) ?>">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group cphone">
          <label>Phone</label>
          <input name="cphone" id="cphone" required value="<?= htmlspecialchars($cphone) ?>">
        </div>
        <div class="form-group cemail">
          <label>Email</label>
          <input name="cemail" id="cemail" required value="<?= htmlspecialchars($cemail) ?>">
        </div>
      </div>
    </div>


    <div class="copyBilling">
      <input type="checkbox" id="copyBilling">
      <label for="copyBilling">SHIPPING SAME AS BILLING</label>
    </div>

    <!-- Shipping -->
    <div class="section">
      <h2>Ship To:</h2>
      <div class="form-group">
        <label>Name</label>
        <input name="sname" id="sname" required value="<?= htmlspecialchars($sname) ?>">
      </div>
      <div class="form-group">
        <label>Address</label>
        <input name="saddy" id="saddy" required value="<?= htmlspecialchars($saddy) ?>">
      </div>
      <div class="form-row">
        <div class="form-group city">
          <label>City</label>
          <input name="scity" id="scity" required value="<?= htmlspecialchars($scity) ?>">
        </div>
        <div class="form-group state">
          <label>State</label>
          <input name="sstate" id="sstate" maxlength="2" required value="<?= htmlspecialchars($sstate) ?>">
        </div>
        <div class="form-group zip">
          <label>Zip</label>
          <input name="szip" id="szip" maxlength="10" required value="<?= htmlspecialchars($szip) ?>">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group cphone">
          <label>Phone</label>
          <input name="sphone" id="sphone" required value="<?= htmlspecialchars($sphone) ?>">
        </div>
        <div class="form-group cemail">
          <label>Email</label>
          <input name="semail" id="semail" required value="<?= htmlspecialchars($semail) ?>">
        </div>
      </div>
    </div>

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
            <td id="subtotal_display">$<?= number_format($subtotal, 2) ?></td>
          </tr>
          <tr class="total-row" id="discount_row" style="display:none;">
            <td colspan="3">Discount (<span id="discount_pct_display">0</span>%)</td>
            <td id="discount_amount_display">−$0.00</td>
          </tr>
          <tr class="total-row">
            <td colspan="3">Sales Tax (8%)</td>
            <td id="tax_display">$<?= number_format($tax, 2) ?></td>
          </tr>
          <tr class="total-row">
            <td colspan="3">Total</td>
            <td id="total_display">$<?= number_format($total, 2) ?></td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Hidden inputs for confirm_order.php -->
    <input type="hidden" name="discount_pct" id="discount_pct" value="0">
    <input type="hidden" name="discount_amount" id="discount_amount" value="0">
    <input type="hidden" name="subtotal" value="<?= htmlspecialchars($subtotal) ?>">
    <input type="hidden" name="tax" value="<?= htmlspecialchars($tax) ?>">
    <input type="hidden" name="total" id="total" value="<?= htmlspecialchars($total) ?>">

    <div class="button-container">
      <button type="submit" class="confirm-button">SUBMIT ORDER</button>
    </div>
  </main>
  </form>
  <script src="static/js/invoice.js"></script>

  <hr class="cyberpunk-hr">

            <!-- Footer -->
            <?php include 'templates/footer.php'; ?>
</body>
</html>
