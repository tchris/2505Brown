<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'lib/PHPMailer/src/Exception.php';
require 'lib/PHPMailer/src/PHPMailer.php';
require 'lib/PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    // Gmail SMTP setup
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'suffermedaily@gmail.com';             // your Gmail address
    $mail->Password = 'ujtrzuxvdpetgksu';                    // Gmail App Password (no spaces)
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Sender & recipient
    $mail->setFrom('suffermedaily@gmail.com', 'TRON Cycles');
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
