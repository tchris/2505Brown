<?php
session_start();
require 'database.php';

date_default_timezone_set("America/Denver");

$cart = $_SESSION['cart'] ?? [];

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($cart)) {
    echo "<p>Invalid access or empty cart.</p>";
    exit;
}

// Grab checkout form data
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
           
            <div class="form-group">
                <label for="cname">Name</label>
                <input id="cname" name="cname" required>
            </div>
            
            <div class="form-group">
            <label for="caddy">Address</label>
            <input id="caddy" name="caddy" required>
            </div>
            
            <div class="form-row">
                <div class="form-group city">
                    <label for="ccity">City</label>
                    <input id="ccity" name="ccity" required>
                </div>

                <div class="form-group state">
                    <label for="cstate">State</label>
                    <input id="cstate" name="cstate" maxlength="2" pattern="[A-Za-z]{2}" required>
                </div>

                <div class="form-group zip">
                    <label for="czip">Zip</label>
                    <input id="czip" name="czip" maxlength="10" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group cphone">
                    <label for="cphone">Phone</label>
                    <input id="cphone" name="cphone" required>
                </div> 

                <div class="form-group cemail">
                    <label for="cemail">Email</label>
                    <input id="cemail" name="cemail" required>
                </div>
            </div>

        <div class="section">

            <h2>Ship To:</h2>
           
            <div class="form-group">
                <label for="sname">Name</label>
                <input id="sname" name="sname" required>
            </div>
            
            <div class="form-group">
            <label for="saddy">Address</label>
            <input id="saddy" name="saddy" required>
            </div>
            
            <div class="form-row">
                <div class="form-group city">
                    <label for="scity">City</label>
                    <input id="scity" name="scity" required>
                </div>

                <div class="form-group state">
                    <label for="sstate">State</label>
                    <input id="sstate" name="sstate" maxlength="2" pattern="[A-Za-z]{2}" required>
                </div>

                <div class="form-group zip">
                    <label for="szip">Zip</label>
                    <input id="szip" name="szip" maxlength="10" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group cphone">
                    <label for="sphone">Phone</label>
                    <input id="sphone" name="sphone" required>
                </div> 

                <div class="form-group cemail">
                    <label for="semail">Email</label>
                    <input id="semail" name="semail" required>
                </div>
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
            <div class="button-container">
            <button type="submit" class="confirm-button">SUBMIT ORDER</button>
            </div>
        </form>
    </main>
</body>
</html>
