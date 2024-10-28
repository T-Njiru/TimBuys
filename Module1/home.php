<?php
session_start();
include_once 'connection.php'; // Include your connection file

// Create a new instance of the Database class
$database = new Database();
$pdo = $database->getConnection(); // Get the PDO connection

// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {
    header('Location: login.html'); // Redirect to login if not logged in
    exit();
}

// Fetch customer details
$customer_id = $_SESSION['customer_id'];
$stmt = $pdo->prepare("SELECT * FROM Customer WHERE CustomerID = :customer_id");
$stmt->bindParam(':customer_id', $customer_id);
$stmt->execute();
$customer = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tim Buys - Home</title>
    <link rel="stylesheet" href="styles/homephp.css">
</head>
<body>
<header>
<div class="logo">TIM BUYS</div>
    <nav>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="cart.php">Cart</a></li>
            <li><a href="profile.php">Profile</li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="navbar">
    <div class="search-bar">
        <form action="search_results.php" method="GET">
            <input type="text" name="query" placeholder="Search products..." required>
            <select name="category">
                <option value="">All Categories</option>
                <option value="electronics">Electronics</option>
                <option value="fashion">Fashion</option>
                <option value="books">Books</option>
                <option value="furniture">Furniture</option>
            </select>
            <button type="submit">Search</button>
        </form>
    </div>
</div>

      </div>
