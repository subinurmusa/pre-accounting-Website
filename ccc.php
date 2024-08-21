<?php
$to = 'safiyehalike@gmail.com';
$subject = 'Test Email';
$message = 'This is a test email.';
$headers = 'From: noreply@example.com' . "\r\n" .
           'Reply-To: noreply@example.com' . "\r\n" .
           'X-Mailer: PHP/' . phpversion();

if (mail($to, $subject, $message, $headers)) {
    echo 'Email sent successfully.';
} else {
    echo 'Email sending failed.';
}
?>
