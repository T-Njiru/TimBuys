<?php
include_once('cart_functions.php'); 

$cartItems = getCartItems();

if (empty($cartItems)) {
    header("Location: cart.php");
    exit();
}


$_SESSION['cart'] = [];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Thank You for Your Purchase!</h1>
    <p>Your order has been successfully processed.</p>
    <a href="../Module2/listing/product_listing.php" class="btn btn-primary">Continue Shopping</a>
</div>

</body>
</html>
