<?php
session_start();
require 'connection.php'; // Include the database connection file
require 'vendor/autoload.php'; // Include the PHPMailer autoload file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Initialize the Database class and establish a connection
$database = new Database();
$pdo = $database->getConnection();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $stmt = $pdo->prepare("SELECT * FROM Customer WHERE Email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generate a reset token and its expiry time
        $resetToken = bin2hex(random_bytes(16)); // Generate a secure random token
        $expiryTime = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token valid for 1 hour

        // Update the user record with the reset token and expiry
        $stmt = $pdo->prepare("UPDATE Customer SET ResetToken = :token, ResetTokenExpiry = :expiry WHERE CustomerID = :id");
        $stmt->execute([
            'token' => $resetToken,
            'expiry' => $expiryTime,
            'id' => $user['CustomerID']
        ]);

        // Create a reset link
        $resetLink = "http://localhost/TimBuys/Module1/reset_password_form.php?token=" . $resetToken;

        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();                                            // Set mailer to use SMTP
            $mail->Host       = 'smtp.gmail.com';                   // Specify your SMTP server (e.g., smtp.gmail.com)
            $mail->SMTPAuth   = true;                                 // Enable SMTP authentication
            $mail->Username   = 'fatumamm99@gmail.com';             // Your SMTP username (can be the sender's email)
            $mail->Password   = 'mjdn nvnf qkcq iiyi';                // Your SMTP password
            $mail->SMTPSecure = 'tls';       // Enable TLS encryption
            $mail->Port       = 587;                                     // TCP port to connect to

            //Recipients
            $mail->setFrom('fatumamm99@gmail.com', 'TimBuys'); // Set the sender email
            $mail->addAddress($email);                             // Add a recipient

            // Content
            $mail->isHTML(true);                                    // Set email format to HTML
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "Click the following link to reset your password: <a href='$resetLink'>$resetLink</a>";

            // Send the email
            $mail->send();
            echo "A password reset link has been sent to your email address.";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Email not found.";
    }
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | TimBuys</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Reset Password</div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Enter your email address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Reset Link</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
}
?>
