

 <?php 

 require('auth.php');

 class checkout{
    private $grandtotal;
    private $method;
    private $address;
    private $amount, $phonenumber;
    public function __construct(){
        $this->grandtotal=0;
        $this->address=null;
    }
    public function getAddress() {
        return $this->address;
    }

    // Getter for amount
    public function getAmount() {
        return $this->amount;
    }

    // Getter for phonenumber
    public function getPhonenumber() {
        return $this->phonenumber;
    }
public function items(){?>
    <table class="table table-bordered mx-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Product Name</th>
                                <th>Unit Price (KSh)</th>
                                <th>Quantity</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($_SESSION['cart'])) {
                                foreach ($_SESSION['cart'] as $product) {?>
                                    <tr>
                                        <td><?php echo $product['ProductName']; ?></td>
                                        <td><?php echo $product['Price']; ?></td>
                                        <td><?php echo $product['Quantity']; ?></td>
                                        <td><?php echo $product['Price'] * $product['Quantity']; ?></td>
                                    </tr>
                                    <?php $this->grandtotal += $product['Price'] * $product['Quantity'];
                                }
                                $this->amount=$this->grandtotal;
                            } else { 
                                echo "fail";
                            } ?>
                        </tbody>
                    </table><?php
  
}
public function Delivery(){?>
    <form action="<?php print basename($_SERVER['PHP_SELF']); ?>" method="POST">
    <label>
        <input type="radio" name="delivery" value="PickUp" id="option1" required> Pick up from School
    </label><br>
    <label>
        <input type="radio" name="delivery" class="mb-3" value="Delivery" id="option2" data-bs-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1" onclick="toggleInputRequirement(true)" required> Delivery
    </label><br>
    
    <div class="collapse multi-collapse" id="multiCollapseExample1">
        <div class="card card-body mb-2" style="background-color:#daa520; width:auto;">
            <label for="#AddressInput" class="me-auto p-0">Address: </label>
            <input class="form-control" id="AddressInput" placeholder="Street/Estate name, City" name="Address">
        </div>
    </div>
    <button type="submit" name="address" class="btn text-bg-dark" >Proceed to Payment</button>
</form>
<script>
 const option1 = document.getElementById('option1');
  const option2 = document.getElementById('option2');
  const additionalInput = document.getElementById('additionalInput');

  // Function to check and set 'required' dynamically
  function toggleInputRequirement() {
    if (option2.checked) {
      additionalInput.required = true; // Set as required if Option 2 is selected
    } else {
      additionalInput.required = false; // Remove required if Option 1 is selected
    }
  }

  // Add event listeners to radio buttons
  option1.addEventListener('change', toggleInputRequirement);
  option2.addEventListener('change', toggleInputRequirement);

  // Initial check in case form loads with Option 2 already selected
  toggleInputRequirement();
    </script>
<?php
}
public function area3(){
  
                    if (isset($_POST['address']) && isset($_POST['delivery']) ) {  
                        $this->method= $_POST['delivery'];
                        if(isset($_POST['Address'])&& $_POST['Address']!=null){
                            echo "Address set: ";
                            $holdaddress= $_SESSION['address']=$_POST['Address']; 
                            $this->address=$holdaddress;
                            echo $this->address; } 
                        else {
                            echo "Address set: ";
                            $holdaddress= $_SESSION['address']="Strathmore School"; 
                            $this->address=$holdaddress;
                            echo $this->address;
                         } ?>
                   
                        <!-- <span><?php //echo $_POST['delivery']; ?></span><br>  
                        <span><?php //echo ($_POST['delivery'] == "Delivery" && $_POST['Address']!= null) ? $_POST['Address'] : ""; ?></span><br>
                        <span><?php //echo $this->grandtotal; ?></span> -->
                        <form method="post" action="<?php print basename($_SERVER['PHP_SELF']);?>">
                            <label>Amount: <?php echo ($this->amount); ?></label>
                            <div class="input-group mb-3" style="width:50%;">
                                <span class="input-group-text" id="basic-addon1">+254</span>
                                <input type="tel" class="form-control" placeholder="712345678" maxlength="9" aria-label="Phonenumber" aria-describedby="basic-addon1" name="phonenumber" required>
                            </div>
                            <button type="submit" name="PayNum" class="btn text-bg-dark" >Lipa na Mpesa</button>
                        </form>
                    <?php } 
}
public function beginAuth($CheckoutObj,$bddress){
    if (isset($_POST['PayNum']) && isset($_POST['phonenumber']) ) {
        $number="254".$_POST['phonenumber'];  
        if(ctype_digit($number)){
        $this->phonenumber= $number;
            $auth=new auth();
            $auth->Payment($CheckoutObj);
            //print_r($_SESSION['cart']);
        
         } 
}
}
public function updateTable() {
    global $servername, $username, $password, $dbname;
    // Accessing the session cart and address
    if (!isset($_SESSION['cart']) || !isset($_SESSION['address'])) {
        echo "Session data not set!";
        return;
    }

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert into `orders` table
    $OrderID = $_SESSION["OrderID"] = "TBY" . rand(10000, 99999);
    $CustomerID = "1";
    $Address = $_SESSION['address'];

    $sql = "INSERT INTO orders (OrderID, CustomerID, Address) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sis", $OrderID, $CustomerID, $Address);
    $stmt->execute();
    $stmt->close();

    // Insert into `orderedproduct` table for each cart item
    foreach ($_SESSION['cart'] as $order) {
        $sql = "INSERT INTO orderedproduct (VendorProductID, OrderID, Quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isi", $order['ProductID'], $OrderID, $order['Quantity']);
        $stmt->execute();
        $stmt->close();
    }

    $conn->close();
    unset($_SESSION['cart']);
}

 }            
 
 
//  require "transactionfncs.php";
// require "constants.php";
// // Step 1: Get Access Token
//               $access_token = generateAccessToken($consumer_key, $consumer_secret);

//               // Step 2: Make C2B Payment Request
//               $response = c2bPaymentRequest($access_token, $shortcode, $amount, $msisdn, $billRefNumber);

//               // Print response
//               print_r($response);
 ?> 