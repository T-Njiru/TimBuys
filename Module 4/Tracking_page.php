<?php
// Fetch order details from the database
$orderId = $_GET['orderId']; // Retrieve OrderID from the URL or session
// Assume database connection is established here

// Query order data
$query = "SELECT * FROM orders WHERE OrderID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $orderId);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

// Calculate Estimated Delivery Date
$estimatedDelivery = date('d/m/Y', strtotime($order['order_date'] . ' + 3 days'));
?>

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
    
    <!-- Leaflet CSS and JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

    <script>
        function initMap() {
            // Fetch current location from backend data
            const data = { latitude: "<?= $order['current_lat'] ?>", longitude: "<?= $order['current_long'] ?>" };
            const location = [parseFloat(data.latitude), parseFloat(data.longitude)];

            const map = L.map('map').setView(location, 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            L.marker(location).addTo(map)
                .bindPopup('Current Location')
                .openPopup();
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
      <span class="account">
          <i class="fas fa-user account-icon"></i> ACCOUNT
      </span>
      <span class="help">HELP</span>
      <span class="cart">
          <i class="fas fa-shopping-cart cart-icon"></i> CART
      </span>
  </div>
</header>

<div class="Tracking">
    <h2>My Order</h2>
    <span class="line"></span>
    <h4>Order ID: <?= $order['OrderID'] ?></h4>
    <div id="card">
        <div class="Card">
            <div class="col"><strong>Estimated Delivery Date:</strong><br><?= $estimatedDelivery ?></div>
            <div class="col"><strong>Shipping BY:</strong><br> TIM BUYS</div>
            <div class="col"><strong>Status:</strong><br> <?= $order['status'] ?></div>
            <div class="col"><strong>Tracking #:</strong><br> <?= $order['OrderID'] ?></div>
        </div>
    </div>
    <div class="track">
        <div class="step <?= $order['status'] == 'Processed' || $order['status'] == 'Shipped' || $order['status'] == 'En Route' || $order['status'] == 'Arrived' ? 'active' : '' ?>">
            <span class="icon"><i class="fa fa-check"></i></span>
            <span class="text">Order Processed</span>
        </div>
        <div class="step <?= $order['status'] == 'Shipped' || $order['status'] == 'En Route' || $order['status'] == 'Arrived' ? 'active' : '' ?>">
            <span class="icon"><i class="fa fa-truck"></i></span>
            <span class="text">Order Shipped</span>
        </div>
        <div class="step <?= $order['status'] == 'En Route' || $order['status'] == 'Arrived' ? 'active' : '' ?>">
            <span class="icon"><i class="fa fa-shipping-fast"></i></span>
            <span class="text">Order En Route</span>
        </div>
        <div class="step <?= $order['status'] == 'Arrived' ? 'active' : '' ?>">
            <span class="icon"><i class="fa fa-home"></i></span>
            <span class="text">Order Arrived</span>
        </div>
    </div>
    <button class="back-button">Back to Orders</button>
</div>
<div id="map" style="height: 400px; width: 100%;"></div>

</body>
</html>
