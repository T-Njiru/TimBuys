<?php
include_once('cart_functions.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    echo $_POST['product_id'];
    $productID = intval($_POST['product_id']);
echo $productID;
    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'timbuys'); 

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

 
    $stmt = $conn->prepare("SELECT p.ProductID, p.ProductName,p.ProductImage, vp.Price, vp.Quantity, vp.Description, v.Name AS VendorName, c.CategoryName
                            FROM Product p
                            JOIN VendorProduct vp ON p.ProductID = vp.ProductID
                            JOIN Vendor v ON vp.VendorID = v.VendorID
                            JOIN Category c ON p.CategoryID = c.CategoryID
                            WHERE p.ProductID = ?");
    $stmt->bind_param("i", $productID);
    $stmt->execute();
    $result = $stmt->get_result();
    // $product = $result->fetch_assoc();
    // print_r($product);
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        addToCart($product);
        $stmt->close();
        $conn->close();
        header("Location: ../Module1/home.php"); 
        exit();
    } else {
        $stmt->close();
        $conn->close();
        echo "Product not found.";
    }
} else {
    echo "Invalid request.";
}
?>
