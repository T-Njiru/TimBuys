<?php
include('../connection.php');

// Initialize Database and get connection
$database = new Database();
$pdo = $database->getConnection();

// Fetch all customers
$stmt = $pdo->prepare("SELECT * FROM Customer ORDER BY FirstName");
$stmt->execute();
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include('includes/header.php'); ?>

<div class="container mt-4">
    <h2>Customer Management</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Customer ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customers as $customer): ?>
                <tr>
                    <td><?php echo $customer['CustomerID']; ?></td>
                    <td><?php echo $customer['FirstName'] . ' ' . $customer['LastName']; ?></td>
                    <td><?php echo $customer['Email']; ?></td>
                    <td><?php echo $customer['Contact']; ?></td>
                    <td>
                        <a href="view_customer.php?customer_id=<?php echo $customer['CustomerID']; ?>" class="btn btn-info">View</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>
