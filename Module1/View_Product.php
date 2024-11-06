<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Navbar styling to match TimBuys style */
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

        /* Product page styling */
        .product-container {
            display: flex;
            flex-wrap: wrap;
            margin-top: 20px;
            color: #333;
        }
        .product-image {
            flex: 1;
            max-width: 400px;
            margin-right: 20px;
        }
        .product-image img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        .product-details {
            flex: 2;
            margin-top: 20px;
        }
        .product-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .product-price {
            color: #28a745;
            font-size: 24px;
            margin-bottom: 15px;
        }
        .product-description {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .btn-add-to-cart {
            background-color: #FFB700;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            text-decoration: none;
        }
        .btn-add-to-cart:hover {
            background-color: #FFB700;
        }
    </style>
</head>
<body>

<!-- Navbar to match TimBuys theme -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="#">TIM BUYS</a>
        <form class="form-inline mx-auto">
            <input class="form-control mr-sm-2" type="text" placeholder="Search products, brands, and categories" aria-label="Search">
            <button class="btn btn-search my-2 my-sm-0" type="submit">Search</button>
        </form>
        <div class="navbar-nav">
            <a class="nav-link text-white" href="#">Account</a>
            <a class="nav-link text-white" href="#">Help</a>
            <a class="nav-link text-white" href="#">Cart</a>
        </div>
    </div>
</nav>

<div class="container product-container">
    <?php
include 'C:/xampp/htdocs/TimBuys/Module3/cart_functions.php';

    
        if (isset($_GET['productid'])) {
            // Get the product ID from the URL
            $productID = $_GET['productid'];
            
            // Connect to the database
            $connect = mysqli_connect('localhost', 'root', '', 'timbuys');
            
            // Check connection
            if (!$connect) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Query to get the product details
            $query = "
                SELECT p.ProductName, p.ProductImage, vp.Price, vp.Description 
                FROM product p
                JOIN vendorproduct vp ON p.ProductID = vp.ProductID
                WHERE p.ProductID = $productID
            ";
            $result = mysqli_query($connect, $query);

            // Check if the product exists
            if ($result && mysqli_num_rows($result) > 0) {
                $product = mysqli_fetch_assoc($result);
    ?>
                <div class="product-image">
                    <img src="uploads/<?php echo $product['ProductImage']; ?>" alt="<?php echo $product['ProductName']; ?>">
                </div>
                <div class="product-details">
                    <h1 class="product-title"><?php echo $product['ProductName']; ?></h1>
                    <h2 class="product-price">KSh <?php echo number_format($product['Price'], 2); ?></h2>
                    <p class="product-description"><?php echo $product['Description']; ?></p>
                    <form method="post" action="../Module3/add_to_cart.php">
                        <input value="<?php echo $productID;?>" name="product_id" hidden >
                    <button type="submit" class="btn btn-add-to-cart">Add to Cart</button>
                 </form>

                </div>
    <?php
            } else {
                echo "<p>Product not found.</p>";
            }
            // Close the database connection
            mysqli_close($connect);
        } else {
            echo "<p>No product selected.</p>";
        }
    ?>
</div>

</body>
</html>
