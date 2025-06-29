<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'lib/phpmailer/src/Exception.php';
require 'lib/phpmailer/src/PHPMailer.php';
require 'lib/phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    // Gmail SMTP setup
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'tronbikectu@gmail.com';              // your Gmail address
    $mail->Password = 'ctua elkf teyt wtek';                // your Gmail App Password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Sender & recipient
    $mail->setFrom('tronbikectu@gmail.com', 'TRON Cycles');
    $mail->addAddress('John.L.Szpyrka@outlook.com', 'John Szpyrka');

    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'TRON Cycles – Test Email';
    $mail->Body    = '<p>This is a <strong>test email</strong> sent from PHPMailer using Gmail SMTP.</p>';
    $mail->AltBody = 'This is a test email sent from PHPMailer using Gmail SMTP.';

    $mail->send();
    echo '✅ Email has been sent successfully.';
} catch (Exception $e) {
    echo '❌ Email could not be sent. Mailer Error: ', $mail->ErrorInfo;
}
