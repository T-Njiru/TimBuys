<?php
session_start();

require 'PHPMailer/src/Exception.php';  
require 'PHPMailer/src/PHPMailer.php';  
require 'PHPMailer/src/SMTP.php';  

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Validate email
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Generate a random 4-digit verification code
        $code = rand(1000, 9999);
        $_SESSION['verification_code'] = $code;
        $_SESSION['code_expiry'] = time() + 300; // Code expires in 5 minutes
        $_SESSION['email'] = $email; // Store the email in session

        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);
        
        // Enable verbose debug output
        $mail->SMTPDebug = 2; // 1 = errors and messages, 2 = messages only

        try {
            //Server settings
            $mail->isSMTP();                                            // Set mailer to use SMTP
            $mail->Host       = 'smtp.gmail.com';                   // Specify your SMTP server (e.g., smtp.gmail.com)
            $mail->SMTPAuth   = true;                                 // Enable SMTP authentication
            $mail->Username   = 'fatumamm99@gmail.com';             // Your SMTP username (can be the sender's email)
            $mail->Password   = 'mjdn nvnf qkcq iiyi';                // Your SMTP password
            $mail->SMTPSecure = 'tls';       // Enable TLS encryption
            $mail->Port       = 587;                                  // TCP port for TLS

            // Recipients
            $mail->setFrom('fatumamm99@gmail.com', 'Fatma');   // Use your "from" email
            $mail->addAddress($email);                                // Add the recipient's email

            // Content
            $mail->isHTML(true);                                      // Set email format to HTML
            $mail->Subject = 'Your Verification Code';
            $mail->Body    = "Your verification code is: <strong>$code</strong>";

            $mail->send();
            echo 'Verification code sent to ' . $email;
            header("Location: vendorverpage.php"); // Redirect to the verification page
            exit();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Invalid email format.";
    }
}
?>
