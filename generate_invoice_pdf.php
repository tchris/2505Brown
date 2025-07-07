<?php

require __DIR__ . '/vendor/autoload.php';
require_once 'database.php'; // Safe to include now due to guard clauses below

use Dompdf\Dompdf;

// Safe constant definitions to avoid "already defined" warnings
if (!defined('HOST'))     define('HOST', 'your_host');
if (!defined('USER'))     define('USER', 'your_user');
if (!defined('PASSWORD')) define('PASSWORD', 'your_password');
if (!defined('DATABASE')) define('DATABASE', 'your_database');

// Ensure mysqli connection exists
$mysqli = $mysqli ?? new mysqli(HOST, USER, PASSWORD, DATABASE);
if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

function generateInvoicePDF($order_id, $customer_name) {
    global $mysqli;

    // Fetch invoice details
    $stmt = $mysqli->prepare("SELECT * FROM invoice WHERE inv_id = ?");
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }

    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $invoice = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Fetch invoice line items
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

    // Build HTML
    ob_start(); ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Invoice #<?= $order_id ?></title>
        <style>
            body { font-family: Arial, sans-serif; font-size: 14px; }
            h1 { text-align: center; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { padding: 8px; border: 1px solid #ccc; text-align: left; }
            .right { text-align: right; }
            .totals td { font-weight: bold; }
        </style>
    </head>
    <body>
        <h1>Invoice #<?= $order_id ?></h1>
        <p><strong>Date:</strong> <?= $invoice['inv_date'] ?></p>
        <p><strong>Ship To:</strong><br>
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

    // Generate PDF
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('letter', 'portrait');
    $dompdf->render();

    $pdfPath = __DIR__ . "/invoices/invoice_$order_id.pdf";
    file_put_contents($pdfPath, $dompdf->output());

    return $pdfPath;
}
