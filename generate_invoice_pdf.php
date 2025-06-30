<?php
use Dompdf\Dompdf;
require 'vendor/autoload.php';

function generateInvoicePDF($order_id, $customer_name) {
    $html = "<h1>Invoice for Order #$order_id</h1><p>Customer: $customer_name</p>";

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('letter', 'portrait');
    $dompdf->render();

    $pdfOutput = $dompdf->output();
    $pdfPath = __DIR__ . "/invoices/invoice_$order_id.pdf";
    file_put_contents($pdfPath, $pdfOutput);

    return $pdfPath;
}
