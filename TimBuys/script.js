
let products = [];


document.getElementById('post-item-form').addEventListener('submit', function(event) {
    event.preventDefault();

   
    let itemName = document.getElementById('item-name').value;
    let itemPrice = document.getElementById('item-price').value;
    let itemDescription = document.getElementById('item-description').value;

    
    let newItem = {
        name: itemName,
        price: itemPrice,
        description: itemDescription
    };

    
    products.push(newItem);

    
    document.getElementById('post-item-form').reset();

    
    displayProducts();
});


function displayProducts() {
    let productList = document.getElementById('product-list');
    productList.innerHTML = '';

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


let cart = [];


function addToCart(productIndex) {
    cart.push(products[productIndex]);
    alert('Item added to cart!');
    displayCart();
}


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

