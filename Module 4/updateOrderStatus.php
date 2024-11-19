<?php
include_once('checkoutfncs.php');
require_once 'global.php';
require 'vendor/autoload.php'; // Adjust the path as necessary
require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src//SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "TimBuys";

$OrderID = isset($_GET['OrderID']) ? $_GET['OrderID'] : null;
$newStatus = isset($_GET['status']) ? $_GET['status'] : null;

if (!$OrderID || !$newStatus) {
    echo json_encode(['status' => 'error', 'message' => 'OrderID or status missing']);
    exit;
}

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update the status
$sql = "UPDATE orderedproduct SET Status=? WHERE OrderID=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $newStatus, $OrderID);
if ($stmt->execute()) {
    // Get customer email
    $sql = "SELECT c.Email FROM orders o JOIN Customer c ON o.CustomerID = c.CustomerID WHERE o.OrderID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $OrderID);
    $stmt->execute();
    $stmt->bind_result($customerEmail);
    $stmt->fetch();

    if ($customerEmail) {
        // Send email notification
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'huberttim55@gmail.com'; // SMTP username
            $mail->Password = 'lyhv kvfv ngnn zzdl';   // SMTP password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 465;

            $mail->setFrom('ahuberttim55@gmail.com', 'Tim Buys');
            $mail->addAddress($customerEmail);

            $mail->isHTML(true);
            $mail->Subject = 'Order Status Update';
            $mail->Body    = "Your order with ID $OrderID has been updated to status: $newStatus.";

            $mail->send();
            echo json_encode(['status' => 'success', 'message' => 'Order updated and email sent']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Mailer Error: ' . $mail->ErrorInfo]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Customer email not found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update order status']);
}

$stmt->close();
$conn->close();
?>
