<?php
session_start();
include 'connection.php'; // Include your database connection file

// Create an instance of the Database class
$database = new Database();
$conn = $database->getConnection(); // Get the database connection

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
        try {
            // Prepare the SQL statement to insert the email into the newsletter_subscribers table
            $stmt = $conn->prepare("INSERT INTO newsletter_subscribers (email) VALUES (:email)");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Create a new PHPMailer instance
            $mail = new PHPMailer(true);

            // Server settings
            $mail->isSMTP();                                            // Set mailer to use SMTP
            $mail->Host       = 'smtp.gmail.com';                       // Specify your SMTP server (e.g., smtp.gmail.com)
            $mail->SMTPAuth   = true;                                    // Enable SMTP authentication
            $mail->Username   = 'fatumamm99@gmail.com';                 // Your SMTP username (can be the sender's email)
            $mail->Password   = 'mjdn nvnf qkcq iiyi';                   // Your SMTP password
            $mail->SMTPSecure = 'tls';                                   // Enable TLS encryption
            $mail->Port       = 587;                                     // TCP port for TLS

            // Recipients
            $mail->setFrom('fatumamm99@gmail.com', 'TimBuys');   // Use your "from" email
            $mail->addAddress($email);                                // Add the recipient's email

            // Content
            $mail->isHTML(true);                                      // Set email format to HTML
            $mail->Subject = 'Thank you for subscribing to TimBuys!';
            $mail->Body    = "
                <h3>Thank you for subscribing to TimBuys!</h3>
                <p>We're thrilled to have you on board. You'll be the first to hear about our latest deals, discounts, and exclusive offers.</p>
                <p>Stay tuned for amazing updates!</p>
                <p>Best regards,</p>
                <p><strong>TimBuys Team</strong></p>
            ";

            // Send the email
            $mail->send();

            // Provide feedback and redirect
            echo "<script>alert('Thank you for subscribing!.'); window.location.href='home.html';</script>";
        } catch (PDOException $e) {
            // Handle database error (duplicate entry or other issues)
            if ($e->getCode() == 23000) { // Duplicate entry error code
                echo "<script>alert('This email is already subscribed.'); window.location.href='home.html';</script>";
            } else {
                echo "<script>alert('Database error: " . addslashes($e->getMessage()) . "'); window.location.href='home.html';</script>";
            }
        } catch (Exception $e) {
            // Handle mailer error
            echo "<script>alert('Mailer Error: " . $mail->ErrorInfo . "'); window.location.href='home.html';</script>";
        }
    } else {
        echo "<script>alert('Invalid email format. Please try again.'); window.location.href='home.html';</script>";
    }
}
?>
