<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Caveat">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open-Sans">
    <link rel="stylesheet" href="Homepage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Order Tracking - Tim Buys</title>
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

    <script>
        async function initMap() {
            const start = [parseFloat("-1.3105"), parseFloat("36.8148")]; // Start location
            const end = [parseFloat("-1.286389"), parseFloat("36.817223")]; // End location, Nairobi CBD for example

            // Initialize map
            const map = L.map('map').setView(start, 13);

            // Tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            // Route (polyline) between start and end
            const route = L.polyline([start, end], {color: 'blue'}).addTo(map);

            // Markers at start and end
            L.marker(start).addTo(map).bindPopup('Picked Up Location').openPopup();
            L.marker(end).addTo(map).bindPopup('Delivery Destination').openPopup();

            // Fit map to the route bounds
            map.fitBounds(route.getBounds());
        }

        window.onload = initMap;
    </script>
</head>
<body>

<header class="header">
  <div class="logo">TIM BUYS</div>
  <div class="search-bar">
      <input type="text" placeholder="Search products, brands and categories">
      <button>Search</button>
  </div>
  <div class="account-section">
      <span class="account"><i class="fas fa-user account-icon"></i> ACCOUNT</span>
      <span class="help">HELP</span>
      <span class="cart"><i class="fas fa-shopping-cart cart-icon"></i> CART</span>
  </div>
</header>

<?php

$conn = new mysqli("hostname", "username", "password", "TimBuys");


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$orderID = "BC123FD456"; 
$sql = "SELECT order_date, estimated_delivery_date, status, tracking_number FROM orders WHERE order_id = '$orderID'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    
    $row = $result->fetch_assoc();
    $orderDate = $row['order_date'];
    $estimatedDeliveryDate = date('d/m/Y', strtotime('+1 day', strtotime($orderDate))); // Calculate estimated delivery date
    $status = $row['status'];
    $trackingNumber = $row['tracking_number'];
} else {
    echo "No records found.";
}
$conn->close();
?>

<div class="Tracking">
    <h2>My Order</h2>
    <span class="line"></span>
    <h4>Order ID: <?= $orderID ?></h4>
    <div id="card">
        <div class="Card">
            <div class="col"><strong>Estimated Delivery Date:</strong> <br><?= $estimatedDeliveryDate ?></div>
            <div class="col"><strong>Shipping BY:</strong> <br> TIM BUYS</div>
            <div class="col"><strong>Status:</strong> <br> <?= $status ?></div>
            <div class="col"><strong>Tracking #:</strong> <br> <?= $trackingNumber ?></div>
        </div>
    </div>
    <div class="track">
        <div class="step active"><span class="icon"><i class="fa fa-check"></i></span><span class="text">Order Processed</span></div>
        <div class="step active"><span class="icon"><i class="fa fa-truck"></i></span><span class="text">Picked by Courier</span></div>
        <div class="step"><span class="icon"><i class="fa fa-shipping-fast"></i></span><span class="text">Order En Route</span></div>
        <div class="step"><span class="icon"><i class="fa fa-home"></i></span><span class="text">Order Arrived</span></div>
    </div>
    <a href="index.php"><button class="back-button">Backs