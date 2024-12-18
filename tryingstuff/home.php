<?php require_once('../Module3/cart_functions.php');
require_once  'cartcontent.php';
$Cart=new cart();
$Specific=$Cart->load();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tim Buys</title>
    <link rel="stylesheet" href="homepage.css" />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap"
      rel="stylesheet"
    />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <script>
      const translations = {
        en: {
          title: "TimBuys - Online Store",
          categoriesTitle: "Product Categories",
          featuredProductsTitle: "Featured Products",
          discountedProductsTitle: "Discounted Products",
          feedbackTitle: "User Feedback",
          subscribeTitle: "Subscribe to Our Newsletter",
          termsTitle: "Terms and Conditions",
          termsContent: `Welcome to TimBuys. By using our site, you agree to our terms of service. 
                Please read them carefully. 1. You must be at least 18 years old to use our services. 
                2. You agree to provide accurate information when registering. 
                3. We reserve the right to cancel or suspend your account if you violate any terms. 
                4. Our products are sold as described. If you receive a faulty item, please contact us within 30 days. 
                5. We are not responsible for any loss or damage caused by the use of our products.`,
        },
        es: {
          title: "TimBuys - Tienda en Línea",
          categoriesTitle: "Categorías de Productos",
          featuredProductsTitle: "Productos Destacados",
          discountedProductsTitle: "Productos Descuentados",
          feedbackTitle: "Comentarios de Usuarios",
          subscribeTitle: "Suscríbete a Nuestro Boletín",
          termsTitle: "Términos y Condiciones",
          termsContent: `Bienvenido a TimBuys. Al usar nuestro sitio, aceptas nuestros términos de servicio. 
                Léelos atentamente. 1. Debes tener al menos 18 años para usar nuestros servicios. 
                2. Aceptas proporcionar información precisa al registrarte. 
                3. Nos reservamos el derecho de cancelar o suspender tu cuenta si violas alguno de los términos. 
                4. Nuestros productos se venden como se describen. Si recibes un artículo defectuoso, contáctanos dentro de 30 días. 
                5. No somos responsables de ninguna pérdida o daño causado por el uso de nuestros productos.`,
        },
      };

      function changeLanguage(language) {
        document.title = translations[language].title;
        document.getElementById("categoriesTitle").innerText =
          translations[language].categoriesTitle;
        document.getElementById("featuredProductsTitle").innerText =
          translations[language].featuredProductsTitle;
        document.getElementById("discountedProductsTitle").innerText =
          translations[language].discountedProductsTitle;
        document.getElementById("feedbackTitle").innerText =
          translations[language].feedbackTitle;
        document.getElementById("subscribeTitle").innerText =
          translations[language].subscribeTitle;
        document.getElementById("termsTitle").innerText =
          translations[language].termsTitle;
        document.getElementById("termsContent").innerText =
          translations[language].termsContent;
      }

      function showTermsPopup() {
        document.getElementById("termsPopup").style.display = "block";
      }

      function hideTermsPopup() {
        document.getElementById("termsPopup").style.display = "none";
        localStorage.setItem("termsAccepted", "true"); // Store acceptance in local storage
      }

      function agreeToTerms() {
        const checkbox = document.getElementById("termsCheckbox");
        if (checkbox.checked) {
          alert("Thank you for agreeing to the terms and conditions!");
          hideTermsPopup();
        } else {
          alert("Please agree to the terms and conditions to proceed.");
        }
      }

      function checkTermsAcceptance() {
        const accepted = localStorage.getItem("termsAccepted");
        if (!accepted) {
          showTermsPopup();
        }
      }

      window.onload = function () {
        checkTermsAcceptance();
        changeLanguage("en"); // Default to English
      };
    </script>
    <style>
        /* Offcanvas background color matching the hero section (dark brown) */
        .offcanvas {
          background-color: #4a2801; /* Same as hero section */
          color: white; /* Text color white to contrast with dark background */
        }
      
        /* Offcanvas header styling to match the navbar */
        .offcanvas-header {
          background-color: #000; /* Same black background as the navbar */
          padding: 10px 20px; /* Consistent padding */
          color: white; /* White text color */
        }
      
        /* Offcanvas body styling to match other content areas */
        .offcanvas-body {
          padding: 20px; /* Consistent padding with other sections */
          background-color: #d4a017; /* Matching the banner section */
        }
      
        /* Offcanvas close button consistent with navbar button styles */
        .offcanvas-header .btn-close {
          background-color: #fff; /* White background for contrast */
          color: #000; /* Black icon color for visibility */
          padding: 5px;
        }
      
        /* Offcanvas links style matching navbar links */
        .offcanvas a {
          color: white; /* Same as navbar links */
          text-decoration: none;
          font-weight: bold;
        }
      
        /* Hover effect for offcanvas links */
        .offcanvas a:hover {
          color: darkgoldenrod; /* Hover effect consistent with dropdown in navbar */
        }
    </style>
      
  </head>
  <body>
    <div class="navbar">
      <div class="logo">TIM BUYS</div>
      <div class="search-bar">
        <input
          type="text"
          placeholder="Search products, brands and categories"
        />
        <button>Search</button>
      </div>
      <div class="nav-links">
        <div class="dropdown">
          <a href="#" class="account-link">
            <i class="fas fa-user"></i> ACCOUNT
          </a>
          <div class="dropdown-content">
            <button onclick="location.href='login.html';">Sign In</button>
            <button onclick="location.href='../Module1.2/signin.html';">
              Sign Up
            </button>
          </div>
        </div>
        <a href="#">HELP</a>
        <button class="btn " type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions"><a href="#"><i class="fas fa-shopping-cart"></i> CART</a></button>
      </div>
    </div>
  <!--Cart-->
   <?php     
  $Cart->cart($Specific);
  ?>

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

    <script>
      // Optional: Add any JavaScript here if needed for interactivity.
    </script>
  </body>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</html>