<?php
session_start();

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include database connection (if storing messages in the database)
require 'connection.php';
$database = new Database();
$pdo = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if (empty($email) || empty($subject) || empty($message)) {
        displayMessage("Please fill in all fields.", "error");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        displayMessage("Invalid email address.", "error");
        exit;
    }

    $adminEmail = "fatmamarsa2@gmail.com"; // Replace with your admin email address
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'fatumamm99@gmail.com';
        $mail->Password = 'mjdn nvnf qkcq iiyi';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom($email, 'Contact Form User');
        $mail->addAddress($adminEmail);
        $mail->Subject = $subject;
        $mail->Body = "You have received a new message from your contact form.\n\n" .
                      "Email: $email\n\n" .
                      "Subject: $subject\n\n" .
                      "Message:\n$message";

        $mail->send();
        displayMessage("Message sent successfully. We will get back to you soon.", "success");
    } catch (Exception $e) {
        displayMessage("Failed to send message. Error: {$mail->ErrorInfo}", "error");
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (email, subject, message, created_at) VALUES (:email, :subject, :message, NOW())");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':message', $message);
        $stmt->execute();
    } catch (PDOException $e) {
        displayMessage("Failed to save the message to the database. Error: {$e->getMessage()}", "error");
    }
} else {
    displayMessage("Invalid request method.", "error");
}

function displayMessage($message, $type) {
    $textColor = "#000000"; // Black color
    $bgColor = $type === "success" ? "#f8f9fa" : "#f8d7da";
    $buttonColor = "#f8b500"; // Honey yellow color

    echo "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Message Status</title>
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css'>
        <style>
            body {
                background: linear-gradient(135deg, #fceabb 0%, #f8b500 100%);
                color: $textColor;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .message-box {
                background-color: $bgColor;
                padding: 30px;
                border-radius: 15px;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
                text-align: center;
                width: 400px;
            }
            .message-box h2 {
                font-size: 24px;
                margin-bottom: 20px;
            }
            .message-box p {
                font-size: 16px;
                margin-bottom: 20px;
            }
            .btn-custom {
                background-color: $buttonColor;
                color: #fff;
                border: none;
                padding: 10px 20px;
                border-radius: 8px;
                text-decoration: none;
                font-size: 16px;
            }
            .btn-custom:hover {
                background-color: darken($buttonColor, 10%);
            }
        </style>
    </head>
    <body>
        <div class='message-box'>
            <h2>Status</h2>
            <p>$message</p>
            <a href='home.html' class='btn-custom'>Go to Home</a>
        </div>
    </body>
    </html>
    ";
    exit;
}
?>
