<?php
include_once('cart_functions.php'); 
?>
<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Your Cart</h1>
    <a href="../Module2/listing/product_listing.php" class="btn btn-secondary mb-3">Continue Shopping</a> <!-- Correct path -->
    <?php
    $cartItems = getCartItems();

    if (!empty($cartItems)) {
        echo '<table class="table table-bordered">';
        echo '<thead class="table-dark">';
        echo '<tr>';
        echo '<th>Product Name</th>';
        echo '<th>Price (KSh)</th>';
        echo '<th>Quantity</th>';
        echo '<th>Total (KSh)</th>';
        echo '<th>Action</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        $grandTotal = 0;
        foreach ($cartItems as $item) {
            $total = $item['Price'] * $item['Quantity'];
            $grandTotal += $total;
            echo '<tr>';
            echo '<td>' . htmlspecialchars($item['ProductName']) . '</td>';
            echo '<td>' . htmlspecialchars($item['Price']) . '</td>';
            echo '<td>' . htmlspecialchars($item['Quantity']) . '</td>';
            echo '<td>' . htmlspecialchars($total) . '</td>';
            echo '<td>';
            echo '<form method="POST" action="remove_from_cart.php" onsubmit="return confirm(\'Are you sure you want to remove this item?\');">';
            echo '<input type="hidden" name="product_id" value="' . htmlspecialchars($item['ProductID']) . '">';
            echo '<button type="submit" class="btn btn-danger btn-sm">Remove</button>';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
        }

        echo '<tr>';
        echo '<td colspan="3" class="text-end"><strong>Grand Total:</strong></td>';
        echo '<td colspan="2"><strong>KSh ' . htmlspecialchars($grandTotal) . '</strong></td>';
        echo '</tr>';

        echo '</tbody>';
        echo '</table>';
        echo '<a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>'; // Optional
    } else {
        echo '<p>Your cart is empty.</p>';
    }
    ?>
</div>

</body>
</html>
