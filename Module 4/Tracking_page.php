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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <link rel="stylesheet" href="Tracking_page.css">
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
    // Your API Key is embedded in the URL
    $url = "https://api.geoapify.com/v1/geocode/search?text=" . urlencode($address) . "&apiKey=2260ba8f348a499aa8684d2a0d335755";

    // Fetch the API response
    $response = @file_get_contents($url);

    // Check if the response is successful
    if ($response === FALSE) {
        return null; // Return null if the API call fails
    }

    $json = json_decode($response, true);

    // Extract coordinates if available
    if (isset($json['features'][0]['geometry']['coordinates'])) {
        return [
            'lat' => $json['features'][0]['geometry']['coordinates'][1], // Latitude
            'lng' => $json['features'][0]['geometry']['coordinates'][0], // Longitude
        ];
    }

    return null; // Return null if no coordinates found
}



// Get OrderID from query parameters
$OrderID = isset($_GET['OrderID']) ? $_GET['OrderID'] : null;

if (!$OrderID) {
    die("Error: Order ID not found in URL parameters.");
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'TimBuys');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to retrieve order details
$sql = "SELECT 
            o.CustomerID AS CustomerID, 
            o.OrderDate, 
            o.Address AS CustomerFullAddress,
            op.Status, 
            op.OrderID, 
            v.Address AS VendorAddress
        FROM orders o
        JOIN orderedproduct op ON o.OrderID = op.OrderID
        JOIN VendorProduct vp ON op.VendorProductID = vp.VendorProductID
        JOIN Vendor v ON vp.VendorID = v.VendorID
        WHERE o.OrderID = ?";

// Prepare and execute the query
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

    $startCoordinates = getCoordinates($vendorAddress);
    $endCoordinates = getCoordinates($customerFullAddress);

    $response = [
        'start' => $startCoordinates,
        'end' => $endCoordinates,
        'order_date' => $orderDate,
        'status' => $status,
        'tracking_number' => $trackingNumber
    ];
} else {
    die("No records found for Order ID: $OrderID");
}

$conn->close();
?>

<script>
async function initMap() {
    const data = <?= json_encode($response) ?>;

    // Validate start and end coordinates
    if (!data.start || !data.end) {
        console.error("Coordinates are missing.");
        document.getElementById("map").innerHTML = "<p>Map data unavailable.</p>";
        return;
    }

    const start = [parseFloat(data.start.lat), parseFloat(data.start.lng)];
    const end = [parseFloat(data.end.lat), parseFloat(data.end.lng)];

    // Initialize the map centered on the start location
    const map = L.map("map").setView(start, 13);

    // Add OpenStreetMap tiles
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
        attribution: "&copy; <a href='https://www.openstreetmap.org/copyright'>OpenStreetMap</a>"
    }).addTo(map);

    // Add a polyline between start and end points
    const route = L.polyline([start, end], { color: "#daa520", weight: 5 }).addTo(map);

    // Add markers with popups for the start and end locations
    L.marker(start).addTo(map).bindPopup("Picked Up Location").openPopup();
    L.marker(end).addTo(map).bindPopup("Delivery Destination").openPopup();

    // Adjust map view to fit the route
    map.fitBounds(route.getBounds());
}


async function updateOrderStatus() {
    const orderID = <?= json_encode($OrderID) ?>;

    const fetchAndUpdateStatus = async () => {
        const response = await fetch(`getOrderStatus.php?OrderID=${orderID}`);
        const data = await response.json();
        updateStatusElements(data);

        if (data.status !== currentStatus) {
            currentStatus = data.status;
        }
    };

    let currentStatus = "<?= $status ?>";
    await fetchAndUpdateStatus();
    setInterval(fetchAndUpdateStatus, 30000);
}

function updateStatusElements(data) {
    document.querySelector(".status").innerText = data.status;
    document.querySelector(".estimated-date").innerText = data.estimatedDate;
    document.querySelector(".tracking-number").innerText = data.trackingNumber;

    const steps = document.querySelectorAll(".step");
    steps.forEach(step => {
        if (step.querySelector(".text").innerText === data.status) {
            step.classList.add("active");
        } else {
            step.classList.remove("active");
        }
    });
}

window.onload = () => {
    initMap();
    updateOrderStatus();
};
</script>

<div class="Tracking">
    <h2>My Order</h2>
    <span class="line"></span>
    <h4>Order ID: <?= $OrderID ?></h4>
    <div id="card">
        <div class="Card">
            <div class="col"><strong>Estimated Delivery Date:</strong> <br><?= date('Y-m-d H:i:s', strtotime('+2 days', strtotime($orderDate))) ?></div>
            <div class="col"><strong>Shipping BY:</strong> <br> TIM BUYS</div>
            <div class="col"><strong>Status:</strong> <br> <span class="status"><?= $status ?></span></div>
            <div class="col"><strong>Tracking #:</strong> <br> <span class="tracking-number"><?= $trackingNumber ?></span></div>
        </div>
    </div>
    <div id="map" style="height: 400px;"></div>
    <div class="track">
        <div class="step <?= ($status == 'Processed' || $status == 'Order Picked by Courier' || $status == 'Order En Route' || $status == 'Order Arrived') ? 'active' : '' ?>"><span class="icon"><i class="fa fa-check"></i></span><span class="text">Order Processed</span></div>
        <div class="step <?= ($status == 'Order Picked by Courier' || $status == 'Order En Route' || $status == 'Order Arrived') ? 'active' : '' ?>"><span class="icon"><i class="fa fa-truck"></i></span><span class="text">Picked by Courier</span></div>
        <div class="step <?= ($status == 'Order En Route' || $status == 'Order Arrived') ? 'active' : '' ?>"><span class="icon"><i class="fa fa-shipping-fast"></i></span><span class="text">Order En Route</span></div>
        <div class="step <?= ($status == 'Order Arrived') ? 'active' : '' ?>"><span class="icon"><i class="fa fa-home"></i></span><span class="text">Order Arrived</span></div>
    </div>
    <a href="home.php"><button class="back-button">Back</button></a>
</div>

</body>
</html>

