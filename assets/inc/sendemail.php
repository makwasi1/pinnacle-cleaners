<?php

// Define constants
define("RECIPIENT_NAME", "Admin");
define("RECIPIENT_EMAIL", "info@pinnaclecleaners.org");

// Function to sanitize and validate input
function sanitize_input($input, $pattern) {
    return preg_replace($pattern, "", $input);
}

// Function to validate email
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Read the form values
$success = false;
$name = isset($_POST['name']) ? sanitize_input($_POST['name'], "/[^\.\-\' a-zA-Z0-9]/") : "";
$senderEmail = isset($_POST['email']) ? sanitize_input($_POST['email'], "/[^\.\-\_\@a-zA-Z0-9]/") : "";
$phone = isset($_POST['phone']) ? sanitize_input($_POST['phone'], "/[^\.\-\_0-9]/") : "";
$services = isset($_POST['services']) ? sanitize_input($_POST['services'], "/[^\.\-\_ a-zA-Z0-9]/") : "";
$subject = isset($_POST['subject']) ? sanitize_input($_POST['subject'], "/[^\.\-\_ a-zA-Z0-9]/") : "";
$address = isset($_POST['address']) ? sanitize_input($_POST['address'], "/[^\.\-\_ a-zA-Z0-9]/") : "";
$website = isset($_POST['website']) ? sanitize_input($_POST['website'], "/[^\.\-\_\:\/a-zA-Z0-9]/") : "";
$message = isset($_POST['message']) ? sanitize_input($_POST['message'], "/(From:|To:|BCC:|CC:|Subject:|Content-Type:)/") : "";

// Validate email
if (!validate_email($senderEmail)) {
    echo "<div class='inner error'><p class='error'>Invalid email address. Please try again.</p></div><!-- /.inner -->";
    exit;
}

$mail_subject = 'A contact request sent by ' . $name;

$body = 'Name: ' . $name . "\r\n";
$body .= 'Email: ' . $senderEmail . "\r\n";

if ($phone) {
    $body .= 'Phone: ' . $phone . "\r\n";
}
if ($services) {
    $body .= 'Services: ' . $services . "\r\n";
}
if ($subject) {
    $body .= 'Subject: ' . $subject . "\r\n";
}
if ($address) {
    $body .= 'Address: ' . $address . "\r\n";
}
if ($website) {
    $body .= 'Website: ' . $website . "\r\n";
}

$body .= 'Message: ' . "\r\n" . $message;

// If all values exist, send the email
if ($name && $senderEmail && $message) {
    $recipient = RECIPIENT_NAME . " <" . RECIPIENT_EMAIL . ">";
    $headers = "From: " . $name . " <" . $senderEmail . ">\r\n";
    $headers .= "Content-Type: text/plain; charset=utf-8\r\n";
    
    if (mail($recipient, $mail_subject, $body, $headers)) {
        echo "<div class='inner success'><p class='success'>Thanks for contacting us. We will contact you ASAP!</p></div><!-- /.inner -->";
    } else {
        echo "<div class='inner error'><p class='error'>Failed to send your message. Please try again later.</p></div><!-- /.inner -->";
    }
} else {
    echo "<div class='inner error'><p class='error'>Please fill in all required fields.</p></div><!-- /.inner -->";
}

?>
