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
        } catch (Exception $e) {
            $message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $message = "Email not found.";
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
        body {
            background-color: #f8f9fa; /* Light background for better contrast */
        }
        .container {
            margin-top: 10%;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .card-header, .btn-primary {
            background-color: #daa520; 
            color: #fff; 
        }
        .btn-primary {
            transition: background-color 0.3s ease;
            border: none; /* Remove default border */
        }
        .btn-primary:hover {
            background-color: #b59416; /* Darker shade on hover */
        }
        .alert {
            display: none; 
            margin-top: 20px; 
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">Reset Password</div>
                <div class="card-body">
                    <div id="message" class="alert alert-success" role="alert"><?= $message ?></div>
                    <form method="POST" onsubmit="showMessage();">
                        <div class="mb-3">
                            <label for="email" class="form-label">Enter your email address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function showMessage() {
        const message = "<?= $message ?>";
        if (message) {
            document.getElementById('message').style.display = 'block';
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
