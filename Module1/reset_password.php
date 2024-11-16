<?php
session_start();
require 'connection.php'; // Include the database connection file
require 'PHPMailer/src/Exception.php';  
require 'PHPMailer/src/PHPMailer.php';  
require 'PHPMailer/src/SMTP.php';  

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Initialize the Database class and establish a connection
$database = new Database();
$pdo = $database->getConnection();

$message = ""; // Initialize message variable
$showPopup = false; // Track if the popup should be shown
$email = ""; // Retain the email input

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Check if the email exists in the database
    $stmt = $pdo->prepare("SELECT * FROM Customer WHERE Email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
        $resetToken = bin2hex(random_bytes(16));
        date_default_timezone_set('Africa/Nairobi');
        $expiryTime = date('Y-m-d H:i:s', strtotime('+5 hours'));

        $stmt = $pdo->prepare("UPDATE Customer SET ResetToken = :token, ResetTokenExpiry = :expiry WHERE CustomerID = :id");
        $stmt->execute([
            'token' => $resetToken,
            'expiry' => $expiryTime,
            'id' => $user['CustomerID']
        ]);

        $resetLink = "http://localhost/TimBuys/Module1/reset_password_form.php?token=" . $resetToken;
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'fatumamm99@gmail.com';
            $mail->Password   = 'mjdn nvnf qkcq iiyi';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;
            $mail->setFrom('fatumamm99@gmail.com', 'TimBuys');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "Click the following link to reset your password: <a href='$resetLink'>$resetLink</a>";

            $mail->send();
            $message = "A password reset link has been sent to your email address.";
            $showPopup = true; // Trigger the popup message
        } catch (Exception $e) {
            $message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            $showPopup = true;
        }
    } else {
        $message = "Email not found.";
        $showPopup = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | TimBuys</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        /* Background and Body */
        body {
            background: linear-gradient(135deg, #fceabb 0%, #f8b500 100%);
            background-attachment: fixed;
            color: #333;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            padding: 20px 0;
            height: 100vh;
            margin: 0;
        }

        /* Reset Password Wrapper */
        .reset-password-wrapper {
            max-width: 400px;
            width: 100%;
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            margin-top: 5%;
            margin-bottom:12%;
        }

        /* Title and Button Styling */
        .form-title {
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
            color: #f39c12;
            text-align: center;
        }
        .btn-primary {
            background-color: #f39c12;
            border-color: #f39c12;
        }
        .btn-primary:hover {
            background-color: #d87d02;
            border-color: #d87d02;
        }

        /* Input Field Styling */
        .form-label {
            color: #555555;
            font-weight: 500;
        }
        .form-control {
            border: 2px solid #e8e8e8;
            border-radius: 8px;
        }
        .form-control:focus {
            border-color: #f39c12;
            box-shadow: 0 0 0 0.2rem rgba(243, 156, 18, 0.25);
        }

        /* Responsive Styling */
        @media (max-width: 576px) {
            .form-title {
                font-size: 20px;
            }
            .reset-password-wrapper {
                width: 90%;
            }
        }
    </style>
</head>
<body>
<div class="reset-password-wrapper">
    <h2 class="form-title">Reset Password</h2>

    <!-- Display the popup message -->
    <?php if ($showPopup): ?>
        <div class="alert <?= strpos($message, 'successfully') !== false ? 'alert-success' : 'alert-danger' ?>" role="alert">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Enter your email address</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required />
        </div>
        <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
