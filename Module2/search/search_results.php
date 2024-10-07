<?php
// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'timbuys');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = isset($_GET['query']) ? $_GET['query'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Build SQL query
$sql = "SELECT p.ProductName, vp.Price, vp.Quantity, vp.Description, v.Name AS VendorName, c.CategoryName
        FROM Product p
        JOIN VendorProduct vp ON p.ProductID = vp.ProductID
        JOIN Vendor v ON vp.VendorID = v.VendorID
        JOIN Category c ON p.CategoryID = c.CategoryID
        WHERE p.ProductName LIKE '%$query%'";

if (!empty($category)) {
    $sql .= " AND c.CategoryName = '$category'";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h1>Search Results</h1>";
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
