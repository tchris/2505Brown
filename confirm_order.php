<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'database.php';

// Get cart from session
$cart = $_SESSION['cart'] ?? [];

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($cart)) {
    echo "<p>Invalid access or empty cart.</p>";
    exit;
}

// Capture form data
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

// Pull final calculated amounts from POST (trust preview page)
$discount_pct = $_POST['discount_pct'] ?? 0;
$discount_amount = $_POST['discount_amount'] ?? 0;\
$subtotal = $_POST['subtotal'];
$tax = $_POST['tax'];
$total = $_POST['total'];

// Pull cart data for line items
$bike_ids = implode(',', array_map('intval', array_keys($cart)));
$result = $mysqli->query("SELECT * FROM Mountain_Bike WHERE id IN ($bike_ids)");

$items = [];
while ($bike = $result->fetch_assoc()) {
    $id = $bike['id'];
    $qty = $cart[$id];
    $price = $bike['price'];

    $items[] = [
        'bike_id' => $id,
        'qty' => $qty,
        'price_each' => $price,
        'name' => $bike['name']
    ];
}

// Insert invoice with tax
$stmt = $mysqli->prepare("
    INSERT INTO invoice 
    (inv_date, ship_date, cname, caddy, ccity, cstate, czip, cphone, cemail, 
     sname, saddy, scity, sstate, szip, sphone, semail, subtotal, tax, total) 
    VALUES 
    (CURDATE(), CURDATE() + INTERVAL 2 DAY, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param("ssssssssssssssddd",
    $cname, $caddy, $ccity, $cstate, $czip, $cphone, $cemail,
    $sname, $saddy, $scity, $sstate, $szip, $sphone, $semail,
    $subtotal, $tax, $total
);

$stmt->execute();
$inv_id = $mysqli->insert_id;

// Insert line items
$stmt_items = $mysqli->prepare("INSERT INTO invoice_products (inv_id, bike_id, qty) VALUES (?, ?, ?)");
foreach ($items as $item) {
    $stmt_items->bind_param("iii", $inv_id, $item['bike_id'], $item['qty']);
    $stmt_items->execute();
}

// Generate PDF and send email
require 'generate_invoice_pdf.php';
require 'send_invoice_emails.php';

$pdfPath = generateInvoicePDF($inv_id, $cname);
sendInvoiceEmails($pdfPath, $cemail, $cname, $inv_id);

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
  <link rel="stylesheet" href="static/layout.css">
</head>
<body>
  <!-- Hero Wrapper -->
        <?php include 'templates/hero.php'; ?>

  <main class="invoice-box">
    <h1>Thank You for Your Order!</h1>
    <p><strong>Date:</strong> <?= date("Y-m-d") ?></p>
    <p><strong>Invoice #:</strong> <?= $inv_id ?></p>

    <div class="section">
      <h2>Bill To:</h2>
      <p><?= htmlspecialchars($cname) ?><br>
         <?= htmlspecialchars($caddy) ?><br>
         <?= htmlspecialchars($ccity) ?>, <?= htmlspecialchars($cstate) ?> <?= htmlspecialchars($czip) ?><br>
         <?= htmlspecialchars($cemail) ?> | <?= htmlspecialchars($cphone) ?></p>
    </div>

    <div class="section">
      <h2>Ship To:</h2>
      <p><?= htmlspecialchars($sname) ?><br>
         <?= htmlspecialchars($saddy) ?><br>
         <?= htmlspecialchars($scity) ?>, <?= htmlspecialchars($sstate) ?> <?= htmlspecialchars($szip) ?><br>
         <?= htmlspecialchars($semail) ?> | <?= htmlspecialchars($sphone) ?></p>
    </div>

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
          <?php if ($discount_pct > 0): ?>
          <tr class="total-row">
            <td colspan="3">Discount (<?= htmlspecialchars($discount_pct) ?>%)</td>
            <td>−$<?= number_format($discount_amount, 2) ?></td>
          </tr>
          <?php endif; ?>
          <tr class="total-row">
            <td colspan="3">Sales Tax</td>
            <td>$<?= number_format($tax, 2) ?></td>
          </tr>
          <tr class="total-row">
            <td colspan="3">Total</td>
            <td>$<?= number_format($total, 2) ?></td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="button-container">
      <a class="confirm-button" href="index.php">Back to Home</a>
    </div>
  </main>
  <hr class="cyberpunk-hr">

            <!-- Footer -->
            <?php include 'templates/footer.php'; ?>
</body>
</html>
