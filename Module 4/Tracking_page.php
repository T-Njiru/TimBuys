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
        function getOrderID() {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get('order_id');
        }

        async function initMap(orderID) {
            try {
                const response = await fetch(`getCoordinates.php?order_id=${orderID}`); // Fetch coordinates from the backend
                const data = await response.json();

                if (data.error) {
                    throw new Error(data.error);
                }

                const start = [parseFloat(data.start.lat), parseFloat(data.start.lng)];
                const end = [parseFloat(data.end.lat), parseFloat(data.end.lng)];

                // Initialize map
                const map = L.map('map').setView(start, 13);

                // Tile layer
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);

                // Route (polyline) between start and end
                const route = L.polyline([start, end], { color: 'blue' }).addTo(map);

                // Markers at start and end
                L.marker(start).addTo(map).bindPopup('Picked Up Location').openPopup();
                L.marker(end).addTo(map).bindPopup('Delivery Destination').openPopup();

                // Fit map to the route bounds
                map.fitBounds(route.getBounds());
            } catch (error) {
                console.error('Error fetching coordinates:', error);
            }
        }

        window.onload = function() {
            const orderID = getOrderID();
            if (orderID) {
                initMap(orderID);
            } else {
                console.error('Order ID not found in URL parameters.');
            }
        };
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
function getCoordinates($address) {
    $url = "https://api.bigdatacloud.net/client-side-reverse-geocode-client?address={$address}";

    $response = file_get_contents($url);
    $json = json_decode($response, true);

    if (isset($json['results'][0]['geometry']['location'])) {
        $coordinates = $json['results'][0]['geometry']['location'];
        return [
            'lat' => $coordinates['lat'],
            'lng' => $coordinates['lng']
        ];
    } else {
        return null;
    }
}

$conn = new mysqli('localhost', 'root', '', 'TimBuys');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$orderID = $_GET['order_id']; 

// Fetch vendor and customer IDs from orders table
$sql = "SELECT vendor_id, customer_id, order_date, status, tracking_number FROM orders WHERE order_id = '$orderID'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $vendorID = $row['vendor_id'];
    $customerID = $row['customer_id'];
    $orderDate = $row['order_date'];
    $status = $row['status'];
    $trackingNumber = $row['tracking_number'];

    // Fetch vendor address
    $vendorQuery = "SELECT address FROM vendors WHERE vendor_id = '$vendorID'";
    $vendorResult = $conn->query($vendorQuery);
    $vendorAddress = $vendorResult->fetch_assoc()['address'];

    // Fetch customer address
    $customerQuery = "SELECT address FROM customers WHERE customer_id = '$customerID'";
    $customerResult = $conn->query($customerQuery);
    $customerAddress = $customerResult->fetch_assoc()['address'];

    $startCoordinates = getCoordinates($vendorAddress);
    $endCoordinates = getCoordinates($customerAddress);

    $response = [
        'start' => $startCoordinates,
        'end' => $endCoordinates,
        'order_date' => $orderDate,
        'status' => $status,
        'tracking_number' => $trackingNumber
    ];

    echo '<script>
            async function initMap() {
                const data = ' . json_encode($response) . ';
                const start = [parseFloat(data.start.lat), parseFloat(data.start.lng)];
                const end = [parseFloat(data.end.lat), parseFloat(data.end.lng)];
        
                const map = L.map("map").setView(start, 13);
        
                L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                    maxZoom: 19,
                    attribution: "&copy; <a href=\'https://www.openstreetmap.org/copyright\'>OpenStreetMap</a>"
                }).addTo(map);
        
                const route = L.polyline([start, end], {color: "blue"}).addTo(map);
        
                L.marker(start).addTo(map).bindPopup("Picked Up Location").openPopup();
                L.marker(end).addTo(map).bindPopup("Delivery Destination").openPopup();
        
                map.fitBounds(route.getBounds());
            }
            window.onload = initMap;
          </script>';
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
    <div id="map" style="height: 400px;"></div>
    <div class="track">
        <div class="step active"><span class="icon"><i class="fa fa-check"></i></span><span class="text">Order Processed</span></div>
        <div class="step active"><span class="icon"><i class="fa fa-truck"></i></span><span class="text">Picked by Courier</span></div>
        <div class="step <?= ($status == 'Order En Route' || $status == 'Order Arrived') ? 'active' : '' ?>"><span class="icon"><i class="fa fa-shipping-fast"></i></span><span class="text">Order En Route</span></div>
        <div class="step <?= ($status == 'Order Arrived') ? 'active' : '' ?>"><span class="icon"><i class="fa fa-home"></i></span><span class="text">Order Arrived</span></div>
    </div>
    <a href="index.php"><button class="back-button">Back</button></a>
</div>

</body>
</html>
