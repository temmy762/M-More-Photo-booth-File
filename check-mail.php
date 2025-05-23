<?php
// Check mail functionality
echo "<h1>PHP Mail Function Check</h1>";
echo "<p>PHP Mail Function: " . (function_exists('mail') ? '<span style="color:green">Available</span>' : '<span style="color:red">Not Available</span>') . "</p>";

// Display mail configuration
echo "<h2>Mail Configuration</h2>";
echo "<p>SMTP: " . ini_get('SMTP') . "</p>";
echo "<p>smtp_port: " . ini_get('smtp_port') . "</p>";
echo "<p>sendmail_from: " . ini_get('sendmail_from') . "</p>";
echo "<p>mail.add_x_header: " . ini_get('mail.add_x_header') . "</p>";

// Try to send a test email
$to = "test@example.com";
$subject = "PHP Mail Test";
$message = "This is a test email sent from the PHP mail() function at " . date('Y-m-d H:i:s');
$headers = "From: noreply@memoriesandmorephotobooths.com\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

echo "<h2>Test Email Result</h2>";

// Attempt to send mail and report result
$result = mail($to, $subject, $message, $headers);
if ($result) {
    echo "<p style='color: green;'>Mail function returned TRUE. The email was accepted for delivery.</p>";
    echo "<p>Note: This does not guarantee email delivery, only that PHP successfully passed it to the mail system.</p>";
} else {
    echo "<p style='color: red;'>Mail function returned FALSE. There was a problem sending the email.</p>";
    
    // Check for errors
    $error = error_get_last();
    if ($error !== null) {
        echo "<p>Error: " . htmlspecialchars($error['message']) . "</p>";
    }
}

// Show PHP info for mail settings
echo "<h2>PHP Info</h2>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>OS: " . PHP_OS . "</p>";
echo "<p>Loaded Extensions: " . implode(', ', get_loaded_extensions()) . "</p>";
?>
