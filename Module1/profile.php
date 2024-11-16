<?php
session_start();
require 'connection.php'; // Include your connection file

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

// Check if customer data was retrieved
if (!$customer) {
    echo "Customer not found.";
    exit();
}

// Fetch the customer's address
$stmt = $pdo->prepare("
    SELECT a.HouseNo, a.Street, a.Area, c.CountyName, ci.CityName
    FROM Address a
    JOIN County c ON a.CountyID = c.CountyID
    JOIN City ci ON a.CityID = ci.CityID
    WHERE a.CustomerID = :customer_id
");
$stmt->execute([':customer_id' => $customer_id]);
$address = $stmt->fetch(PDO::FETCH_ASSOC);

// Prepare default shipping address
$default_shipping_address = $address
    ? "{$address['HouseNo']}, {$address['Street']}, {$address['Area']}, {$address['CityName']}, {$address['CountyName']}"
    : "No default shipping address available";

$store_credit_balance = "KSh 0"; // Placeholder for store credit balance
?>
<!DOCTYPE html>
<html>
<head>
    <title>Your Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            background-color: black;
            border-bottom: 1px solid #ddd;
        }
        .header .logo {
            font-size: 24px;
            font-weight: bold;
            color: white;
        }
        .header .search-bar {
            display: flex;
            align-items: center;
            flex-grow: 1;
            margin: 0 20px;
        }
        .header .search-bar input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px 0 0 4px;
        }
        .header .search-bar button {
            padding: 8px 16px;
            border: none;
            background-color: #ff9900;
            color: white;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
        }
        .header .user-info {
            display: flex;
            align-items: center;
        }
        .header .user-info i {
            margin-right: 10px;
        }
        .sidebar {
            width: 250px;
            background-color: white;
            padding: 20px;
            border-right: 1px solid #ddd;
            position: fixed;
            height: 100%;
        }
        .sidebar a {
            display: flex;
            align-items: center;
            padding: 10px 0;
            color: black;
            text-decoration: none;
            font-size: 16px;
        }
        .sidebar a i {
            margin-right: 10px;
        }
        .sidebar a:hover {
            background-color: #f5f5f5;
        }
        .sidebar .logout {
            color: #ff9900;
            font-weight: bold;
        }
        .content {
            margin-left: 270px;
            padding: 20px;
        }
        .content .profile-title {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #daa520;
            margin-bottom: 20px;
        }
        .content .profile-title i {
            font-size: 30px;
            margin-right: 10px;
        }
        .content h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .content .card {
            background-color: white;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        .content .card i {
            font-size: 30px;
            color: #daa520;
            margin-right: 15px;
        }
        .content .card h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .content .card p {
            font-size: 16px;
            margin: 0;
        }
        .content .card a {
            color: #ff9900;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">TIMBUYS</div>
        <div class="search-bar">
            <input type="text" placeholder="Search products, brands and categories">
            <button>SEARCH</button>
        </div>
        <div class="user-info">
            <i class="fas fa-user"></i>
            <span>Hi, <?php echo htmlspecialchars($customer['FirstName']); ?></span>
            <i class="fas fa-question-circle"></i>
            <i class="fas fa-shopping-cart"></i>
        </div>
    </div>
    <div class="sidebar">
        <a href="#"><i class="fas fa-user"></i> My TIMBUYS Account</a>
        <a href="#"><i class="fas fa-box"></i> Orders</a>
        <a href="#"><i class="fas fa-envelope"></i> Inbox</a>
        <a href="#"><i class="fas fa-heart"></i> Saved Items</a>
        <a href="vendor.html"><i class="fas fa-store"></i> Register To Be A Seller</a>
        <a href="account_management.php"><i class="fas fa-cog"></i> Account Management</a>
        <a href="#"><i class="fas fa-address-book"></i> Address Book</a>
        <a href="#"><i class="fas fa-times-circle"></i> Close Account</a>
        <a href="logout.php" class="logout">LOGOUT</a>
    </div>
    <div class="content">
        <div class="profile-title">
            <i class="fas fa-user-circle"></i>
            <h1>Your Profile</h1>
        </div>
        <div class="card">
            <i class="fas fa-user-circle"></i>
            <div>
                <h3>ACCOUNT DETAILS</h3>
                <p><?php echo $customer['FirstName'] . ' ' . $customer['LastName']; ?></p>
                <p><?php echo $customer['Email']; ?></p>
            </div>
        </div>
        <div class="card">
            <i class="fas fa-map-marker-alt"></i>
            <div>
                <h3>ADDRESS BOOK</h3>
                <p>Your default shipping address:</p>
                <p><?php echo $default_shipping_address; ?></p>
                <a href="add_address.php">ADD DEFAULT ADDRESS</a>
            </div>
        </div>
        <div class="card">
            <i class="fas fa-credit-card"></i>
            <div>
                <h3>TIMBUYS STORE CREDIT</h3>
                <a href="#">Store credit balance: <?php echo $store_credit_balance; ?></a>
            </div>
        </div>
    </div>
</body>
</html>
