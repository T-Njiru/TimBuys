<div class="container mt-5">
    <?php
    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'timbuys_database');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = isset($_GET['query']) ? $_GET['query'] : '';
    $category = isset($_GET['category']) ? $_GET['category'] : '';

    // Build SQL query
    $sql = "SELECT p.ProductID, p.ProductName, vp.Price, vp.Quantity, vp.Description, v.Name AS VendorName, c.CategoryName, p.ProductImage
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
        echo "<h1>Search Results</h1><div class='row'>";
        while ($row = $result->fetch_assoc()) {
            echo "<div class='col-md-3'>";
            echo "<div class='card'>";
            
            echo "<img src='uploads/" . $row['ProductImage'] . "' alt='" . $row['ProductName'] . "' class='card-img-top'>";
            echo "<div class='card-body'>";
            echo "<h5 class='card-title'>" . $row['ProductName'] . "</h5>";
            echo "<p class='card-text'>Price: KSh " . $row['Price'] . "</p>";
            echo "<p class='card-text'>Quantity: " . $row['Quantity'] . "</p>";
            echo "<p class='card-text'>Description: " . $row['Description'] . "</p>";
            echo "<p class='card-text'>Vendor: " . $row['VendorName'] . "</p>";
            echo "<p class='card-text'>Category: " . $row['CategoryName'] . "</p>";
            echo "<a href='View_Product.php?productid=" . $row['ProductID'] . "' class='btn btn-primary'>View Product</a>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<p>No products found.</p>";
    }

    $conn->close();
    ?>
</div>
