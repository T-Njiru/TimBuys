<?php
// Include the connection class and initialize the database
include('../connection.php');

// Initialize Database and get connection
$database = new Database();
$pdo = $database->getConnection();

// Handle search and pagination
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$limit = 5; // Number of rows per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch orders with customer, vendor, and product details based on search query and pagination
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
        v.Name AS VendorName
    FROM 
        orders o
    INNER JOIN Customer c ON o.CustomerID = c.CustomerID
    INNER JOIN orderedproduct op ON o.OrderID = op.OrderID
    INNER JOIN VendorProduct vp ON op.VendorProductID = vp.VendorProductID
    INNER JOIN Vendor v ON vp.VendorID = v.VendorID
    INNER JOIN Product p ON vp.ProductID = p.ProductID
    WHERE p.ProductName LIKE :search OR v.Name LIKE :search
    ORDER BY o.OrderDate DESC
    LIMIT :limit OFFSET :offset
";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total rows for pagination
$countQuery = "
    SELECT COUNT(*) AS total 
    FROM orders o
    INNER JOIN Customer c ON o.CustomerID = c.CustomerID
    INNER JOIN orderedproduct op ON o.OrderID = op.OrderID
    INNER JOIN VendorProduct vp ON op.VendorProductID = vp.VendorProductID
    INNER JOIN Vendor v ON vp.VendorID = v.VendorID
    INNER JOIN Product p ON vp.ProductID = p.ProductID
    WHERE p.ProductName LIKE :search OR v.Name LIKE :search
";
$countStmt = $pdo->prepare($countQuery);
$countStmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
$countStmt->execute();
$totalRows = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalRows / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Your custom CSS (if any) -->
    <style>
        /* Custom style for the table header background */
    
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .table {
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .table th {
            background-color: #daa520 !important;;
            color: #fff !important;;
            text-align: center;
        }
        .btn-action {
            padding: 5px 10px;
            font-size: 14px;
        }
        .btn-search {
            background-color: #daa520 !important;;
            color: white !important;;
            border: none !important;;
        }
        .btn-search:hover {
            background-color: #b59416;
        }
        .pagination a {
            color: #daa520;
        }
        .pagination a:hover {
            text-decoration: underline;
        }
        .actions a {
            margin-right: 5px;
        }
        .buttonn {
            background-color: #daa520 !important;;
            border-color: #f8f9fa !important;;

        
        }
        .search-field {
            background-color: white !important;;
            border: 1px solid #ccc !important;;
            border-radius: 5px !important;;
            padding: 3px 10px !important;;
        }
        
    </style>
</head>
<?php include('includes/header.php'); ?>
<body>

<!-- Main Content -->
<div class="container mt-4">
    <h2 class="text-center mb-4">Order Management</h2>
    
    <!-- Search Form -->
    <form class="d-flex mb-4" method="GET">
        <input type="text" class="form-control search-field me-2" name="search" placeholder="Search by Product Name or Vendor" value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="buttonn">Search</button>
    </form>

    <!-- Orders Table -->
    <table class="table table-striped table-bordered">
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
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['OrderID']) ?></td>
                        <td><?= htmlspecialchars($order['CustomerFirstName']) . ' ' . htmlspecialchars($order['CustomerLastName']) ?> (<?= htmlspecialchars($order['CustomerEmail']) ?>)</td>
                        <td><?= htmlspecialchars($order['VendorName']) ?></td>
                        <td><?= htmlspecialchars($order['ProductName']) ?></td>
                        <td><?= htmlspecialchars($order['OrderedQuantity']) ?></td>
                        <td>Ksh<?= number_format($order['ProductPrice'] * $order['OrderedQuantity'], 2) ?></td>
                        <td><?= htmlspecialchars($order['OrderDate']) ?></td>
                        <td><?= htmlspecialchars($order['DeliveryAddress']) ?></td>
                        <td class="actions">
                            <a href="view_order.php?order_id=<?= htmlspecialchars($order['OrderID']) ?>" class="btn btn-dark btn-action">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="text-center">No orders found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <nav>
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php include('includes/footer.php'); ?>
</html>
