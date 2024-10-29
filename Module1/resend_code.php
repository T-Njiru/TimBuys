<?php
session_start();

// Regenerate a new code and update the session
$code = rand(1000, 9999);
$_SESSION['verification_code'] = $code;
$_SESSION['code_expiry'] = time() + 300; // Code expires in 5 minutes

$email = $_SESSION['email'];
$subject = "Your new Verification Code";
$message = "Your new verification code is: $code";
$headers = "From: no-reply@timbuys.com";

if (mail($email, $subject, $message, $headers)) {
    echo "New code sent.";
    header("Location: verification_page.php");
    exit();
} else {
    echo "Failed to send the new code.";
}
?>
