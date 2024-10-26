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

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

    <script>
        function initMap() {
            // Mock data representing Strathmore University in Nairobi
            var data = {
                latitude: "-1.3105",  // Strathmore University Latitude
                longitude: "36.8148"  // Strathmore University Longitude
            };

            var location = [parseFloat(data.latitude), parseFloat(data.longitude)];
            var map = L.map('map').setView(location, 15); // Zoom level set to 15 for a closer view

            // Adding OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Adding a marker for Strathmore University
            L.marker(location).addTo(map)
                .bindPopup('Current Location: Strathmore University, Nairobi')
                .openPopup();
        }

        // Load the map when the page is fully loaded
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
    <h4>Order ID: BC123FD456</h4>
    <div id="card">
    <div class="Card">
      <div class="col"> <strong>Estimated Delivery Date:</strong> <br>25/10/2024 </div>
        <div class="col"> <strong>Shipping BY:</strong> <br> TIM BUYS </div>
        <div class="col"> <strong>Status:</strong> <br> Picked by the courier </div>
        <div class="col"> <strong>Tracking #:</strong> <br> BD045903594059 </div>
    </div>
  </article>
  <div class="track">
    <div class="step active">
        <span class="icon"> <i class="fa fa-check"></i> </span>
        <span class="text">Order Processed</span>
    </div>
    <div class="step active">
        <span class="icon"> <i class="fa fa-truck"></i> </span>
        <span class="text">Order Shipped</span>
    </div>
    <div class="step active">
        <span class="icon"> <i class="fa fa-shipping-fast"></i> </span>
        <span class="text">Order En Route</span>
    </div>
    <div class="step active">
        <span class="icon"> <i class="fa fa-home"></i> </span>
        <span class="text">Order Arrived</span>
    </div>
</div>
<button class="back-button">Back to Orders</button>
</div>
        
        <!-- Leaflet Map Container -->
        <div id="map" style="height: 400px; width: 100%;"></div>
        
</body>
</html>
