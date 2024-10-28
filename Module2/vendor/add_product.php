<?php
// Remove session requirement for `VendorID`
$vendor_id = 1; // Set to a default vendor ID if needed

// Check if form is submitted
if (isset($_POST['submit'])) {
    $product_name = $_POST['product_name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $description = $_POST['description'];

    // Image upload handling
    $product_image = $_FILES['product_image']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($product_image);

    // Ensure the uploads directory exists, create if not
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);  // 0777 gives full permissions
    }

    // Move the uploaded file
    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
        // Database connection
        $conn = new mysqli('localhost:3307', 'root', '', 'timbuys_database');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Insert into product table
        $stmt = $conn->prepare("INSERT INTO product (ProductName, CategoryID, ProductImage) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $product_name, $category_id, $target_file);
        $stmt->execute();
        $product_id = $conn->insert_id; // Get the last inserted ProductID
        $stmt->close();

        // Insert into vendorproduct table (optional if VendorID is not required)
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
