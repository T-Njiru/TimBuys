<?php
require_once "transactionfncs.php";

 class auth{
   private $consumer_key = '';
private $consumer_secret = '';
private $shortcode = '174379';

private $callbackUrl = 'https://6d93-197-232-135-168.ngrok-free.app/TimBuys/tryingstuff/result.php'; // Replace with your callback URL
private $accountReference = 'TimBuys Checkout'; // Replace with reference
private $transactionDesc = 'Payment for Items from Timbuys'; // Replace with transaction description
function Payment($CheckoutObj){
        $amount=$CheckoutObj->getAmount();
        $phoneNumber= $CheckoutObj->getPhonenumber();

        $access_token = generateAccessToken($this->consumer_key, $this->consumer_secret);

        //echo "Works";
    // Step 2: Initiate STK Push
        $response = stkPushRequest($access_token, $this->shortcode, $amount, $phoneNumber, $this->callbackUrl, $this->accountReference, $this->transactionDesc);
        //echo "Done as well";
   
}
}
?>