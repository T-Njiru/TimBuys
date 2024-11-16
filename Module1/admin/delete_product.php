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

// Handle deletion request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    // Delete product from VendorProduct table
    $deleteStmt = $pdo->prepare("DELETE FROM VendorProduct WHERE ProductID = :productID");
    $deleteStmt->execute(['productID' => $productID]);

    // Optionally, you can delete the product from the Product table too
    $deleteProductStmt = $pdo->prepare("DELETE FROM Product WHERE ProductID = :productID");
    $deleteProductStmt->execute(['productID' => $productID]);

    // Redirect to product management page after deletion
    header("Location: product_management.php");
    exit;
}

// Fetch product details to display confirmation
$stmt = $pdo->prepare("
    SELECT 
        p.ProductName, 
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Product</title>
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
            background-color: #dc3545;
            color: #fff;
            text-align: center;
            font-size: 24px;
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
            border: none;
        }
        .btn-delete:hover {
            background-color: #b52d3a;
        }
        .btn-cancel {
            background-color: #6c757d;
            color: white;
            border: none;
        }
        .btn-cancel:hover {
            background-color: #565e64;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="card-header">Delete Product</div>
        <div class="card-body text-center">
            <p>Are you sure you want to delete the following product?</p>
            <h4 class="text-danger"><?= htmlspecialchars($product['ProductName']) ?></h4>
            <p><strong>Vendor:</strong> <?= htmlspecialchars($product['VendorName']) ?></p>
            <form method="POST">
                <button type="submit" name="confirm_delete" class="btn btn-delete">Yes, Delete</button>
                <a href="product_management.php" class="btn btn-cancel ms-2">Cancel</a>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
