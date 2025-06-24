<?php
session_start();
require 'database.php';

$cart = $_SESSION['cart'] ?? [];

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($cart)) {
    echo "<p>Invalid access or empty cart.</p>";
    exit;
}

// Grab checkout form data
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

// Pull cart data
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice Preview – TRON Cycles</title>
    <link rel="stylesheet" href="static/base.css">
    <link rel="stylesheet" href="static/components.css">
    <link rel="stylesheet" href="static/layout.css">
    <style>
        .invoice-box {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            border: 2px solid #00ddff;
            box-shadow: 0 0 20px #00ddff;
            background: black;
            color: #00ddff;
            border-radius: 12px;
            font-size: 1rem;
        }
        .invoice-box h1 {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .invoice-box .section {
            margin-bottom: 2rem;
        }
        .invoice-box .section h2 {
            font-size: 1.2rem;
            margin-bottom: .5rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        th, td {
            border: 1px solid #00ddff;
            padding: 0.5rem;
            text-align: left;
        }
        th {
            background-color: #111;
        }
        .total-row td {
            border-top: 2px solid #00ddff;
            font-weight: bold;
        }
        .buy-button {
            display: block;
            margin: 30px auto 0;
        }
    </style>
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

    <main class="invoice-box">
        <h1>Invoice Preview</h1>
        <p><strong>Date:</strong> <?= date("Y-m-d") ?></p>

        <div class="section">
            <h2>Bill To:</h2>
            <p><?= htmlspecialchars($cname) ?><br>
            <?= htmlspecialchars($caddy) ?><br>
            <?= htmlspecialchars($cstate) ?> <?= htmlspecialchars($czip) ?><br>
            <?= htmlspecialchars($cphone) ?> | <?= htmlspecialchars($cemail) ?></p>
        </div>

        <div class="section">
            <h2>Ship To:</h2>
            <p><?= htmlspecialchars($sname) ?><br>
            <?= htmlspecialchars($saddy) ?><br>
            <?= htmlspecialchars($sstate) ?> <?= htmlspecialchars($szip) ?><br>
            <?= htmlspecialchars($sphone) ?> | <?= htmlspecialchars($semail) ?></p>
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
                    <tr class="total-row">
                        <td colspan="3">Total</td>
                        <td>$<?= number_format($total, 2) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <form action="confirm_order.php" method="post">
            <!-- Hidden fields to preserve all input data -->
            <?php foreach ($_POST as $key => $value): ?>
                <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
            <?php endforeach; ?>
            <button type="submit" class="buy-button">Confirm & Submit Order</button>
        </form>
    </main>
</body>
</html>
