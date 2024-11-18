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
    $image_extension = pathinfo($product_image, PATHINFO_EXTENSION);
    
    // Set the uploads directory using Windows path
    $target_dir = "C:/xampp/htdocs/TimBuys/uploads/";

    // Ensure the uploads directory exists, create if not
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Define the full path for saving the image
    $target_file = $target_dir . basename($product_image);

    // Define the absolute URL for the image to be stored in the database
    $absolute_url = "http://localhost/TimBuys/uploads/" . basename($product_image);

    // Move the uploaded file to the target directory
    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
        // Database connection
        $conn = new mysqli('localhost:3306', 'root', '', 'timbuys');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Store the absolute URL in the database
        $stmt = $conn->prepare("INSERT INTO product (ProductName, CategoryID, ProductImage) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $product_name, $category_id, $absolute_url);
        $stmt->execute();
        $product_id = $stmt->insert_id;
        $stmt->close();

        // Insert into vendorproduct table
        $stmt = $conn->prepare("INSERT INTO vendorproduct (VendorID, ProductID, Price, Quantity, Description) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iidis", $vendor_id, $product_id, $price, $quantity, $description);
        $stmt->execute();
        $stmt->close();

        echo "<p>Product added successfully with image!</p>";
    } else {
        echo "<p>Failed to upload image.</p>";
    }
}
?>
