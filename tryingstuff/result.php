<?php  // Start session at the top of the script
// Log the incoming request time
include_once('checkoutfncs.php');
require_once 'global.php';
require 'C:\xampp\htdocs\TimBuys\tryingstuff\vendor\autoload.php'; // Adjust the path as necessary

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
//echo $CustomerID."Separate<br>";


$input = file_get_contents('php://input');
if ($input==null){
    echo json_encode(['status' => 'received']);
}
file_put_contents('log.txt', "Raw input: " . $input . "\n", FILE_APPEND);

$servername="localhost"; 
$username="root";
$password="";
$dbname="timbuys";

$conn = new mysqli($servername, $username, $password, $dbname);

// Prepare and execute the SQL statement to retrieve the session ID
$sql = "SELECT SessionID FROM sessionid where CustomerID=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$CustomerID);
$stmt->execute();
$stmt->bind_result($sessionId);
$stmt->fetch();

// Close the statement and connection
$stmt->close();
$conn->close();
if ($sessionId) {
    echo $sessionId;
   
    session_id($sessionId); // Set the session ID
    session_start(); // Resume the session
   echo $_SESSION['address'];
}




// Decode JSON data into an associative array
$transactionData = json_decode($input, true);

// Extract required values from JSON, if decoding is successful
if (json_last_error() === JSON_ERROR_NONE) {
    $merchantRequestID = $transactionData['Body']['stkCallback']['MerchantRequestID'];
    $checkoutRequestID = $transactionData['Body']['stkCallback']['CheckoutRequestID'];
    $resultCode = $transactionData['Body']['stkCallback']['ResultCode'];
    $resultDesc = $transactionData['Body']['stkCallback']['ResultDesc'];
    $callbackItems = $transactionData['Body']['stkCallback']['CallbackMetadata']['Item'];

    // Handling error case
    if ($resultCode !== 0) {
       
        deleteOrderAndProducts();
    } else {
        
        // Success: Extract and process transaction data
        processTransaction($callbackItems);
    }
}


function deleteOrderAndProducts() {
    global $servername, $username, $password, $dbname;
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (isset($_SESSION['OrderID'])) {
        $orderId = $_SESSION['OrderID'];
        
        $deleteOrderedProductsSql = "DELETE FROM orderedproduct WHERE OrderID=?";
        $stmt = $conn->prepare($deleteOrderedProductsSql);
        $stmt->bind_param("s", $orderId);
        $stmt->execute();
        $stmt->close();

        $deleteOrdersSql = "DELETE FROM orders WHERE OrderID=?";
        $stmt = $conn->prepare($deleteOrdersSql);
        $stmt->bind_param("s", $orderId);
        $stmt->execute();
        $stmt->close();

        
    }

    $conn->close();
}

function processTransaction($callbackItems) {
    global $servername, $username, $password, $dbname;

    $amount = null;
    $receiptNumber = null;
    $transactionDate = null;
    $phoneNumber = null;

    foreach ($callbackItems as $item) {
        switch ($item['Name']) {
            case 'Amount':
                $amount = $item['Value'];
                break;
            case 'MpesaReceiptNumber':
                $receiptNumber = $item['Value'];
                break;
            case 'TransactionDate':
                $transactionDate = $item['Value'];
                break;
            case 'PhoneNumber':
                $phoneNumber = $item['Value'];
                break;
        }
    }

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO transaction (ReceiptID, Phonenumber, Amount, TransactionDate) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssid", $receiptNumber, $phoneNumber, $amount, $transactionDate);

    if ($stmt->execute()) {
        
        $checkout = new checkout();
        $checkout->updateTable();
        if (isset($_SESSION['OrderID'])) {
            $orderId = $_SESSION['OrderID'];
            $updateOrderStatusSql = "UPDATE orderedproduct SET Status='Order Processed' WHERE OrderID=?";
            $stmt = $conn->prepare($updateOrderStatusSql);
            $stmt->bind_param("s", $orderId);
            $stmt->execute();
            $stmt->close();

            // Send email notification using PHPMailer
            $sql = "SELECT c.Email FROM orders o JOIN Customer c ON o.CustomerID = c.CustomerID WHERE o.OrderID=?";
            $stmt = $conn->prepare($updateOrderStatusSql);
            $stmt->bind_param("s", $orderId);
            $stmt->execute();
            $result=$stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $customerEmail = $row['Email'];
                sendEmailNotification($customerEmail, $orderId, 'Processed');
                unset($_SESSION['cart']);
            }
        }

    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $conn->close();
}

function sendEmailNotification($to, $orderId, $status) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;
        $mail->Username = 'huberttim55@gmail.com'; // SMTP username
        $mail->Password = 'hbpc oqqg jklq eqlk';   // SMTP password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('huberttim55@gmail.com', 'Tim Buys');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = 'Order Status Update';
        $mail->Body    = "Your order with ID $orderId has been updated to status: $status.";
        $mail->AltBody = "Your order with ID $orderId has been updated to status: $status.";

        $mail->send();
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
    }
}
?>
