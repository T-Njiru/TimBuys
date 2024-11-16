<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Listing</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
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
        .btn-buy {
            background-color: #FFB700;
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 5px;
        }
        .btn-buy:hover {
            background-color: #218838;
        }
        .container {
            margin-top: 40px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Products</h1>
    <div class="row">
        <?php
            $connect = mysqli_connect('localhost', 'root', '', 'timbuys');
            $query = "
                SELECT p.ProductID, p.ProductName, p.ProductImage, vp.Price, vp.Quantity 
                FROM product p
                JOIN vendorproduct vp ON p.ProductID = vp.ProductID
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
                <img src="uploads/<?php echo $product['ProductImage']; ?>" alt="<?php echo $product['ProductName']; ?>" class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $product['ProductName']; ?></h5>
                    <h4><?php echo "Ksh" . $product['Price']; ?></h4>
                    <form method="post" action="../../Module3/add_to_cart.php">
                        <input type="hidden" name="product_id" value="<?php echo $product['ProductID']?>">
                        <button type="submit" class="btn btn-success btn-block">Add to Cart</button>
                    </form>
                    <a href="item.php?productid=<?php echo $product['ProductID']; ?>" class="btn-buy d-block text-center mt-2">Buy</a>
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
