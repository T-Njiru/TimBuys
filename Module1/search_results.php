<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TimBuys - Search Results</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .navbar {
            background-color: #000;
            color: #fff;
            padding: 15px;
        }
        .navbar .logo {
            font-size: 24px;
            font-weight: bold;
            color: #fff;
        }
        .navbar .search-bar input[type="text"] {
            width: 250px;
            padding: 5px;
        }
        .navbar .search-bar select, .navbar .search-bar button {
            padding: 5px;
        }
        .navbar .nav-links a {
            color: #fff;
            margin: 0 10px;
        }
        .navbar .nav-links .dropdown-content button {
            display: block;
            width: 100%;
            background: none;
            border: none;
            text-align: left;
            padding: 5px;
            color: #333;
        }
        .card {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .card-body {
            padding: 15px;
        }
        .card-title {
            font-size: 18px;
            font-weight: bold;
        }
        .card-text {
            color: #555;
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<div class="navbar">
    <div class="logo">TIM BUYS</div>
    <div class="search-bar">
        <form action="search_results.php" method="GET">
            <input type="text" name="query" placeholder="Search products, brands, and categories" required>
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
    <div class="nav-links">
        <div class="dropdown">
            <a href="#" class="account-link">
                <i class="fas fa-user"></i> ACCOUNT
            </a>
            <div class="dropdown-content">
                <button onclick="location.href='login.html';">Log In</button>
                <button onclick="location.href='../Module1/signin.html';">Sign Up</button>
            </div>
        </div>
        <a href="#">HELP</a>
        <a href="#"><i class="fas fa-shopping-cart"></i> CART</a>
    </div>
</div>

<div class="container mt-5">
    <?php
    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'timbuys_database', '3307');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = isset($_GET['query']) ? $_GET['query'] : '';
    $category = isset($_GET['category']) ? $_GET['category'] : '';

    // Build SQL query
    $sql = "SELECT p.ProductName, vp.Price, vp.Quantity, vp.Description, v.Name AS VendorName, c.CategoryName, p.ProductImage
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