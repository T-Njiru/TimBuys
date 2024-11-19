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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
        }
        .table {
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .table th {
            background-color: #daa520; /* Honey yellow */
            color: #fff;
            text-align: center;
        }
        .btn-info {
            background-color: #daa520 !important; /* Honey yellow */
            border: none;
            color: #fff;
        }
        .btn-info:hover {
            background-color: #b59416 !important; /* Darker honey yellow */
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Customer Management</h2>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Customer ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($customer['CustomerID']); ?></td>
                        <td><?php echo htmlspecialchars($customer['FirstName'] . ' ' . $customer['LastName']); ?></td>
                        <td><?php echo htmlspecialchars($customer['Email']); ?></td>
                        <td><?php echo htmlspecialchars($customer['Contact']); ?></td>
                        
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include('includes/footer.php'); ?>
