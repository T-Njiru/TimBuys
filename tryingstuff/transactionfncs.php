<?php

function generateAccessToken($consumer_key, $consumer_secret) {
    $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

    // Encode key and secret
    $credentials = base64_encode($consumer_key . ':' . $consumer_secret);
    //echo "Encoded credentials: " . $credentials . "\n"; // Debug line
    
    // cURL initialization
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Authorization: Basic ' . $credentials]);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL and check for success
    $response = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    // Check the response for access token
    if ($status == 200) {
        $json = json_decode($response);
        if (isset($json->access_token)) {
            return $json->access_token;
        } else {
            die("Access token not found in response: " . $response);
        }
    } else {
        die("Failed to get access token: HTTP Status $status - " . $response);
    }
}

function stkPushRequest($access_token, $shortcode, $amount, $phoneNumber, $callbackUrl, $accountReference, $transactionDesc) {
    $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

    // Generate the password
    $timestamp = date('YmdHis');
    $passkey = ''; // Replace with your actual passkey
    $password = base64_encode($shortcode . $passkey . $timestamp);

    // Prepare the data
    $data = [
        'BusinessShortCode' => $shortcode,
        'Password' => $password,
        'Timestamp' => $timestamp,
        'TransactionType' => 'CustomerPayBillOnline',
        'Amount' => $amount,
        'PartyA' => "254115263790", // Initiator's phone number
        'PartyB' => $shortcode,
        'PhoneNumber' => $phoneNumber,
        'CallBackURL' => $callbackUrl,
        'AccountReference' => $accountReference,
        'TransactionDesc' => $transactionDesc
    ];

    // Setup headers
    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $access_token
    ];

    // Initialize cURL
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

    // Execute cURL and handle response
    $response = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    // Check for successful response
    if ($status == 200) {
        return json_decode($response);
    } else {
        die("STK Push Request failed: HTTP Status $status - " . $response);
    }
}



// $consumer_key = 'KlF3DHj5BPzZgjFSlGNYcE8iM28HKfdZaiZ69zpD53G6EJNF';
// $consumer_secret = 'uTGTiQJnLrSsOOED5OwJQWSWmk3hArwhk6GA47oj6YY2OGF9mmWDIXmNSKhn7tIA';
// $shortcode = '174379';
// $amount = 1; // Replace with desired amount
// $phoneNumber = '254115263790'; // Replace with customer's phone number
// $callbackUrl = 'https://7a43-197-232-135-168.ngrok-free.app/TimBuys/tryingstuff/result.php'; // Replace with your callback URL
// $accountReference = 'Timbuys'; // Replace with reference
// $transactionDesc = 'Jasons custom charge'; // Replace with transaction description

// // Step 1: Get Access Token
// $access_token = generateAccessToken($consumer_key, $consumer_secret);
// echo "Access Token: " . $access_token . "\n"; // Debug line

// // Step 2: Initiate STK Push
// $response = stkPushRequest($access_token, $shortcode, $amount, $phoneNumber, $callbackUrl, $accountReference, $transactionDesc);
// // file_put_contents('stk_push_response.txt', print_r($response, true));
// // Output the response for verification
// // print_r($response);

?>
