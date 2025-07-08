<?php

require __DIR__ . '/vendor/autoload.php';
require_once 'database.php';

use Dompdf\Dompdf;

if (!defined('HOST'))     define('HOST', 'your_host');
if (!defined('USER'))     define('USER', 'your_user');
if (!defined('PASSWORD')) define('PASSWORD', 'your_password');
if (!defined('DATABASE')) define('DATABASE', 'your_database');

$mysqli = $mysqli ?? new mysqli(HOST, USER, PASSWORD, DATABASE);
if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

function generateInvoicePDF($order_id, $customer_name) {
    global $mysqli;

    // Fetch invoice
    $stmt = $mysqli->prepare("SELECT * FROM invoice WHERE inv_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $invoice = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Fetch items
    $stmt_items = $mysqli->prepare("
        SELECT m.name, p.qty, m.price 
        FROM invoice_products p 
        JOIN Mountain_Bike m ON p.bike_id = m.id 
        WHERE p.inv_id = ?
    ");
    $stmt_items->bind_param("i", $order_id);
    $stmt_items->execute();
    $result_items = $stmt_items->get_result();
    $stmt_items->close();

    $discount = 0;
    $discountRow = '';
    if (!empty($invoice['subtotal']) && !empty($invoice['total'])) {
        $raw_total = $invoice['subtotal'] * 1.08; // 8% tax without discount
        $discount = $raw_total - floatval($invoice['total']);
        if ($discount > 0.01) {
            $pct = round(($discount / $invoice['subtotal']) * 100, 2);
            $discountRow = "
                <tr class='totals'>
                    <td colspan='3'>Discount ({$pct}%)</td>
                    <td style='color:red;'>−$" . number_format($discount, 2) . "</td>
                </tr>";
        }
    }

    ob_start(); ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Invoice #<?= $order_id ?></title>
        <style>
            body { font-family: Arial, sans-serif; font-size: 14px; }
            h1, h2 { text-align: center; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { padding: 8px; border: 1px solid #ccc; }
            .totals td { font-weight: bold; }
        </style>
    </head>
    <body>
        <h1>Invoice #<?= $order_id ?></h1>
        <p><strong>Date:</strong> <?= $invoice['inv_date'] ?></p>

        <h2>Bill To</h2>
        <p>
            <?= htmlspecialchars($invoice['cname']) ?><br>
            <?= htmlspecialchars($invoice['caddy']) ?><br>
            <?= htmlspecialchars($invoice['ccity']) ?>, <?= htmlspecialchars($invoice['cstate']) ?> <?= htmlspecialchars($invoice['czip']) ?><br>
            <?= htmlspecialchars($invoice['cemail']) ?> | <?= htmlspecialchars($invoice['cphone']) ?>
        </p>

        <h2>Ship To</h2>
        <p>
            <?= htmlspecialchars($invoice['sname']) ?><br>
            <?= htmlspecialchars($invoice['saddy']) ?><br>
            <?= htmlspecialchars($invoice['scity']) ?>, <?= htmlspecialchars($invoice['sstate']) ?> <?= htmlspecialchars($invoice['szip']) ?><br>
            <?= htmlspecialchars($invoice['semail']) ?> | <?= htmlspecialchars($invoice['sphone']) ?>
        </p>

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
                <?php while ($item = $result_items->fetch_assoc()): ?>
                <tr>
                    <td><?= $item['qty'] ?></td>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td>$<?= number_format($item['price'], 2) ?></td>
                    <td>$<?= number_format($item['price'] * $item['qty'], 2) ?></td>
                </tr>
                <?php endwhile; ?>
                <tr class="totals">
                    <td colspan="3">Subtotal</td>
                    <td>$<?= number_format($invoice['subtotal'], 2) ?></td>
                </tr>
                <?= $discountRow ?>
                <tr class="totals">
                    <td colspan="3">Sales Tax</td>
                    <td>$<?= number_format($invoice['tax'], 2) ?></td>
                </tr>
                <tr class="totals">
                    <td colspan="3">Total</td>
                    <td>$<?= number_format($invoice['total'], 2) ?></td>
                </tr>
            </tbody>
        </table>
    </body>
    </html>
    <?php
    $html = ob_get_clean();

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('letter', 'portrait');
    $dompdf->render();

    $pdfPath = __DIR__ . "/invoices/invoice_$order_id.pdf";
    file_put_contents($pdfPath, $dompdf->output());

    return $pdfPath;
}
