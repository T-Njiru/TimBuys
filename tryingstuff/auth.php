<?php
require_once "transactionfncs.php";


 class auth{
   private $consumer_key = 'KlF3DHj5BPzZgjFSlGNYcE8iM28HKfdZaiZ69zpD53G6EJNF';
private $consumer_secret = 'uTGTiQJnLrSsOOED5OwJQWSWmk3hArwhk6GA47oj6YY2OGF9mmWDIXmNSKhn7tIA';
private $shortcode = '174379';

private $callbackUrl = 'https://b2d6-197-232-135-168.ngrok-free.app/TimBuys/tryingstuff/result.php'; // Replace with your callback URL
private $accountReference = 'TimBuys Checkout'; // Replace with reference
private $transactionDesc = 'Payment for Items from Timbuys'; // Replace with transaction description
function Payment($CheckoutObj){
  require 'global.php';
        $amount=$CheckoutObj->getAmount();
        $phoneNumber= $CheckoutObj->getPhonenumber();
        $Transaction= new Transaction();
        $access_token = $Transaction->generateAccessToken($this->consumer_key, $this->consumer_secret);

        //echo "Works";
    // Step 2: Initiate STK Push
        $response = $Transaction->stkPushRequest($access_token, $this->shortcode, $amount, $phoneNumber, $this->callbackUrl, $this->accountReference, $this->transactionDesc);
       echo "Processing order...";
}

}
?>