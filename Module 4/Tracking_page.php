<?php
require 'C:/xampp/htdocs/TimBuys2/Module 4/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$OrderID = isset($_GET['OrderID']) ? $_GET['OrderID'] : null;
if (!$OrderID) {
    die("Error: Order ID not found in URL parameters.");
}

// Database Connection
$conn = new mysqli('localhost', 'root', '', 'TimBuys');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch order and status details
$sql = "SELECT 
            o.CustomerID, 
            o.OrderDate, 
            o.Address AS CustomerFullAddress,
            op.Status, 
            op.OrderID, 
            v.Address AS VendorAddress,
            c.Email AS CustomerEmail
        FROM orders o
        JOIN orderedproduct op ON o.OrderID = op.OrderID
        JOIN VendorProduct vp ON op.VendorProductID = vp.VendorProductID
        JOIN Vendor v ON vp.VendorID = v.VendorID
        JOIN Customer c ON o.CustomerID = c.CustomerID
        WHERE o.OrderID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $OrderID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $customerID = $row['CustomerID'];
    $orderDate = $row['OrderDate'];
    $status = $row['Status'];
    $trackingNumber = $row['OrderID'];
    $vendorAddress = $row['VendorAddress'];
    $customerFullAddress = $row['CustomerFullAddress'];
    $customerEmail = $row['CustomerEmail'];
} else {
    die("No records found for Order ID: $OrderID");
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Tracking</title>
    <link rel="stylesheet" href="Tracking_page.css">
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
</head>
<body>
<div class="Tracking">
    <h2>Order Tracking</h2>
    <p>Order ID: <span id="order-id"><?= $OrderID ?></span></p>
    <p>Status: <span id="order-status"><?= $status ?></span></p>
    <div id="map" style="height: 400px;"></div>
</div>

<script>
// Fetch updated status every 30 seconds
async function updateStatus() {
    const orderID = <?= json_encode($OrderID) ?>;

    try {
        // Fetch the updated status from the server
        const response = await fetch(`updateOrderStatus.php?OrderID=${orderID}`);
        const data = await response.json();

        if (data.status === 'success') {
            // Update the status on the page
            document.getElementById('order-status').textContent = data.newStatus;

            // Show email notification if provided
            console.log(data.message);
        } else {
            console.error(data.message);
        }
    } catch (error) {
        console.error("Error updating status:", error);
    }
}

// Run updateStatus every 30 seconds
setInterval(updateStatus, 30000);

// Initialize map
async function initMap() {
    const start = <?= json_encode(getCoordinates($vendorAddress)) ?>;
    const end = <?= json_encode(getCoordinates($customerFullAddress)) ?>;

    if (!start || !end) {
        console.error("Coordinates are missing.");
        document.getElementById("map").innerHTML = "<p>Map data unavailable.</p>";
        return;
    }

    const map = L.map("map").setView([start.lat, start.lng], 13);
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
        attribution: "&copy; <a href='https://www.openstreetmap.org/copyright'>OpenStreetMap</a>"
    }).addTo(map);

    const route = L.polyline([[start.lat, start.lng], [end.lat, end.lng]], { color: "#daa520", weight: 5 }).addTo(map);
    L.marker([start.lat, start.lng]).addTo(map).bindPopup("Vendor Location").openPopup();
    L.marker([end.lat, end.lng]).addTo(map).bindPopup("Customer Location").openPopup();
    map.fitBounds(route.getBounds());
}

initMap();
</script>
</body>
</html>
