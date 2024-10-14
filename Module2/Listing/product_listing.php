<?php
include_once('../../Module3/cart_functions.php'); 

$conn = new mysqli('localhost', 'root', '', 'timbuys_database', 3307);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT p.ProductID, p.ProductName, vp.Price, vp.Quantity, vp.Description, v.Name AS VendorName, c.CategoryName
        FROM Product p
        JOIN VendorProduct vp ON p.ProductID = vp.ProductID
        JOIN Vendor v ON vp.VendorID = v.VendorID
        JOIN Category c ON p.CategoryID = c.CategoryID";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Listings</title>
    <link rel="stylesheet" href="liststyle.css"> <!-- Link to your CSS file -->
</head>
<body>

<header>
    <nav>
        <ul>
            <li><a href="../../Module3/home.php">Home</a></li>
            <li><a href="../../Module3/cart.php">Cart</a></li>
        </ul>
    </nav>
</header>

<section>
    <h1>Product Listings</h1>
    <a href="../../Module3/cart.php" class="btn btn-primary mb-3">View Cart</a>

    <div id="product-list">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='card'>";
                echo "<h5 class='card-title'>" . htmlspecialchars($row['ProductName']) . " (Category: " . htmlspecialchars($row['CategoryName']) . ")</h5>";
                echo "<p class='card-text'>Price: KSh " . htmlspecialchars($row['Price']) . "</p>";
                echo "<p class='card-text'>Quantity Available: " . htmlspecialchars($row['Quantity']) . "</p>";
                echo "<p class='card-text'>Description: " . htmlspecialchars($row['Description']) . "</p>";
                echo "<p class='card-text'>Vendor: " . htmlspecialchars($row['VendorName']) . "</p>";
                echo "<form method='POST' action='../../Module3/add_to_cart.php'>";
                echo "<input type='hidden' name='product_id' value='" . htmlspecialchars($row['ProductID']) . "'>";
                echo "<button type='submit' class='btn btn-success'>Add to Cart</button>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "No products found.";
        }
        $conn->close();
        ?>
    </div>
</section>

<section>
    <h2>Your Cart</h2>
    <div id="cart-items"></div>
</section>

<script src="cart.js"></script> <!-- Link to your JavaScript file -->
</body>
</html>
