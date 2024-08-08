<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP(); // Set mailer to use SMTP
    $mail->Host       = 'smtp.gmail.com'; // Specify MailerSend SMTP server
    $mail->SMTPAuth   = true; // Enable SMTP authentication
    $mail->Username   = 'satishkarki1000@gmail.com'; // SMTP username
    $mail->Password   = 'kybr zddx pggd wdlq'; // SMTP password (App Password if 2FA is enabled)
    $mail->SMTPSecure = 'ssl'; // Enable SSL encryption
    $mail->Port       = 465; // TCP port to connect to

    // Recipients
    $mail->setFrom('satishkarki1000@gmail.com', 'Your Name'); // Use the same email as Username
    $mail->addAddress('satishkarki761@gmail.com'); // Add a recipient

    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';

    // Debugging
    $mail->SMTPDebug = 2; // Enable verbose debug output

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
