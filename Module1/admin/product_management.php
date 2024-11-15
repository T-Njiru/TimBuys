<?php
include('../connection.php');

// Initialize Database and get connection
$database = new Database();
$pdo = $database->getConnection();

// Fetch all products with vendor information
$stmt = $pdo->prepare("
    SELECT 
        p.ProductName,
        p.ProductID,
        v.Name AS VendorName,
        vp.Price,
        vp.Quantity
    FROM VendorProduct vp
    INNER JOIN Product p ON vp.ProductID = p.ProductID
    INNER JOIN Vendor v ON vp.VendorID = v.VendorID
    ORDER BY p.ProductName
");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include('includes/header.php'); ?>

<div class="container mt-4">
    <h2>Product Management</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Vendor</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo $product['ProductName']; ?></td>
                    <td><?php echo $product['VendorName']; ?></td>
                    <td>Ksh<?php echo number_format($product['Price'], 2); ?></td>
                    <td><?php echo $product['Quantity']; ?></td>
                    <td>
                        <a href="edit_product.php?product_id=<?php echo $product['ProductID']; ?>" class="btn btn-warning">Edit</a>
                        <a href="delete_product.php?product_id=<?php echo $product['ProductID']; ?>" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>
