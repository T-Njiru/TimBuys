<?php
// Start session to retrieve the user's name if logged in
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TimBuys - Online Store</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
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
                Please read them carefully...`
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
                Léelos atentamente...`
            }
        };

        function changeLanguage(language) {
            document.title = translations[language].title;
            document.getElementById('categoriesTitle').innerText = translations[language].categoriesTitle;
            document.getElementById('featuredProductsTitle').innerText = translations[language].featuredProductsTitle;
            document.getElementById('discountedProductsTitle').innerText = translations[language].discountedProductsTitle;
            document.getElementById('feedbackTitle').innerText = translations[language].feedbackTitle;
            document.getElementById('subscribeTitle').innerText = translations[language].subscribeTitle;
            document.getElementById('termsTitle').innerText = translations[language].termsTitle;
            document.getElementById('termsContent').innerText = translations[language].termsContent;
        }

        function showTermsPopup() {
            document.getElementById('termsPopup').style.display = 'block';
        }

        function hideTermsPopup() {
            document.getElementById('termsPopup').style.display = 'none';
            localStorage.setItem('termsAccepted', 'true');
        }

        function agreeToTerms() {
            const checkbox = document.getElementById('termsCheckbox');
            if (checkbox.checked) {
                alert('Thank you for agreeing to the terms and conditions!');
                hideTermsPopup();
            } else {
                alert('Please agree to the terms and conditions to proceed.');
            }
        }

        function checkTermsAcceptance() {
            const accepted = localStorage.getItem('termsAccepted');
            if (!accepted) {
                showTermsPopup();
            }
        }

        window.onload = function() {
            checkTermsAcceptance();
            changeLanguage('en'); // Default to English
        }
    </script>
</head>
<body>

<header>
    <div class="container">
        <h1>TimBuys</h1>
        <nav>
            <ul>
                <li>
                    <select id="languageSelector" onchange="changeLanguage(this.value)">
                        <option value="en">English</option>
                        <option value="es">Spanish</option>
                    </select>
                </li>
                <li><a href="#">Home</a></li>
                <li><a href="#">Deals</a></li>
                <li><a href="#">Products</a></li>
                <li><a href="#">About Us</a></li>

                <!-- PHP to display user's first name if logged in -->
                <?php
                if (isset($_SESSION['first_name'])) {
                    echo '<li>Welcome, ' . htmlspecialchars($_SESSION['first_name']) . '</li>';
                    echo '<li><a href="logout.php" class="btn">Logout</a></li>';
                } else {
                    echo '<li><a href="index.html" class="btn">Sign Up</a></li>';
                    echo '<li><a href="login.php" class="btn">Sign In</a></li>';
                }
                ?>

                <li>
                    <a href="javascript:void(0)" class="message-icon" onclick="toggleMessages()">
                        📬 <span class="notification-count">3</span>
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="notification-icon" onclick="showNotification()">
                        🔔
                    </a>
                </li>
            </ul>
        </nav>
        <div class="search-bar">
            <input type="text" placeholder="Search for products...">
            <button>Search</button>
        </div>
    </div>
</header>

<div id="messageArea" class="message-display">
    <h3>Messages</h3>
    <ul>
        <li>Welcome to TimBuys! Enjoy your shopping.</li>
        <li>Don't miss our upcoming sales!</li>
        <li>Your feedback is valuable to us!</li>
    </ul>
</div>

<main>
    <section class="banner">
        <img src="banner.jpg" alt="Special Offer" class="banner-img">
    </section>

    <section class="categories">
        <h2 id="categoriesTitle">Product Categories</h2>
        <div class="category-list">
            <div class="category">Electronics</div>
            <div class="category">Fashion</div>
            <div class="category">Home & Garden</div>
            <div class="category">Sports</div>
        </div>
    </section>

    <section class="product-list">
        <h2 id="discountedProductsTitle">Discounted Products</h2>
        <div class="grid-container">
            <article class="product">
                <img src="product1.jpg" alt="Product 1">
                <h2>Product 1</h2>
                <div class="price">
                    <span class="original-price">$39.99</span>
                    <span class="new-price">$29.99</span>
                </div>
                <button>Add to Cart</button>
                <button>Wishlist</button>
            </article>
            <article class="product">
                <img src="product2.jpg" alt="Product 2">
                <h2>Product 2</h2>
                <div class="price">
                    <span class="original-price">$49.99</span>
                    <span class="new-price">$39.99</span>
                </div>
                <button>Add to Cart</button>
                <button>Wishlist</button>
            </article>
        </div>
    </section>

    <section class="product-list">
        <h2 id="featuredProductsTitle">Featured Products</h2>
        <div class="grid-container">
            <article class="product">
                <img src="product3.jpg" alt="Product 3">
                <h2>Product 3</h2>
                <div class="price">
                    <span class="new-price">$59.99</span>
                </div>
                <button>Add to Cart</button>
                <button>Wishlist</button>
            </article>
            <article class="product">
                <img src="product4.jpg" alt="Product 4">
                <h2>Product 4</h2>
                <div class="price">
                    <span class="new-price">$69.99</span>
                </div>
                <button>Add to Cart</button>
                <button>Wishlist</button>
            </article>
        </div>
    </section>

    <section class="feedback-area">
        <h2 id="feedbackTitle">User Feedback</h2>
        <textarea rows="4" placeholder="Leave your feedback..."></textarea>
        <button>Submit Feedback</button>
        <div class="feedback-display">
            <h3>Recent Feedback</h3>
            <p>"Great shopping experience!" - User</p>
            <p>"Love the discounts!" - User</p>
        </div>
    </section>

    <section class="newsletter">
        <h2 id="subscribeTitle">Subscribe to Our Newsletter</h2>
        <input type="email" placeholder="Enter your email...">
        <button>Subscribe</button>
    </section>
</main>

<footer>
    <div class="container">
        <p>&copy; 2024 TimBuys. All rights reserved.</p>
        <button onclick="showTermsPopup()">Terms and Conditions</button>
    </div>
</footer>

<!-- Terms Popup -->
<div id="termsPopup" class="popup">
    <div class="popup-content">
        <span class="close" onclick="hideTermsPopup()">&times;</span>
        <h2 id="termsTitle">Terms and Conditions</h2>
        <p id="termsContent"></p>
        <input type="checkbox" id="termsCheckbox"> I agree to the terms and conditions
        <br><br>
        <button onclick="agreeToTerms()">Accept</button>
        <button onclick="hideTermsPopup()">Decline</button>
    </div>
</div>

</body>
</html>
