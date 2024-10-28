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
            <span><i class="icon-cart">Cart</i></span>
        </div>
    </header>

    <main class="Container">
        <h1>Add a New Product</h1>
        <form action="add_product.php" method="post" enctype="multipart/form-data" class="Box">
            <div class="Column">
                <!-- Removed Vendor ID input field -->
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
    </main>
</body>
</html>
