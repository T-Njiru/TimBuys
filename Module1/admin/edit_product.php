<?php
include('../connection.php');

// Initialize Database and get connection
$database = new Database();
$pdo = $database->getConnection();

// Get product ID from query string
$productID = isset($_GET['product_id']) ? (int)$_GET['product_id'] : null;

// Redirect if no product ID is provided
if (!$productID) {
    header("Location: product_management.php");
    exit;
}

// Fetch product details based on product ID
$stmt = $pdo->prepare("
    SELECT 
        p.ProductName, 
        vp.Price, 
        vp.Quantity, 
        v.Name AS VendorName
    FROM VendorProduct vp
    INNER JOIN Product p ON vp.ProductID = p.ProductID
    INNER JOIN Vendor v ON vp.VendorID = v.VendorID
    WHERE p.ProductID = :productID
");
$stmt->execute(['productID' => $productID]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// Redirect if product not found
if (!$product) {
    header("Location: product_management.php");
    exit;
}

// Handle form submission for editing product
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $price = isset($_POST['price']) ? (float)$_POST['price'] : null;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : null;

    // Validate input
    if ($price <= 0 || $quantity < 0) {
        $error = 'Please enter valid price and quantity values.';
    } else {
        // Update product information
        $updateStmt = $pdo->prepare("
            UPDATE VendorProduct 
            SET Price = :price, Quantity = :quantity 
            WHERE ProductID = :productID
        ");
        $updateStmt->execute([
            'price' => $price,
            'quantity' => $quantity,
            'productID' => $productID
        ]);

        // Redirect back to product management page
        header("Location: product_management.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
            max-width: 600px;
        }
        .card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #daa520;
            color: #fff;
            text-align: center;
            font-size: 24px;
        }
        .btn-save {
            background-color: #daa520;
            color: white;
            border: none;
        }
        .btn-save:hover {
            background-color: #b59416;
        }
        .error {
            color: red;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="card-header">Edit Product</div>
        <div class="card-body">
            <?php if ($error): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label for="productName" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="productName" value="<?= htmlspecialchars($product['ProductName']) ?>" disabled>
                </div>
                <div class="mb-3">
                    <label for="vendorName" class="form-label">Vendor</label>
                    <input type="text" class="form-control" id="vendorName" value="<?= htmlspecialchars($product['VendorName']) ?>" disabled>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price (Ksh)</label>
                    <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?= htmlspecialchars($product['Price']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" value="<?= htmlspecialchars($product['Quantity']) ?>" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-save">Save Changes</button>
                    <a href="product_management.php" class="btn btn-secondary ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
