<?php
include('../connection.php');

// Initialize Database and get connection
$database = new Database();
$pdo = $database->getConnection();

// Fetch today's revenue
$stmt = $pdo->prepare("SELECT SUM(VendorProduct.Price * OrderedProduct.Quantity) as revenue FROM Orders
                        INNER JOIN OrderedProduct ON Orders.OrderID = OrderedProduct.OrderID
                        INNER JOIN VendorProduct ON OrderedProduct.VendorProductID = VendorProduct.VendorProductID
                        WHERE DATE(OrderDate) = CURDATE()");
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$revenue = $result['revenue'] ?? 0;

// Fetch today's new users count
$stmt = $pdo->prepare("SELECT COUNT(*) as users FROM Customer WHERE DATE(DOB) = CURDATE()");
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$users = $result['users'] ?? 0;

// Fetch total sales value for today
$stmt = $pdo->prepare("SELECT SUM(VendorProduct.Price * OrderedProduct.Quantity) as sales FROM Orders
                        INNER JOIN OrderedProduct ON Orders.OrderID = OrderedProduct.OrderID
                        INNER JOIN VendorProduct ON OrderedProduct.VendorProductID = VendorProduct.VendorProductID
                        WHERE DATE(OrderDate) = CURDATE()");
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$sales = $result['sales'] ?? 0;

// Example static value for Ads Views; replace with real data if available
$adViews = 3462;
?>

<?php include('includes/header.php'); ?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Admin Dashboard</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="vendor_approval.php">Vendors</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="product_management.php">Products</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="order_management.php">Orders</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="customer_management.php">Customers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="financials.php">Financials</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-4">
    <h2>Welcome, Admin</h2>

    <!-- Dashboard Overview -->
    <div class="row">
        <!-- Today's Money -->
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Today's Money</h5>
                    <h3>Ksh<?php echo number_format($revenue, 2); ?></h3>
                    <p class="text-success">+55% than last week</p>
                </div>
            </div>
        </div>
        
        <!-- Today's Users -->
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Today's Users</h5>
                    <h3><?php echo $users; ?></h3>
                    <p class="text-success">+3% than last month</p>
                </div>
            </div>
        </div>
        
        <!-- Sales -->
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Sales</h5>
                    <h3>Ksh<?php echo number_format($sales, 2); ?></h3>
                    <p class="text-success">+5% than yesterday</p>
                </div>
            </div>
        </div>

        <!-- Ads Views -->
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Ads Views</h5>
                    <h3><?php echo $adViews; ?></h3>
                    <p class="text-danger">-2% than yesterday</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphs Section -->
    <div class="row mt-4">
        <!-- Website Views Graph -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Website Views</h5>
                    <p>Last Campaign Performance</p>
                    <canvas id="websiteViewsChart"></canvas>
                    <p class="text-muted">Campaign sent 2 days ago</p>
                </div>
            </div>
        </div>

        <!-- Daily Sales Graph -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Daily Sales</h5>
                    <p>(+15%) increase in today's sales</p>
                    <canvas id="dailySalesChart"></canvas>
                    <p class="text-muted">Updated 4 min ago</p>
                </div>
            </div>
        </div>

        <!-- Completed Tasks Graph -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Completed Tasks</h5>
                    <p>Last Campaign Performance</p>
                    <canvas id="completedTasksChart"></canvas>
                    <p class="text-muted">Just updated</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js for graphs -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Website Views Chart
new Chart(document.getElementById("websiteViewsChart"), {
    type: 'bar',
    data: {
        labels: ['M', 'T', 'W', 'T', 'F', 'S', 'S'],
        datasets: [{
            label: 'Views',
            data: [50, 30, 20, 40, 70, 90, 80],
            backgroundColor: 'rgba(75, 192, 192, 0.6)',
        }]
    },
    options: { responsive: true }
});

// Daily Sales Chart
new Chart(document.getElementById("dailySalesChart"), {
    type: 'line',
    data: {
        labels: ['J', 'F', 'M', 'A', 'M', 'J', 'J', 'A', 'S', 'O'],
        datasets: [{
            label: 'Sales',
            data: [12, 19, 3, 5, 2, 3, 7],
            backgroundColor: 'rgba(255, 99, 132, 0.6)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1,
            fill: false
        }]
    },
    options: { responsive: true }
});

// Completed Tasks Chart
new Chart(document.getElementById("completedTasksChart"), {
    type: 'doughnut',
    data: {
        labels: ['Completed', 'Pending'],
        datasets: [{
            data: [60, 40],
            backgroundColor: ['#36A2EB', '#FF6384'],
            hoverBackgroundColor: ['#36A2EB', '#FF6384']
        }]
    },
    options: { responsive: true }
});
</script>

<?php include('includes/footer.php'); ?>
