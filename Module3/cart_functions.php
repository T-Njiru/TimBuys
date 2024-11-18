<?php
//session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add a product to the cart
function addToCart($product) {
    $productID = $product['ProductID'];
    if (isset($_SESSION['cart'][$productID])) {
        $_SESSION['cart'][$productID]['Quantity'] += 1;
    } else {
        $_SESSION['cart'][$productID] = $product;
        $_SESSION['cart'][$productID]['Quantity'] = 1;
    }
}

// Function to remove a product from the cart
function removeFromCart($productID) {
    if (isset($_SESSION['cart'][$productID])) {
        unset($_SESSION['cart'][$productID]);
    }
}

// Function to get all cart items
function getCartItems() {
    return $_SESSION['cart'];
}
?>
