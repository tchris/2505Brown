use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'lib/PHPMailer/src/PHPMailer.php';
require 'lib/PHPMailer/src/SMTP.php';
require 'lib/PHPMailer/src/Exception.php';

$pdfPath = __DIR__ . "/invoices/invoice_$order_id.pdf"; // from above or session

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'suffermedaily@gmail.com';
    $mail->Password = 'ujtrzuxvdpetgksu';  // your app password (no spaces)
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('suffermedaily@gmail.com', 'TRON Cycles');
    $mail->addAddress($customer_email, $customer_name);             // Customer
    $mail->addAddress('orders@troncycles.com', 'Fulfillment Team'); // Team

    $mail->isHTML(true);
    $mail->Subject = "Your TRON Cycles Order #$order_id";
    $mail->Body = "Thank you for your order. Your invoice is attached.";
    $mail->addAttachment($pdfPath);

    $mail->send();
} catch (Exception $e) {
    error_log("❌ Email Error: " . $mail->ErrorInfo);
}
