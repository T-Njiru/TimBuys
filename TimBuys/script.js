// script.js

// Array to store the products
let products = [];

// Add event listener for posting an item
document.getElementById('post-item-form').addEventListener('submit', function(event) {
    event.preventDefault();

    // Get item details from form
    let itemName = document.getElementById('item-name').value;
    let itemPrice = document.getElementById('item-price').value;
    let itemDescription = document.getElementById('item-description').value;

    // Create a product object
    let newItem = {
        name: itemName,
        price: itemPrice,
        description: itemDescription
    };

    // Add product to the products array
    products.push(newItem);

    // Clear the form
    document.getElementById('post-item-form').reset();

    // Display products
    displayProducts();
});

// Function to display products
function displayProducts() {
    let productList = document.getElementById('product-list');
    productList.innerHTML = ''; // Clear previous items

    products.forEach((product, index) => {
        let productDiv = document.createElement('div');
        productDiv.classList.add('product-item');
        productDiv.innerHTML = `
            <h3>${product.name}</h3>
            <p>${product.description}</p>
            <p>Price: $${product.price}</p>
            <button onclick="addToCart(${index})">Add to Cart</button>
        `;
        productList.appendChild(productDiv);
    });
}

// Shopping cart array
let cart = [];

// Function to add products to cart
function addToCart(productIndex) {
    cart.push(products[productIndex]);
    alert('Item added to cart!');
    displayCart();
}

// Function to display cart items
function displayCart() {
    let cartItems = document.getElementById('cart-items');
    cartItems.innerHTML = '';

    cart.forEach((item, index) => {
        let cartItemDiv = document.createElement('div');
        cartItemDiv.classList.add('cart-item');
        cartItemDiv.innerHTML = `
            <h4>${item.name}</h4>
            <p>Price: $${item.price}</p>
        `;
        cartItems.appendChild(cartItemDiv);
    });
}

