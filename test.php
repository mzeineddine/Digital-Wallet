<!-- <?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$base = "http://localhost/Projects/Digital-Wallet/";
$message = "Verify your email";
$to = "mohammad.ziendeen50@gmail.com";
$subject = "Email Verification For Digital Wallet";
$from = 'mohammad.zeineddine50@gmail.com';
$body = 'Please Click On This link <a href="'.$base.'/email_verification.php">Verify.php?id=35'.'</a> to activate your account.';
$headers = "From: $from\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

if(mail($to, $subject, $body, $headers)){
    echo "Email sent successfully.";
} else {
    echo "Failed to send the email.";
    $error = error_get_last();
    if ($error) {
        echo "Error details: " . print_r($error, true);
    } else {
        echo "No specific error message was captured.";
    }
}
?> -->
<!-- <?php
$to = "mohammad.ziendeen50@gmail.com";
$subject = "My subject";
$txt = "Hello world!";
$headers = "From: mohammad.zeineddine50@gmail.com" . "\r\n";

if(mail($to,$subject,$txt,$headers)){
    echo "Email sent successfully.";
} else {
    echo "Failed to send the email.";
}
?> -->

<?php
$to = "mohammad.ziendeen50@gmail.com";
$subject = "My subject";
$txt = "Hello world!";
$headers = "From: mohammad.zeineddine50@gmail.com\r\n";
$headers .= "Reply-To: mohammad.zeineddine50@gmail.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

if (mail($to, $subject, $txt, $headers)) {
    echo "Email sent successfully.";
} else {
    echo "Failed to send the email.";
}
?>