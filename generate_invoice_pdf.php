use Dompdf\Dompdf;

require 'vendor/autoload.php';

$order_id = $_POST['order_id'] ?? 'TEMP123'; // example
$customer_name = $_POST['customer_name'] ?? 'John Doe';

$html = "<h1>Invoice for Order #$order_id</h1><p>Customer: $customer_name</p>";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$pdfOutput = $dompdf->output();
$pdfPath = __DIR__ . "/invoices/invoice_$order_id.pdf";
file_put_contents($pdfPath, $pdfOutput);

// Return $pdfPath so it can be used in the email
return $pdfPath;
