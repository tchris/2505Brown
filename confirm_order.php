<?php
session_start();
require 'database.php';

$cart = $_SESSION['cart'] ?? [];

// Optional: Turn on error reporting for debugging
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($cart)) {
    echo "<p>Invalid access or empty cart.</p>";
    exit;
}

// 1. Capture form data
$cname   = $_POST['cname'];
$caddy   = $_POST['caddy'];
$cstate  = $_POST['cstate'];
$czip    = $_POST['czip'];
$cphone  = $_POST['cphone'];
$cemail  = $_POST['cemail'];
$sname   = $_POST['sname'];
$saddy   = $_POST['saddy'];
$sstate  = $_POST['sstate'];
$szip    = $_POST['szip'];
$sphone  = $_POST['sphone'];
$semail  = $_POST['semail'];

// 2. Get product data and calculate totals
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

$total = $subtotal;

// 3. Insert invoice
$stmt = $mysqli->prepare("
    INSERT INTO invoice 
    (inv_date, ship_date, cname, caddy, cstate, czip, cphone, cemail, sname, saddy, sstate, szip, sphone, semail, subtotal, total) 
    VALUES 
    (CURDATE(), CURDATE() + INTERVAL 2 DAY, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

if (!$stmt) {
    die("Invoice prepare failed: " . $mysqli->error);
}

$stmt->bind_param("ssssssssssssssdd", 
    $cname, $caddy, $cstate, $czip, $cphone, $cemail,
    $sname, $saddy, $sstate, $szip, $sphone, $semail,
    $subtotal, $total
);

if (!$stmt->execute()) {
    die("Invoice insert failed: " . $stmt->error);
}

$inv_id = $mysqli->insert_id;

// 4. Insert invoice line items into `invoice_products`
$stmt_items = $mysqli->prepare("INSERT INTO invoice_products (inv_id, bike_id, qty, price_each) VALUES (?, ?, ?, ?)");
if (!$stmt_items) {
    die("Line item prepare failed: " . $mysqli->error);
}

foreach ($items as $item) {
    $stmt_items->bind_param("iiid", $inv_id, $item['bike_id'], $item['qty'], $item['price_each']);
    if (!$stmt_items->execute()) {
        die("Line item insert failed: " . $stmt_items->error);
    }
}

// 5. Clear cart
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
    <div class="hero">
        <img src="img/CP-Bike.png" class="hero__image">
        <h1 class="hero__title"><a href="/2505Chartreuse/">TRON Bike Shop</a></h1>
        <h2 class="hero__menu">
            <a href="/2505Chartreuse/hardtail.php">Hardtail</a> |
            <a href="/2505Chartreuse/fullsuspension.php">Full Suspension</a> |
            <a href="/2505Chartreuse/accessories.php">Accessories Galore</a>
        </h2>
    </div>

    <main>
        <h1 class="section-heading">Thank You for Your Order!</h1>
        <p>Invoice #: <strong><?= $inv_id ?></strong></p>

        <h2>Shipping To</h2>
        <p><?= htmlspecialchars($sname) ?><br>
        <?= htmlspecialchars($saddy) ?><br>
        <?= htmlspecialchars($sstate) ?> <?= htmlspecialchars($szip) ?><br>
        <?= htmlspecialchars($semail) ?> | <?= htmlspecialchars($sphone) ?></p>

        <h2>Order Summary</h2>
        <ul class="cart-list">
            <?php foreach ($items as $item): ?>
                <li class="cart-item">
                    <?= htmlspecialchars($item['name']) ?> × <?= $item['qty'] ?> – $<?= number_format($item['qty'] * $item['price_each'], 2) ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <p class="cart-total"><strong>Total: $<?= number_format($total, 2) ?></strong></p>

        <a href="index.php" class="buy-button">Back to Home</a>
    </main>
</body>
</html>