</div>
</header>
        

    <!-- Categories -->
    <section class="categories">
      <p>CATEGORIES</p>
      <button>TVs & Accessories</button>
      <button>Phones & Tablets</button>
      <button>Appliances</button>
      <button>Health & Beauty</button>
      <button>Fashion</button>
      <button>Computing</button>
      <button>Other Categories</button>
    </section>

    <!-- Hero Section -->
    <section class="hero">
      <div class="hero-content">
        <h1>Let us take care of all your shopping needs!</h1>
        <button>SHOP NOW</button>
      </div>
    </section>

    <div class="banner">
      <h1>Elevate your style with 20% off</h1>
      <p>Shop these essentials to complete your look</p>
      <button>SHOP NOW</button>
    </div>
    <div class="most-viewed">
      <h2>Most Viewed Items</h2>
      <div class="most-viewed-items">
        <div class="most-viewed-item">
          <img
            alt="Nike Air Jordan 1 Mid 'Canyon'"
            height="200"
            src="https://storage.googleapis.com/a1aa/image/WgtDTf9HcpxWcChaiRg41Mk1ZuTLzKo8gC0hyBqcYrbW4ZzJA.jpg"
            width="200"
          />
          <p>Nike Air Jordan 1 Mid 'Canyon'</p>
          <p>$120</p>
        </div>
        <div class="most-viewed-item">
          <img
            alt="Chunky Gemstone Rings"
            height="200"
            src="https://storage.googleapis.com/a1aa/image/4p09IveCkmR8YSjQwCePCyAO3fKj3WEW1u8XwifzGBMFDPbOB.jpg"
            width="200"
          />
          <p>Chunky Gemstone Rings</p>
          <p>$30</p>
        </div>
        <div class="most-viewed-item">
          <img
            alt="Apple AirPods"
            height="200"
            src="https://storage.googleapis.com/a1aa/image/92bFe8fIx2rN0EJ011yiaRAh8K25dxbj6lwSyWaOe4YUhnNnA.jpg"
            width="200"
          />
          <p>Apple AirPods</p>
          <p>$150</p>
        </div>
        <div class="most-viewed-item">
          <img
            alt="Ultimate Perfume Stand"
            height="200"
            src="https://storage.googleapis.com/a1aa/image/18FfkAQnlqVjBKUH8MfVf13tAq29CPM100fUvXSeMzihGes5E.jpg"
            width="200"
          />
          <p>Ultimate Perfume Stand</p>
          <p>$80</p>
        </div>
      </div>
    </div>
    <div class="flash-sales">
      <h2>Flash Sales upto 50% off</h2>
      <p class="time-left">Time Left: 4d : 12hrs</p>
      <a href="#" style="color: white"> See all </a>
    </div>
    <div class="flash-sales-items">
      <div class="flash-sales-item">
        <img
          alt="All The Light We Cannot See"
          height="200"
          src="https://storage.googleapis.com/a1aa/image/fbiP5RrQBk1Vc6KnnFSYDHvXnFuj2RV4vHlqXF1lcGevwzmTA.jpg"
          width="200"
        />
        <p>All The Light We Cannot See</p>
        <p>$10</p>
      </div>
      <div class="flash-sales-item">
        <img
          alt="Writing Set for Journaling"
          height="200"
          src="https://storage.googleapis.com/a1aa/image/O6Q6MlpFmi4sL1ugp9veZ1VfjUyAvzYeOVCScHdeKVsDDPbOB.jpg"
          width="200"
        />
        <p>Writing Set for Journaling</p>
        <p>$15</p>
      </div>
      <div class="flash-sales-item">
        <img
          alt="Leather Notebook"
          height="200"
          src="https://storage.googleapis.com/a1aa/image/UP7VaLhKQhJMG9onC1jjuea2BeFCcb8OnYp4mvBRTnizwzmTA.jpg"
          width="200"
        />
        <p>Leather Notebook</p>
        <p>$20</p>
      </div>
      <div class="flash-sales-item">
        <img
          alt="Phone Case with Marble Design"
          height="200"
          src="https://storage.googleapis.com/a1aa/image/dNrqxCz4O6oSFldveDzBMZTdu1d8DIfpnaDUtfnKuFhbhnNnA.jpg"
          width="200"
        />
        <p>Phone Case with Marble Design</p>
        <p>$12</p>
      </div>
    </div>
    <div class="features">
      <div class="section">
        <div class="card" style="background-color: #d4a017">
          <h3>Upgraded College Living</h3>
          <img
            alt="Laptop"
            height="50"
            src="https://storage.googleapis.com/a1aa/image/iqrTXbyT6SKnDpiCpQnJfHqeqroqywm0tVA4rMXLVCkGp6mTA.jpg"
            width="50"
          />
          <p>Shop all college Tech</p>
          <img
            alt="Dorm items"
            height="50"
            src="https://storage.googleapis.com/a1aa/image/LjY12U7sowqkGNVlfBkhgPdeJoN65kmeVzUX0dZqUHheiqbOB.jpg"
            width="50"
          />
          <p>Improve your Dorm/Apartment</p>
          <img
            alt="Watch"
            height="50"
            src="https://storage.googleapis.com/a1aa/image/GT0shX74XeSsTKUbfnj5DXT4nf6QtGug0W8SLF53d4uRS1NnA.jpg"
            width="50"
          />
          <p>Prioritize your mind and body</p>
        </div>
        <div class="card" style="background-color: #d4a017">
          <h3>Back To School</h3>
          <img
            alt="Bag"
            height="50"
            src="https://storage.googleapis.com/a1aa/image/LDGQnfqzZM1NNq1TUct0a0q2qFRd7e7vPp4nvfV72ZEoR1NnA.jpg"
            width="50"
          />
          <p>Deals on Bags</p>
          <img
            alt="School supplies"
            height="50"
            src="https://storage.googleapis.com/a1aa/image/CcUlChKDXSZCEZjehwfenRqaB2GqGky4PH5Sk2L8rTnXS1NnA.jpg"
            width="50"
          />
          <p>Deals on school supplies</p>
          <img
            alt="Notes"
            height="50"
            src="https://storage.googleapis.com/a1aa/image/jeXPrFJIR6SQdqjEvh28B7SrZq9UzlJjb3eZ8DEYe8gPS1NnA.jpg"
            width="50"
          />
          <p>Improve your notes</p>
        </div>
        <div class="card" style="background-color: #d4a017">
          <h3>Fashion on a Budget</h3>
          <img
            alt="Clothes"
            height="50"
            src="https://storage.googleapis.com/a1aa/image/isQS732bUAq5EZGpxSuK5ElXAdfnd3NhtbpnHAznw2FlUdzJA.jpg"
            width="50"
          />
          <p>Clothes under $10</p>
          <img
            alt="Sale items"
            height="50"
            src="https://storage.googleapis.com/a1aa/image/pYBGfWls7DXhRiTUaZR1KzKovj465cln90K5mPfjaCJ2o6mTA.jpg"
            width="50"
          />
          <p>Sales upto 50% off</p>
          <img
            alt="Shoes"
            height="50"
            src="https://storage.googleapis.com/a1aa/image/Narfu1mUc9zZJiifmnwYWO4635arHEhE4rv9x9TPGrW9o6mTA.jpg"
            width="50"
          />
          <p>Deals $10-$40</p>
        </div>
      </div>
      <div class="featured-products">
        <h2>Featured products</h2>
        <div class="product-list">
          <div class="product-item">
            <img
              alt="Oral-B io electric brush"
              height="200"
              src="https://storage.googleapis.com/a1aa/image/hNzxpnFJBrrbKJONAHSdSNMTrLpenfQ9r8EJhpFU8hi5o6mTA.jpg"
              width="200"
            />
            <p>Oral-B io electric brush</p>
            <span> $60 </span>
          </div>
          <div class="product-item">
            <img
              alt="Apple Lightning Charger"
              height="200"
              src="https://storage.googleapis.com/a1aa/image/wQSolPu6dCpvGhlTLxO6RQCGhFxYQIyiaruU0nEsfieEp6mTA.jpg"
              width="200"
            />
            <p>Apple Lightning Charger</p>
            <span> $45 </span>
          </div>
          <div class="product-item">
            <img
              alt="Adjustable Table Lamp"
              height="200"
              src="https://storage.googleapis.com/a1aa/image/eVWrs50janWHJiyfCkqeKGa7YFx6SiSgmxxLb89XyeAxjqbOB.jpg"
              width="200"
            />
            <p>Adjustable Table Lamp</p>
            <span> $75 </span>
          </div>
          <div class="product-item">
            <img
              alt="Canvas Modular Bookshelf"
              height="200"
              src="https://storage.googleapis.com/a1aa/image/ZfQHu8Hm69SVYCe5DpcEdTCTY0x8MekHfe0sBsjP2aJZHV3cC.jpg"
              width="200"
            />
            <p>Canvas Modular Bookshelf</p>
            <span> $300 </span>
          </div>
        </div>
      </div>
      <div class="new-arrivals">
        <h2>New Arrivals</h2>
        <div class="product-list">
          <div class="product-item">
            <img
              alt="Cerave Hydrating Facial Cleanser"
              height="200"
              src="https://storage.googleapis.com/a1aa/image/blPeV28WvNR8IqYDxkhx24lziePmjehV2EG3tulnXGFBS1NnA.jpg"
              width="200"
            />
            <p>Cerave Hydrating Facial Cleanser</p>
            <span> $60 </span>
          </div>
          <div class="product-item">
            <img
              alt="Cognac Brown Minimalist watch"
              height="200"
              src="https://storage.googleapis.com/a1aa/image/aPbU3HlGbZokPxW2IM2aNCnSqIi83KyIjKsxWDDwYuxNqu5E.jpg"
              width="200"
            />
            <p>Cognac Brown Minimalist watch</p>
            <span> $75 </span>
          </div>
          <div class="product-item">
            <img
              alt="Givenchy Gentleman Perfume"
              height="200"
              src="https://storage.googleapis.com/a1aa/image/vwSgqtzrZs7gElv1de0Qkg2I2zPtUM747NYsQmjAlFZYUdzJA.jpg"
              width="200"
            />
            <p>Givenchy Gentleman Perfume</p>
            <span> $225 </span>
          </div>
          <div class="product-item">
            <img
              alt="Stainless Steel Rings"
              height="200"
              src="https://storage.googleapis.com/a1aa/image/9Mx5WhMMimojFpM7RcN84wsoaZIq9vg4ZSxzmLjK5e6hUdzJA.jpg"
              width="200"
            />
            <p>Stainless Steel Rings</p>
            <span> $10 </span>
          </div>
        </div>
      </div>
    </div>

    <footer>
      <div class="container">
        <div class="column">
          <h2>NEED HELP? Chat With Us</h2>
          <ul>
            <li>Help Centre</li>
            <li>Contact Us</li>
          </ul>
          <h2>USEFUL LINKS</h2>
          <ul>
            <li>Track Your Order</li>
            <li>Shipping and Delivery</li>
            <li>Pick-Up Stations</li>
            <li>Return Policy</li>
            <li>How to Order</li>
            <li>Dispute Resolution Policy</li>
            <li>Corporate and Bulk Purchase</li>
            <li>Advertise with Tim Buys</li>
            <li>Report a Product</li>
            <li>Tim Buys Payment Information Guidelines</li>
          </ul>
        </div>
        <div class="column">
          <h2>ABOUT TIM BUYS</h2>
          <ul>
            <li>About Us</li>
            <li>Return and Refunds Policy</li>
            <li>Terms and Conditions</li>
            <li>Store Credit Terms and Conditions</li>
            <li>Privacy Notice</li>
            <li>Cookies Notice</li>
            <li>Flash Sales</li>
          </ul>
        </div>
        <div class="column">
          <h2>MAKE MONEY WITH TIM BUYS</h2>
          <ul>
            <li>Sell on Tim Buys</li>
            <li>Vendor Hub</li>
          </ul>
        </div>
      </div>
    </footer>

