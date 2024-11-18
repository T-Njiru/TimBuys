<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - TimBuys</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Navbar styling to match TimBuys theme */
        .navbar {
            background-color: #000;
            padding: 10px 0;
        }
        .navbar-brand {
            font-size: 24px;
            font-weight: bold;
            color: white;
        }
        .navbar input[type="text"] {
            width: 300px;
            border-radius: 0;
        }
        .navbar .btn-search {
            background-color: #333;
            color: white;
            border: none;
        }
        
        /* Product cards styling */
        .card {
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        .card-text {
            font-size: 14px;
            color: #555;
        }
        .btn-primary {
            background-color:  #FFB700;
            border: none;
        }
        .btn-primary:hover {
            background-color:  #FFB700;
        }
    </style>
</head>
<body>

<!-- Navbar to match TimBuys theme -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="#">TIM BUYS</a>
        <form class="form-inline mx-auto" action="search_results.php" method="GET">
            <input class="form-control mr-sm-2" type="text" name="query" placeholder="Search products, brands, and categories" aria-label="Search">
            <button class="btn btn-search my-2 my-sm-0" type="submit">Search</button>
        </form>
        <div class="navbar-nav">
            <a class="nav-link text-white" href="#">Account</a>
            <a class="nav-link text-white" href="#">Help</a>
            <a class="nav-link text-white" href="#">Cart</a>
        </div>
    </div>
</nav>
<div class="container mt-5">
    <?php
    
    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'timbuys');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve search parameters from URL
    $query = isset($_GET['query']) ? $_GET['query'] : '';
    $category = isset($_GET['category']) ? $_GET['category'] : '';

    // Build SQL query for searching products
    $sql = "SELECT p.ProductID, p.ProductName, vp.Price, vp.Quantity, vp.Description, v.Name AS VendorName, c.CategoryName, p.ProductImage
            FROM Product p
            JOIN VendorProduct vp ON p.ProductID = vp.ProductID
            JOIN Vendor v ON vp.VendorID = v.VendorID
            JOIN Category c ON p.CategoryID = c.CategoryID
            WHERE p.ProductName LIKE '%$query%'";

    // Filter by category if provided
    if (!empty($category)) {
        $sql .= " AND c.CategoryName = '$category'";
    }

    $result = $conn->query($sql);

    // Display search results
    if ($result->num_rows > 0) {
        echo "<h1 class='mb-4'>Search Results</h1><div class='row'>";
        while ($row = $result->fetch_assoc()) {
            // Check if the image path is already an absolute URL
            $image_path = $row['ProductImage'];
            
            // Display the product card
            echo "<div class='col-md-3'>";
            echo "<div class='card'>";
            echo "<img src='" . htmlspecialchars($image_path) . "' alt='" . htmlspecialchars($row['ProductName']) . "' class='card-img-top' style='height:200px; object-fit:cover;'>";
            echo "<div class='card-body'>";
            echo "<h5 class='card-title'>" . htmlspecialchars($row['ProductName']) . "</h5>";
            echo "<p class='card-text'><strong>Price:</strong> KSh " . number_format($row['Price'], 2) . "</p>";
            echo "<p class='card-text'><strong>Quantity:</strong> " . htmlspecialchars($row['Quantity']) . "</p>";
            echo "<p class='card-text'><strong>Description:</strong> " . htmlspecialchars($row['Description']) . "</p>";
            echo "<p class='card-text'><strong>Vendor:</strong> " . htmlspecialchars($row['VendorName']) . "</p>";
            echo "<p class='card-text'><strong>Category:</strong> " . htmlspecialchars($row['CategoryName']) . "</p>";
            echo "<a href='View_Product.php?productid=" . $row['ProductID'] . "' class='btn btn-primary'>View Product</a>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<p>No products found.</p>";
    }

    // Close the database connection
    $conn->close();
    ?>
</div>


