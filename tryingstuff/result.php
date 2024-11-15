<?php  // Start session at the top of the script
// Log the incoming request time
include_once('checkoutfncs.php');
require_once 'global.php';
//echo $CustomerID."Separate<br>";

$input = file_get_contents('php://input');
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
    } else {
        echo "Error updating record: " . $stmt->error;
    }

   
    
}
    
