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

// Fetch products with vendor information based on search query and pagination
$query = "
    SELECT 
        p.ProductName,
        p.ProductID,
        v.Name AS VendorName,
        vp.Price,
        vp.Quantity
    FROM VendorProduct vp
    INNER JOIN Product p ON vp.ProductID = p.ProductID
    INNER JOIN Vendor v ON vp.VendorID = v.VendorID
    WHERE p.ProductName LIKE :search OR v.Name LIKE :search
    ORDER BY p.ProductName
    LIMIT :limit OFFSET :offset
";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total rows for pagination
$countQuery = "
    SELECT COUNT(*) AS total 
    FROM VendorProduct vp
    INNER JOIN Product p ON vp.ProductID = p.ProductID
    INNER JOIN Vendor v ON vp.VendorID = v.VendorID
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
    <title>Product Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
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
            background-color: #daa520;
            color: #fff;
            text-align: center;
        }
        .btn-action {
            padding: 5px 10px;
            font-size: 14px;
        }
        .btn-search {
            background-color: #daa520;
            color: white;
            border: none;
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
    </style>
</head>
<body>
<div class="container">
    <h2 class="text-center mb-4">Product Management</h2>
    
    <!-- Search Form -->
    <form class="d-flex mb-4" method="GET">
        <input type="text" class="form-control me-2" name="search" placeholder="Search by Product Name or Vendor" value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn btn-search">Search</button>
    </form>
    
    <!-- Products Table -->
    <table class="table table-striped table-bordered">
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
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['ProductName']) ?></td>
                        <td><?= htmlspecialchars($product['VendorName']) ?></td>
                        <td>Ksh<?= number_format($product['Price'], 2) ?></td>
                        <td><?= htmlspecialchars($product['Quantity']) ?></td>
                        <td class="actions">
                            <a href="edit_product.php?product_id=<?= htmlspecialchars($product['ProductID']) ?>" class="btn btn-warning btn-action">Edit</a>
                            <a href="delete_product.php?product_id=<?= htmlspecialchars($product['ProductID']) ?>" class="btn btn-danger btn-action" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No products found.</td>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
