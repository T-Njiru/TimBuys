<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Listing</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
    background-color: #daa520;
}
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
        
        .card {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.2s ease;
        }
        .card:hover {
            transform: scale(1.02);
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
        .card h4 {
            color: #FFB700;
            font-weight: bold;
        }
       
        .container {
           
            margin-top: 40px;
        }
        .cart {
    background-color: black;
    color: white; /* Change text color to white for contrast */
    padding: 10px; /* Optional: adds some padding to the content */
    border-radius: 5px; /* Optional: rounds the corners of the background */
}

    </style>
</head>
<body>
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
<div class="container">
    <h1>Products</h1>
    <div class="row">
        <?php
        include 'C:/xampp/htdocs/TimBuys/Module3/cart_functions.php';

            $connect = mysqli_connect('localhost', 'root', '', 'timbuys');
            
            // Modify the query to also join the Vendor table and retrieve vendor information
            $query = "
                SELECT p.ProductID, p.ProductName, p.ProductImage, vp.Price, vp.Quantity, v.Name AS VendorName
                FROM product p
                JOIN vendorproduct vp ON p.ProductID = vp.ProductID
                JOIN vendor v ON vp.VendorID = v.VendorID
                WHERE vp.Quantity > 0
                ORDER BY p.ProductID ASC
            ";
            
            $result = mysqli_query($connect, $query);

            if ($result):
                if (mysqli_num_rows($result) > 0):
                    while ($product = mysqli_fetch_assoc($result)):
        ?>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="card">
                <!-- Make sure to provide the correct path for the image -->
                <img src="<?php echo $product['ProductImage']; ?>" alt="<?php echo $product['ProductName']; ?>" class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $product['ProductName']; ?></h5>
                    <h4><?php echo "Ksh" . $product['Price']; ?></h4>
                    <p><strong>Vendor:</strong> <?php echo $product['VendorName']; ?></p> <!-- Display Vendor Name -->
                    <form method="post" action="../Module3/add_to_cart.php">
                        <input type="hidden" name="product_id" value="<?php echo $product['ProductID']?>">
                        <button type="submit" class="cart">Add to Cart</button>
                    </form>
                    
                </div>
            </div>
        </div>
        <?php
                    endwhile;
                endif;
            endif;
        ?>
    </div>
</div>

</body>
</html>
