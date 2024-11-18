<?php
require 'C:/xampp/htdocs/TimBuys2/Module 4/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$OrderID = $_GET['OrderID'];
$status = $_GET['status'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "TimBuys";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update the order status
$sql = "UPDATE orderedproduct SET Status=? WHERE OrderID=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $status, $OrderID);
if ($stmt->execute()) {
    // Retrieve customer email
    $sql = "SELECT c.Email FROM orders o JOIN Customer c ON o.CustomerID = c.CustomerID WHERE o.OrderID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $OrderID);
    $stmt->execute();
    $stmt->bind_result($customerEmail);
    $stmt->fetch();

    // Send email notification using PHPMailer
    if ($customerEmail) {
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP();                                        // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';                        // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                                  // Enable SMTP authentication
            $mail->Username = 'huberttim55@gmail.com';              // SMTP username
            $mail->Password = 'hbpc oqqg jklq eqlk';                 // SMTP password
            $mail->SMTPSecure = 'tls';                               // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                       // TCP port to connect to

            //Recipients
            $mail->setFrom('huberttim55@gmail.com', 'Tim Buys');
            $mail->addAddress($customerEmail);                       // Add a recipient

            //Content
            $mail->isHTML(true);                                     // Set email format to HTML
            $mail->Subject = 'Order Status Update';
            $mail->Body    = "Your order with ID $OrderID has been updated to status: $status.";
            $mail->AltBody = "Your order with ID $OrderID has been updated to status: $status.";

            $mail->send();
            echo json_encode(['status' => 'success', 'message' => 'Order status updated and email sent']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
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
