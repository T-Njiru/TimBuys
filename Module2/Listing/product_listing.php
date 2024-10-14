<?php
include_once('../../Module3/cart_functions.php'); 

$conn = new mysqli('localhost', 'root', '', 'timbuys database');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT p.ProductID, p.ProductName, vp.Price, vp.Quantity, vp.Description, v.Name AS VendorName, c.CategoryName
        FROM Product p
        JOIN VendorProduct vp ON p.ProductID = vp.ProductID
        JOIN Vendor v ON vp.VendorID = v.VendorID
        JOIN Category c ON p.CategoryID = c.CategoryID";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h1>Product Listings</h1>";
    echo '<a href="../../Module3/cart.php" class="btn btn-primary mb-3">View Cart</a>'; 
    while ($row = $result->fetch_assoc()) {
        echo "<div class='card mb-3' style='max-width: 540px;'>";
        echo "<div class='row g-0'>";
        echo "<div class='col-md-8'>";
        echo "<div class='card-body'>";
        echo "<h5 class='card-title'>" . htmlspecialchars($row['ProductName']) . " (Category: " . htmlspecialchars($row['CategoryName']) . ")</h5>";
        echo "<p class='card-text'>Price: KSh " . htmlspecialchars($row['Price']) . "</p>";
        echo "<p class='card-text'>Quantity Available: " . htmlspecialchars($row['Quantity']) . "</p>";
        echo "<p class='card-text'>Description: " . htmlspecialchars($row['Description']) . "</p>";
        echo "<p class='card-text'>Vendor: " . htmlspecialchars($row['VendorName']) . "</p>";
        echo "<form method='POST' action='../../Module3/add_to_cart.php'>"; // Corrected path
        echo "<input type='hidden' name='product_id' value='" . htmlspecialchars($row['ProductID']) . "'>";
        echo "<button type='submit' class='btn btn-success'>Add to Cart</button>";
        echo "</form>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }
} else {
    echo "No products found.";
}

$conn->close();
?>
