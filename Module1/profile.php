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

// Check if customer data was retrieved
if (!$customer) {
    echo "Customer not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tim Buys - Profile</title>
    <link rel="stylesheet" href="styles/profile.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="cart.php">Cart</a></li>
            <li><a href="profile.php">Profile</a></li> <!-- Active Profile link -->
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h1>Your Profile</h1>
        <p><strong>First Name:</strong> <?php echo htmlspecialchars($customer['FirstName']); ?></p>
        <p><strong>Last Name:</strong> <?php echo htmlspecialchars($customer['LastName']); ?></p>
        <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($customer['DOB']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($customer['Email']); ?></p>
        <p><strong>Contact:</strong> <?php echo htmlspecialchars($customer['Contact']); ?></p>
    </div>
</body>
</html>
