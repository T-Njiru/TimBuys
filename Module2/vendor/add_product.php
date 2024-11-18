<?php
session_start(); // Start the session to access `$_SESSION` variables

// Check if the vendor is logged in
if (!isset($_SESSION['vendor_id'])) {
    die("Access denied: Please log in as a vendor.");
}

// Get the logged-in Vendor ID
$vendor_id = $_SESSION['vendor_id'];


// Database connection
$conn = new mysqli('localhost:3306', 'root', '', 'timbuys');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle product deletion
if (isset($_GET['delete_id'])) {
    $product_id = $_GET['delete_id'];

    // Ensure the product belongs to the logged-in vendor before deleting
    $query = "SELECT p.ProductImage 
              FROM product p 
              JOIN vendorproduct v ON p.ProductID = v.ProductID 
              WHERE p.ProductID = ? AND v.VendorID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $product_id, $vendor_id);
    $stmt->execute();
    $stmt->bind_result($product_image);
    $stmt->fetch();
    $stmt->close();

    if ($product_image) {
        // Delete the product image from the server
        if (file_exists("C:/xampp/htdocs/TimBuys/uploads/" . basename($product_image))) {
            unlink("C:/xampp/htdocs/TimBuys/uploads/" . basename($product_image));
        }

        // Delete from the database (both product and vendorproduct tables)
        $stmt = $conn->prepare("DELETE FROM vendorproduct WHERE ProductID = ? AND VendorID = ?");
        $stmt->bind_param("ii", $product_id, $vendor_id);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM product WHERE ProductID = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stmt->close();

        echo "<p>Product deleted successfully!</p>";
    } else {
        echo "<p>You do not have permission to delete this product.</p>";
    }
}

// Handle product addition
if (isset($_POST['submit'])) {
    $product_name = $_POST['product_name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $description = $_POST['description'];

    // Image upload handling
    $product_image = $_FILES['product_image']['name'];
    $target_dir = "C:/xampp/htdocs/TimBuys/uploads/";
    $target_file = $target_dir . basename($product_image);
    $absolute_url = "http://localhost/TimBuys/uploads/" . basename($product_image);

    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
        // Insert into `product` table
        $stmt = $conn->prepare("INSERT INTO product (ProductName, CategoryID, ProductImage) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $product_name, $category_id, $absolute_url);
        $stmt->execute();
        $product_id = $stmt->insert_id;
        $stmt->close();

        // Insert into `vendorproduct` table
        $stmt = $conn->prepare("INSERT INTO vendorproduct (VendorID, ProductID, Price, Quantity, Description) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iidis", $vendor_id, $product_id, $price, $quantity, $description);
        $stmt->execute();
        $stmt->close();

        echo "<p>Product added successfully!</p>";
    } else {
        echo "<p>Failed to upload image.</p>";
    }
}
?>
