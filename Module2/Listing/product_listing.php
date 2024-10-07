<?php

$conn = new mysqli('localhost', 'root', '', 'timbuys database');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT p.ProductName, vp.Price, vp.Quantity, vp.Description, v.Name AS VendorName, c.CategoryName
        FROM Product p
        JOIN VendorProduct vp ON p.ProductID = vp.ProductID
        JOIN Vendor v ON vp.VendorID = v.VendorID
        JOIN Category c ON p.CategoryID = c.CategoryID";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h1>Product Listings</h1>";
    while ($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<h2>" . $row['ProductName'] . " (Category: " . $row['CategoryName'] . ")</h2>";
        echo "<p>Price: KSh " . $row['Price'] . "</p>";
        echo "<p>Quantity: " . $row['Quantity'] . "</p>";
        echo "<p>Description: " . $row['Description'] . "</p>";
        echo "<p>Vendor: " . $row['VendorName'] . "</p>";
        echo "</div><hr>";
    }
} else {
    echo "No products found.";
}

$conn->close();
?>
