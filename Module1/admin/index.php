<?php
include('../connection.php');

// Initialize Database and get connection
$database = new Database();
$pdo = $database->getConnection();

// Fetch today's total revenue
$stmt = $pdo->prepare("SELECT SUM(VendorProduct.Price * OrderedProduct.Quantity) AS revenue
                        FROM Orders
                        INNER JOIN OrderedProduct ON Orders.OrderID = OrderedProduct.OrderID
                        INNER JOIN VendorProduct ON OrderedProduct.VendorProductID = VendorProduct.VendorProductID
                        WHERE DATE(Orders.OrderDate) = CURDATE()");
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$revenue = $result['revenue'] ?? 0;

// Fetch today's total orders count
$stmt = $pdo->prepare("SELECT COUNT(*) AS totalOrders FROM Orders WHERE DATE(OrderDate) = CURDATE()");
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$totalOrders = $result['totalOrders'] ?? 0;

// Fetch today's new users count
$stmt = $pdo->prepare("SELECT COUNT(*) AS newUsers FROM Customer WHERE DATE(DOB) = CURDATE()");
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$newUsers = $result['newUsers'] ?? 0;

// Fetch total completed orders count
$stmt = $pdo->prepare("SELECT COUNT(*) AS completedOrders FROM OrderedProduct 
                        INNER JOIN Orders ON Orders.OrderID = OrderedProduct.OrderID
                        WHERE OrderedProduct.Status = 'Completed' AND DATE(Orders.OrderDate) = CURDATE()");
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$completedOrders = $result['completedOrders'] ?? 0;

// Fetch daily sales for the last 7 days
$stmt = $pdo->prepare("SELECT SUM(VendorProduct.Price * OrderedProduct.Quantity) AS dailySales
                        FROM Orders
                        INNER JOIN OrderedProduct ON Orders.OrderID = OrderedProduct.OrderID
                        INNER JOIN VendorProduct ON OrderedProduct.VendorProductID = VendorProduct.VendorProductID
                        WHERE DATE(Orders.OrderDate) BETWEEN CURDATE() - INTERVAL 6 DAY AND CURDATE()
                        GROUP BY DATE(Orders.OrderDate) ORDER BY DATE(Orders.OrderDate) DESC");
$stmt->execute();
$dailySalesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the sales data for the graph (latest 7 days)
$salesData = [];
foreach ($dailySalesData as $daySales) {
    $salesData[] = $daySales['dailySales'] ?? 0;
}
while (count($salesData) < 7) { // Fill in missing days if there are fewer than 7
    array_unshift($salesData, 0);
}

?>

<?php include('includes/header.php'); ?>

<div class="container mt-4">
    <h2>Welcome, Admin</h2>

    <!-- Dashboard Overview -->
    <div class="row">
        <!-- Today's Revenue -->
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Today's Revenue</h5>
                    <h3>Ksh<?php echo number_format($revenue, 2); ?></h3>
                    <!-- No percentage comparison for now -->
                </div>
            </div>
        </div>
        
        <!-- Today's Orders -->
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Today's Orders</h5>
                    <h3><?php echo $totalOrders; ?></h3>
                </div>
            </div>
        </div>
        
        <!-- Today's New Users -->
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Today's New Users</h5>
                    <h3><?php echo $newUsers; ?></h3>
                </div>
            </div>
        </div>

        <!-- Today's Completed Orders -->
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Completed Orders</h5>
                    <h3><?php echo $completedOrders; ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphs Section -->
    <div class="row mt-4">
        <!-- Daily Sales Graph -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Daily Sales</h5>
                    <canvas id="dailySalesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Completed Orders Graph -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Completed Orders</h5>
                    <canvas id="completedOrdersChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Website Views Graph -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Website Views</h5>
                    <canvas id="websiteViewsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js for graphs -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Daily Sales Chart (based on data from the database)
new Chart(document.getElementById("dailySalesChart"), {
    type: 'line',
    data: {
        labels: ['M', 'T', 'W', 'T', 'F', 'S', 'S'],
        datasets: [{
            label: 'Sales',
            data: <?php echo json_encode($salesData); ?>, // Dynamic data from the database
            backgroundColor: 'rgba(255, 99, 132, 0.6)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1,
            fill: false
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                ticks: {
                    beginAtZero: true,
                    max: 100 // Adjusting the maximum value to be realistic
                }
            }
        }
    }
});

// Completed Orders Chart
new Chart(document.getElementById("completedOrdersChart"), {
    type: 'pie',
    data: {
        labels: ['Completed', 'Pending'],
        datasets: [{
            data: [<?php echo $completedOrders; ?>, <?php echo $totalOrders - $completedOrders; ?>],
            backgroundColor: ['#36A2EB', '#FF6384'],
            hoverBackgroundColor: ['#36A2EB', '#FF6384']
        }]
    },
    options: { responsive: true }
});

// Website Views Chart (example data, adjust as needed)
new Chart(document.getElementById("websiteViewsChart"), {
    type: 'bar',
    data: {
        labels: ['M', 'T', 'W', 'T', 'F', 'S', 'S'],
        datasets: [{
            label: 'Website Views',
            data: [50, 60, 80, 40, 90, 70, 85], // Replace with actual data as needed
            backgroundColor: 'rgba(75, 192, 192, 0.6)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                ticks: {
                    beginAtZero: true,
                    max: 100 // Adjusting the maximum value to be realistic
                }
            }
        }
    }
});
</script>

<?php include('includes/footer.php'); ?>
