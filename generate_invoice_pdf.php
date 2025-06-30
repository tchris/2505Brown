<?php

require __DIR__ . '/vendor/autoload.php';
require 'database.php';

use Dompdf\Dompdf;

function generateInvoicePDF($order_id, $customer_name) {
    global $mysqli;

    // Fetch invoice
    $stmt = $mysqli->prepare("SELECT * FROM invoice WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $invoice = $stmt->get_result()->fetch_assoc();

    // Fetch items
    $stmt_items = $mysqli->prepare("
        SELECT p.name, ip.qty, p.price
        FROM invoice_products ip
        JOIN Mountain_Bike p ON ip.bike_id = p.id
        WHERE ip.inv_id = ?
    ");
    $stmt_items->bind_param("i", $order_id);
    $stmt_items->execute();
    $items_result = $stmt_items->get_result();

    $items_html = '';
    foreach ($items_result as $item) {
        $line_total = $item['qty'] * $item['price'];
        $items_html .= "<tr>
            <td>{$item['qty']}</td>
            <td>" . htmlspecialchars($item['name']) . "</td>
            <td>$" . number_format($item['price'], 2) . "</td>
            <td>$" . number_format($line_total, 2) . "</td>
        </tr>";
    }

    $subtotal = number_format($invoice['subtotal'], 2);
    $total = number_format($invoice['total'], 2);

    $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; padding: 20px; }
        h1 { text-align: center; }
        h2 { margin-top: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .total-row td { font-weight: bold; }
        .section { margin-bottom: 25px; }
    </style>
</head>
<body>
    <h1>Invoice Confirmation</h1>
    <p><strong>Invoice #:</strong> {$order_id}</p>
    <p><strong>Date:</strong> {$invoice['inv_date']}</p>

    <div class="section">
        <h2>Bill To:</h2>
        <p>
            {$invoice['cname']}<br>
            {$invoice['caddy']}<br>
            {$invoice['ccity']}, {$invoice['cstate']} {$invoice['czip']}<br>
            {$invoice['cemail']} | {$invoice['cphone']}
        </p>
    </div>

    <div class="section">
        <h2>Ship To:</h2>
        <p>
            {$invoice['sname']}<br>
            {$invoice['saddy']}<br>
            {$invoice['scity']}, {$invoice['sstate']} {$invoice['szip']}<br>
            {$invoice['semail']} | {$invoice['sphone']}
        </p>
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
                $items_html
                <tr class="total-row">
                    <td colspan="3">Subtotal</td>
                    <td>\$$subtotal</td>
                </tr>
                <tr class="total-row">
                    <td colspan="3">Total</td>
                    <td>\$$total</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
HTML;

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('letter', 'portrait');
    $dompdf->render();

    $pdfOutput = $dompdf->output();
    $pdfPath = __DIR__ . "/invoices/invoice_{$order_id}.pdf";
    file_put_contents($pdfPath, $pdfOutput);

    return $pdfPath;
}
