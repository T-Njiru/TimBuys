<?php
// Include the connection class and initialize the database
include('../connection.php');

// Initialize Database and get connection
$database = new Database();
$pdo = $database->getConnection();

// Get the order_id from the URL
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : '';

// Fetch order details based on the order_id
$query = "
    SELECT 
        o.OrderID, 
        o.OrderDate, 
        o.Address AS DeliveryAddress,
        c.FirstName AS CustomerFirstName, 
        c.LastName AS CustomerLastName, 
        c.Email AS CustomerEmail,
        vp.Price AS ProductPrice,
        vp.Quantity AS OrderedQuantity,
        p.ProductName,
        v.Name AS VendorName,
        op.Status AS ProductStatus
    FROM 
        orders o
    INNER JOIN Customer c ON o.CustomerID = c.CustomerID
    INNER JOIN orderedproduct op ON o.OrderID = op.OrderID
    INNER JOIN VendorProduct vp ON op.VendorProductID = vp.VendorProductID
    INNER JOIN Vendor v ON vp.VendorID = v.VendorID
    INNER JOIN Product p ON vp.ProductID = p.ProductID
    WHERE o.OrderID = :order_id
";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':order_id', $order_id, PDO::PARAM_STR);
$stmt->execute();
$orderDetails = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if order exists
if (!$orderDetails) {
    echo "Order not found!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Order</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Your custom CSS (if any) -->
    <link rel="stylesheet" href="path/to/your/custom/style.css">
</head>
<body>

<!-- Main Content -->
<div class="container mt-4">
    <h2 class="text-center mb-4">Order Details</h2>

    <!-- Order Information -->
    <table class="table table-bordered">
        <tr>
            <th>Order ID</th>
            <td><?= htmlspecialchars($orderDetails['OrderID']) ?></td>
        </tr>
        <tr>
            <th>Customer</th>
            <td><?= htmlspecialchars($orderDetails['CustomerFirstName']) . ' ' . htmlspecialchars($orderDetails['CustomerLastName']) ?></td>
        </tr>
        <tr>
            <th>Customer Email</th>
            <td><?= htmlspecialchars($orderDetails['CustomerEmail']) ?></td>
        </tr>
        <tr>
            <th>Order Date</th>
            <td><?= htmlspecialchars($orderDetails['OrderDate']) ?></td>
        </tr>
        <tr>
            <th>Delivery Address</th>
            <td><?= htmlspecialchars($orderDetails['DeliveryAddress']) ?></td>
        </tr>
    </table>

    <!-- Ordered Product Details -->
    <h3>Ordered Products</h3>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= htmlspecialchars($orderDetails['ProductName']) ?></td>
                <td><?= htmlspecialchars($orderDetails['OrderedQuantity']) ?></td>
                <td>Ksh<?= number_format($orderDetails['ProductPrice'] * $orderDetails['OrderedQuantity'], 2) ?></td>
                <td><?= htmlspecialchars($orderDetails['ProductStatus']) ?></td>
            </tr>
        </tbody>
    </table>

    <a href="order_management.php" class="btn btn-secondary">Back to Orders</a>
</div>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
