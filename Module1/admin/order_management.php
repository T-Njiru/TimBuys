<?php
include('../connection.php');

// Initialize Database and get connection
$database = new Database();
$pdo = $database->getConnection();

// Fetch orders with customer, vendor, and product details
$stmt = $pdo->prepare("
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
        v.Name AS VendorName
    FROM 
        orders o
    INNER JOIN Customer c ON o.CustomerID = c.CustomerID
    INNER JOIN orderedproduct op ON o.OrderID = op.OrderID
    INNER JOIN VendorProduct vp ON op.VendorProductID = vp.VendorProductID
    INNER JOIN Vendor v ON vp.VendorID = v.VendorID
    INNER JOIN Product p ON vp.ProductID = p.ProductID
    ORDER BY o.OrderDate DESC
");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include('includes/header.php'); ?>

<div class="container mt-4">
    <h2>Order Management</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Vendor</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Order Date</th>
                <th>Delivery Address</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo $order['OrderID']; ?></td>
                    <td><?php echo $order['CustomerFirstName'] . ' ' . $order['CustomerLastName']; ?> (<?php echo $order['CustomerEmail']; ?>)</td>
                    <td><?php echo $order['VendorName']; ?></td>
                    <td><?php echo $order['ProductName']; ?></td>
                    <td><?php echo $order['OrderedQuantity']; ?></td>
                    <td>Ksh<?php echo number_format($order['ProductPrice'] * $order['OrderedQuantity'], 2); ?></td>
                    <td><?php echo $order['OrderDate']; ?></td>
                    <td><?php echo $order['DeliveryAddress']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>
