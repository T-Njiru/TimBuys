<?php
// Include the Database connection class
require_once 'connection.php';

// Create a Database object and establish a connection
$database = new Database();
$pdo = $database->getConnection();

// Vendor registration logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $contact = $_POST['contact'];

    // Check if the connection is successful
    if ($pdo) {
        // Prepare and execute the SQL query
        $stmt = $pdo->prepare("INSERT INTO Vendor (Name, Address, Email, Password, Contact) VALUES (?, ?, ?, ?, ?)");
        
        // Check for successful execution
        if ($stmt->execute([$name, $address, $email, $password, $contact])) {
            // Redirect to the login page after successful registration
            header("Location: vendorlogin.php"); // Change 'login.php' to the actual path of your login page
            exit(); // Terminate the script after redirection
        } else {
            echo "<p class='error' style='color: red;'>Error: Unable to register vendor. Please try again later.</p>";
        }
    } else {
        echo "<p class='error' style='color: red;'>Database connection error. Please try again later.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Vendor Registration</title>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
    />
    <style>
        body {
            background-color: #f9f9f9;
        }
        .signup-wrapper {
            max-width: 500px;
            margin: 80px auto;
            background-color: white;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .form-title {
            margin-bottom: 20px;
            font-size: 24px;
            color: orange;
        }
    </style>
    <script>
        function validatePasswords() {
            const password = document.querySelector('input[name="password"]').value;
            const confirmPassword = document.querySelector('input[name="confirm_password"]').value;
            if (password !== confirmPassword) {
                alert("Passwords do not match!");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="signup-wrapper">
        <form action="vendor.php" method="post" onsubmit="return validatePasswords()">
            <h2 class="form-title">Vendor Registration</h2>
        
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" class="form-control" required />
            </div>
            
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" name="address" class="form-control" required />
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required />
            </div>
            
            <div class="mb-3">
                <label for="contact" class="form-label">Contact</label>
                <input type="text" name="contact" class="form-control" required />
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required />
            </div>
            
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required />
            </div>
            
            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
