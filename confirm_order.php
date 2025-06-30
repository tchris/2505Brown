<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'database.php';

$cart = $_SESSION['cart'] ?? [];

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($cart)) {
    echo "<p>Invalid access or empty cart.</p>";
    exit;
}

// 1. Capture form data
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
    (inv_date, ship_date, cname, caddy, ccity, cstate, czip, cphone, cemail, sname, saddy, scity, sstate, szip, sphone, semail, subtotal, total) 
    VALUES 
    (CURDATE(), CURDATE() + INTERVAL 2 DAY, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

if (!$stmt) {
    die("Invoice prepare failed: " . $mysqli->error);
}

$stmt->bind_param("ssssssssssssssdd", 
    $cname, $caddy, $ccity, $cstate, $czip, $cphone, $cemail,
    $sname, $saddy, $scity, $sstate, $szip, $sphone, $semail,
    $subtotal, $total
);

if (!$stmt->execute()) {
    die("Invoice insert failed: " . $stmt->error);
}

$inv_id = $mysqli->insert_id;

// 4. Insert line items
$stmt_items = $mysqli->prepare("INSERT INTO invoice_products (inv_id, bike_id, qty) VALUES (?, ?, ?)");
if (!$stmt_items) {
    die("Line item prepare failed: " . $mysqli->error);
}

foreach ($items as $item) {
    $stmt_items->bind_param("iii", $inv_id, $item['bike_id'], $item['qty']);
    if (!$stmt_items->execute()) {
        die("Line item insert failed: " . $stmt_items->error);
    }
}

// 5. Generate invoice & send emails
require 'generate_invoice_pdf.php';
require 'send_invoice_emails.php';

$pdfPath = generateInvoicePDF($inv_id, $cname);
sendInvoiceEmails($pdfPath, $cemail, $cname, $inv_id);

// 6. Clear cart
unset($_SESSION['cart']);
?>
