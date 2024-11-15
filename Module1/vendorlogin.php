<?php
// Include the Database connection class
require_once 'connection.php';

// Create a Database object and establish a connection
$database = new Database();
$pdo = $database->getConnection();

// Start the session
session_start();

// Login logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the connection is successful
    if ($pdo) {
        // Prepare and execute the SQL query
        $stmt = $pdo->prepare("SELECT * FROM Vendor WHERE Email = ?");
        $stmt->execute([$email]);
        $vendor = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the password
        if ($vendor && password_verify($password, $vendor['Password'])) {
            // Store vendor information in session variables
            $_SESSION['vendor_id'] = $vendor['VendorID'];
            $_SESSION['vendor_name'] = $vendor['Name'];
            
            // Redirect to a dashboard or homepage after successful login
            header("Location: ../Module2/vendor/vendor.php"); // Change 'dashboard.php' to the appropriate page
            exit();
        } else {
            $error_message = "Invalid email or password. Please try again.";
        }
    } else {
        $error_message = "Database connection error. Please try again later.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login | TimBuys Seller</title>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
    />
    <style>
        body {
            background: linear-gradient(135deg, #fceabb 0%, #f8b500 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        .login-wrapper {
            max-width: 400px;
            margin: 100px auto;
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }

        .form-title {
            font-size: 26px;
            color: #f39c12;
            text-align: center;
            margin-bottom: 25px;
        }

        .form-label {
            color: #333;
        }

        .form-control {
            font-size: 16px;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #f39c12;
            border: none;
            font-size: 16px;
            padding: 12px;
            border-radius: 8px;
        }

        .btn-primary:hover {
            background-color: #d87d02;
        }

        .reset-password {
            display: block;
            margin-top: 15px;
            text-align: right;
            color: black;
            text-decoration: none;
            font-size: 14px;
        }

        .reset-password:hover {
            text-decoration: underline;
        }

        .alert-danger {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <h2 class="form-title">Login</h2>
        <form action="vendorlogin.php" method="POST">
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required />
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required />
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
            <a href="http://localhost/TimBuys/module1/reset_password.php" class="reset-password">Forgot Password?</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
