<?php
session_start();
include_once 'connection.php'; // Include your connection file

// Create a new instance of the Database class
$database = new Database();
$pdo = $database->getConnection(); // Get the PDO connection

// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Fetch customer details
$customer_id = $_SESSION['customer_id'];
$stmt = $pdo->prepare("SELECT * FROM Customer WHERE CustomerID = :customer_id");
$stmt->bindParam(':customer_id', $customer_id);
$stmt->execute();
$customer = $stmt->fetch(PDO::FETCH_ASSOC);
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
    <nav>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="cart.php">Cart</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <div class="welcome-message">
        <i class="bi bi-person-circle profile-icon"></i> <!-- Profile icon from Bootstrap Icons -->
        Welcome, <?php echo htmlspecialchars($customer['FirstName']); ?>!
    </div>
</header>
    <!-- Categories -->
    <section class="categories">
        <p>CATEGORIES</p>
        <button>TVs & Accessories</button>
        <button>Phones & Tablets</button>
        <button>Appliances</button>
        <button>Health & Beauty</button>
        <button>Fashion</button>
        <button>Computing</button>
        <button>Other Categories</button>
    </section>

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
            <div class="most-viewed-item">
                <img
                    alt="Nike Air Jordan 1 Mid 'Canyon'"
                    height="200"
                    src="https://storage.googleapis.com/a1aa/image/WgtDTf9HcpxWcChaiRg41Mk1ZuTLzKo8gC0hyBqcYrbW4ZzJA.jpg"
                    width="200"
                />
                <p>Nike Air Jordan 1 Mid 'Canyon'</p>
                <p>Ksh 12,000</p> 
            </div>
            <div class="most-viewed-item">
                <img
                    alt="Chunky Gemstone Rings"
                    height="200"
                    src="https://storage.googleapis.com/a1aa/image/4p09IveCkmR8YSjQwCePCyAO3fKj3WEW1u8XwifzGBMFDPbOB.jpg"
                    width="200"
                />
                <p>Chunky Gemstone Rings</p>
                <p>Ksh 3,000</p> 
            </div>
            <div class="most-viewed-item">
                <img
                    alt="Apple AirPods"
                    height="200"
                    src="https://storage.googleapis.com/a1aa/image/92bFe8fIx2rN0EJ011yiaRAh8K25dxbj6lwSyWaOe4YUhnNnA.jpg"
                    width="200"
                />
                <p>Apple AirPods</p>
                <p>Ksh 15,000</p> 
            </div>
            <div class="most-viewed-item">
                <img
                    alt="Ultimate Perfume Stand"
                    height="200"
                    src="https://storage.googleapis.com/a1aa/image/18FfkAQnlqVjBKUH8MfVf13tAq29CPM100fUvXSeMzihGes5E.jpg"
                    width="200"
                />
                <p>Ultimate Perfume Stand</p>
                <p>Ksh 8,000</p> 
            </div>
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
        <div class="flash-sales-item">
            <img
                alt="Leather Backpack"
                height="200"
                src="https://storage.googleapis.com/a1aa/image/zRZ3QUqkA56H3y0lhWoJWvTZbUajNBiip9gyIX6yyf5s1N4C.jpg"
                width="200"
            />
            <p>Leather Backpack</p>
            <p>Ksh 4,500</p> 
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Tim Buys. All rights reserved.</p>
    </footer>
</body>
</html>
