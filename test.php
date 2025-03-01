<?php
$to = "mohammad.ziendeen50@gmail.com";
$subject = "Email Verification";
$message = "Click on the link to verify your email";
$headers = "From: mohammad.zeineddine50@gmail.com\r\n";

if (mail($to, $subject, $message, $headers)) {
    echo "Email sent successfully!";
} else {
    echo "Failed to send email.";
}
?>
