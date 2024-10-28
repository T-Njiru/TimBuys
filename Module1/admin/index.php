<?php
include('includes/header.php');
?>
<div class="container mt-4">
    <h2>Hello Admin</h2>
    <div class="row">
        <!-- Today's Money -->
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Today's Money</h5>
                    <h3>$53k</h3>
                    <p class="text-success">+55% than last week</p>
                </div>
            </div>
        </div>
        
        <!-- Today's Users -->
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Today's Users</h5>
                    <h3>2300</h3>
                    <p class="text-success">+3% than last month</p>
                </div>
            </div>
        </div>
        
        <!-- Ads Views -->
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Ads Views</h5>
                    <h3>3,462</h3>
                    <p class="text-danger">-2% than yesterday</p>
                </div>
            </div>
        </div>
        
        <!-- Sales -->
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Sales</h5>
                    <h3>$103,430</h3>
                    <p class="text-success">+5% than yesterday</p>
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
// Data for Website Views Chart
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

// Data for Daily Sales Chart
new Chart(document.getElementById("dailySalesChart"), {
    type: 'line',
    data: {
        labels: ['J', 'F', 'M', 'A', 'M', 'J', 'J', 'A', 'S', 'O', 'N', 'D'],
        datasets: [{
            label: 'Sales',
            data: [300, 400, 150, 200, 500, 250, 400, 300, 350, 220, 400, 380],
            borderColor: 'rgba(54, 162, 235, 1)',
            fill: false
        }]
    },
    options: { responsive: true }
});

// Data for Completed Tasks Chart
new Chart(document.getElementById("completedTasksChart"), {
    type: 'line',
    data: {
        labels: ['Apr', 'Jun', 'Aug', 'Oct', 'Dec'],
        datasets: [{
            label: 'Tasks',
            data: [100, 300, 400, 320, 450],
            borderColor: 'rgba(153, 102, 255, 1)',
            fill: false
        }]
    },
    options: { responsive: true }
});
</script>

<?php
include('includes/footer.php');
?>
