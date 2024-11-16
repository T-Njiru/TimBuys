<?php
include_once('cart_functions.php'); 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productID = intval($_POST['product_id']);
    removeFromCart($productID);
    header("Location: ../Module1/home.php");
    exit();
} else {
    echo "Invalid request.";
}
?>
