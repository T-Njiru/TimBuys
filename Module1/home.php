<?php
//session_start();

require_once('../Module3/cart_functions.php');
require_once  '../tryingstuff/cartcontent.php';

$Cart=new cart();
$Specific=$Cart->load();

include_once 'connection.php'; // Include your connection file
require_once '../tryingstuff/global.php';

// Create a new instance of the Database class
$database = new Database();
$pdo = $database->getConnection(); // Get the PDO connection

// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

$SessionID=session_id();


$CustomerID=$_SESSION['customer_id'];



// Read the existing content of global.php
$globalFile = '../tryingstuff/global.php';
$globalContent = file_get_contents($globalFile);

// Check if the content was read successfully
if ($globalContent === false) {
    die("Error reading the global.php file.");
}

// Use a pattern to match the current definition of $CustomerID in global.php
$pattern = '/\$CustomerID\s*=\s*.*?;/';

// Replace the value with the current session value
$replacement = '$CustomerID = ' . var_export($CustomerID, true) . ';';

$updatedContent = preg_replace($pattern, $replacement, $globalContent);

// Check if the replacement was successful
if ($updatedContent === null) {
    die("Error updating the content of global.php.");
}

// Write the updated content back to global.php
if (file_put_contents($globalFile, $updatedContent) === false) {
    die("Error writing to global.php.");
}





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

// Fetch customer details
$customer_id = $_SESSION['customer_id'];
$stmt = $pdo->prepare("SELECT * FROM Customer WHERE CustomerID = :customer_id");
$stmt->bindParam(':customer_id', $customer_id);
$stmt->execute();
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

$productQuery = "
    SELECT p.ProductID, p.ProductName, p.ProductImage, vp.Price, vp.Quantity 
    FROM product p
    JOIN vendorproduct vp ON p.ProductID = vp.ProductID
    WHERE vp.Quantity > 0
    ORDER BY p.ProductID ASC
    LIMIT 4
";
$productStmt = $pdo->prepare($productQuery);
$productStmt->execute();
$products = $productStmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tim Buys - Home</title>
    <link rel="stylesheet" href="styles/homephp.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMYgDgrLqWtvU8cXWjl5u7v77IXE2KwGOMdM5g" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
      rel="stylesheet"
    />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Carme&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Oswald:wght@200..700&family=Radley:ital@0;1&display=swap"
      rel="stylesheet"
    />
    <style>
      body {
          font-family: Arial, sans-serif;
      }
      .welcome-message {
          position: absolute;
          top: 10px;
          right: 20px;
          font-size: 16px;
          color: white;
          margin-top: 30px;
          font-weight: bold;
          font-size: 25px;
      }
      footer {
        background-color: #000; /* Footer background color */
        padding: 20px 0;
        text-align: center;
        color: white; /* Text color for footer */
      }
      
      .social-icons {
        margin-top: 10px;
      }

      .social-icons a {
        color: #fff; /* Icon color */
        margin: 0 10px;
        text-decoration: none;
        font-size: 20px;
      }

      .social-icons a:hover {
        color: #da5200; /* Icon hover color */
      }
      .profile-icon {
          margin-right: 5px; /* Spacing between icon and text */
          vertical-align: middle; /* Align icon with text */
      }
      /* Add more styles as needed */
    </style>
</head>
<body>
<header>
    <div class="logo">TIM BUYS</div>
    <div class="search-bar">
        <form
          action="http://localhost/TimBuys/Module1/search_results.php"
          method="GET"
        >
          <input
            type="text"
            name="query"
            placeholder="Search products, brands, and categories"
            required
          />
          <select name="category">
            <option value="">All Categories</option>
            <option value="electronics">Electronics</option>
            <option value="fashion">Fashion</option>
            <option value="books">Books</option>
            <option value="furniture">Furniture</option>
          </select>
          <button type="submit">Search</button>
        </form>
      </div>
    <nav>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="../Module1/product_listing.php">Products</a></li>
            <li><button class="btn " type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions"><a href="#">Cart</a></button></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
         <!--Cart-->
   <?php     
  $Cart->cart($Specific);
  ?>
    </nav>
   
    <div class="welcome-message">
        <i class="bi bi-person-circle profile-icon"></i> <!-- Profile icon from Bootstrap Icons -->
        Welcome, <?php echo htmlspecialchars($customer['FirstName']); ?>!
    </div>
</header>
    

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Let us take care of all your shopping needs!</h1>
            <button>SHOP NOW</button>
        </div>
    </section>

    <div class="banner">
        <h1>Elevate your style with 20% off</h1>
        <p>Shop these essentials to complete your look</p>
        <button>SHOP NOW</button>
    </div>
    <div class="most-viewed">
    <h2>Most Viewed Items</h2>
    <div class="most-viewed-items">
        <?php foreach ($products as $product): ?>
            <div class="most-viewed-item">
                <img alt="<?php echo htmlspecialchars($product['ProductName']); ?>" height="200" src="<?php echo htmlspecialchars($product['ProductImage']); ?>" width="200" />
                <p><?php echo htmlspecialchars($product['ProductName']); ?></p>
                <p>Ksh <?php echo htmlspecialchars($product['Price']); ?></p>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
    <div class="flash-sales">
        <h2>Flash Sales up to 50% off</h2>
        <p class="time-left">Time Left: 4d : 12hrs</p>
        <a href="#" style="color: white"> See all </a>
    </div>
    <div class="flash-sales-items">
        <div class="flash-sales-item">
            <img
                alt="All The Light We Cannot See"
                height="200"
                src="https://storage.googleapis.com/a1aa/image/fbiP5RrQBk1Vc6KnnFSYDHvXnFuj2RV4vHlqXF1lcGevwzmTA.jpg"
                width="200"
            />
            <p>All The Light We Cannot See</p>
            <p>Ksh 1,000</p> 
        </div>
        <div class="flash-sales-item">
            <img
                alt="Writing Set for Journaling"
                height="200"
                src="https://storage.googleapis.com/a1aa/image/O6Q6MlpFmi4sL1ugp9veZ1VfjUyAvzYeOVCScHdeKVsDDPbOB.jpg"
                width="200"
            />
            <p>Writing Set for Journaling</p>
            <p>Ksh 1,500</p> 
        </div>
        
    </div>

    <footer>
      <p>© 2024 TimBuys. All rights reserved.</p>
    
      <div class="social-icons">
        <a href="#"><i class="fab fa-facebook-f"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-linkedin-in"></i></a>
      </div>
    </footer>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</html>
