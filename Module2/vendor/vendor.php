<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <link rel="stylesheet" href="vendor.css">
</head>
<body class="page">
    <header class="Navbar">
        <div class="Brand">TimBuys</div>
        <div class="Search">
            <input type="text" placeholder="Search for products...">
            <button>Search</button>
        </div>
        <div class="UserActions">
            <span><i class="icon-user">Account</i></span>
            <span><i class="icon-cart">Logout</i></span>
        </div>
    </header>

    <main class="Container">
        <h1>Add a New Product</h1>
        <form action="add_product.php" method="post" enctype="multipart/form-data" class="Box">
            <div class="Column">
                <label for="product_name" class="title">Product Name:</label>
                <input type="text" name="product_name" required><br><br>

                <label for="category_id" class="title">Category:</label>
                <select name="category_id">
                    <option value="1">Electronics</option>
                    <option value="2">Clothing</option>
                    <option value="3">Home Appliances</option>
                </select><br><br>

                <label for="product_image" class="title">Product Image:</label>
                <input type="file" name="product_image" accept="image/*" required><br><br>
            </div>
            
            <div class="Column">
                <label for="price" class="title">Price:</label>
                <input type="number" name="price" step="0.01" required><br><br>

                <label for="quantity" class="title">Quantity:</label>
                <input type="number" name="quantity" required><br><br>

                <label for="description" class="title">Description:</label>
                <textarea name="description"></textarea><br><br>

                <input type="submit" name="submit" value="Add Product" class="ReturnButton">
            </div>
        </form>

        <h2>My Products</h2>
        <?php
        include 'add_product.php';
        $stmt = $conn->prepare("SELECT p.ProductID, p.ProductName, p.ProductImage, v.Price, v.Quantity 
                                FROM product p 
                                JOIN vendorproduct v ON p.ProductID = v.ProductID 
                                WHERE v.VendorID = ?");
        $stmt->bind_param("i", $vendor_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()): ?>
            <div class="card">
                <img src="<?php echo $row['ProductImage']; ?>" alt="Product Image" width="200" height="200">
                <h3><?php echo $row['ProductName']; ?></h3>
                <p>Price: Ksh <?php echo $row['Price']; ?></p>
                <p>Quantity: <?php echo $row['Quantity']; ?></p>
                <a href="add_product.php?delete_id=<?php echo $row['ProductID']; ?>" 
                   onclick="return confirm('Are you sure you want to delete this product?');" 
                   class="btn-primary">Delete</a>
            </div>
        <?php endwhile; ?>
        <?php $stmt->close(); ?>
    </main>
</body>
</html>
