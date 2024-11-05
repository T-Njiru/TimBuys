<?php include_once('../Module3/cart_functions.php'); 
require_once('checkoutfncs.php'); 
$SessionID=session_id();
$CustomerID="1";
$servername="localhost"; 
$username="root";
$password="";
$dbname="timbuys";

$conn = new mysqli($servername, $username, $password, $dbname);

$sql="DELETE from sessionid where CustomerID=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$CustomerID);
$stmt->execute();
// Close the statement and connection
$stmt->close();


$sql = "INSERT IGNORE INTO sessionid (SessionID,CustomerID) VALUES (?,?)";
$stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $SessionID,$CustomerID);
    $stmt->execute();
    $stmt->close();
    $conn->close();
$checkout=new checkout();
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tim Buys</title>
    <link rel="stylesheet" href="homepage.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body style="background-color:#daa520;">
    <div class="navbar">
        <div class="logo">TIM BUYS</div>
        <div class="search-bar">
            <input type="text" placeholder="Search products, brands and categories" />
            <button>Search</button>
        </div>
        <div class="nav-links">
            <div class="dropdown">
                <a href="#" class="account-link">
                    <i class="fas fa-user"></i> ACCOUNT
                </a>
                <div class="dropdown-content">
                    <button onclick="location.href='login.html';">Sign In</button>
                    <button onclick="location.href='../Module1.2/signin.html';">Sign Up</button>
                </div>
            </div>
            <a href="#">HELP</a>
            <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions">
                <a href="#"><i class="fas fa-shopping-cart"></i> CART</a>
            </button>
        </div>
    </div>
    <div class="container text-bg-light ms-2 d-flex flex-column my-2" style="width:55%;">
    <div class="border-bottom mx-0 px-0 mb-3 pb-2">
        <h5>Order Summary:</h5>
        <?php $checkout->items();?>
      </div>

      <div class="border-bottom mx-0  px-0 mb-3 pb-2">
        <h5>Select delivery method:</h5>
        <?php $checkout->Delivery();?>
      </div>

      <div class="border-bottom mx-0  px-0 mb-3 pb-2">
        <h5>Payment:</h5>
        <?php $checkout->area3();
        $checkout->beginAuth($checkout,$checkout->getAddress());?>
      </div>

    </div>
   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
