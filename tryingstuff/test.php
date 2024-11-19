<?php
//include_once('../Module3/cart_functions.php');

// Data to send
$data = [
    "Body" => [
        "stkCallback" => [
            "MerchantRequestID" => "29115-34620561-1",
            "CheckoutRequestID" => "ws_CO_191220191020363925",
            "ResultCode" => 0,
            "ResultDesc" => "The service request is processed successfully.",
            "CallbackMetadata" => [
                "Item" => [
                    ["Name" => "Amount", "Value" => 1.00],
                    ["Name" => "MpesaReceiptNumber", "Value" => "NdfJ7RT61SV"],
                    ["Name" => "TransactionDate", "Value" => 20191219102115],
                    ["Name" => "PhoneNumber", "Value" => 25411111111],
                ]
            ]
        ]
    ]
];

// Convert the array to JSON
$jsonData = json_encode($data);

// Initialize cURL
$ch = curl_init('https://b2d6-197-232-135-168.ngrok-free.app/TimBuys/tryingstuff/result.php');

// Set cURL options
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute the request and handle potential errors
$response = curl_exec($ch);
// Check for JSON decoding errors
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "Error: Invalid JSON received from result.php.";
    exit;
}

// Handle the response based on the status
if (isset($responseData['status'])) {
    if ($responseData['status'] === 'success') {
        // Redirect to another page if success
        header("Location: viewOrders.php");
        exit;
    } else {
        // Display the error message
        echo "Error: " . $responseData['message'];
    }
} else {
    echo "Error: Invalid response format from result.php.";
}
curl_close($ch);
?>


// Output the response
//echo $response;