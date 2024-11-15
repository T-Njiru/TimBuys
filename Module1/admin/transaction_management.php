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

// Fetch transactions based on search query and pagination
$query = "SELECT * FROM transaction WHERE ReceiptID LIKE :search OR Phonenumber LIKE :search ORDER BY TransactionDate DESC LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total rows for pagination
$countQuery = "SELECT COUNT(*) AS total FROM transaction WHERE ReceiptID LIKE :search OR Phonenumber LIKE :search";
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
    <title>Transaction Management</title>
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
    </style>
</head>
<body>
<div class="container">
    <h2 class="text-center mb-4">Transaction Management</h2>
    
    <!-- Search Form -->
    <form class="d-flex mb-4" method="GET">
        <input type="text" class="form-control me-2" name="search" placeholder="Search by Receipt ID or Phone Number" value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn btn-search">Search</button>
    </form>
    
    <!-- Transactions Table -->
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Receipt ID</th>
                <th>Phone Number</th>
                <th>Amount</th>
                <th>Transaction Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($transactions)): ?>
                <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td><?= htmlspecialchars($transaction['ReceiptID']) ?></td>
                        <td><?= htmlspecialchars($transaction['Phonenumber']) ?></td>
                        <td>Ksh<?= number_format($transaction['Amount'], 2) ?></td>
                        <td><?= htmlspecialchars($transaction['TransactionDate']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">No transactions found.</td>
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
