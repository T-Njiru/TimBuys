<?php
session_start();
require 'connection.php'; // Include the database connection file

// Initialize the Database class and establish a connection
$database = new Database();
$pdo = $database->getConnection();

// Check if the reset token is present in the URL
if (isset($_GET['token'])) {
    $resetToken = $_GET['token'];

    // Validate the reset token
    $stmt = $pdo->prepare("SELECT * FROM Customer WHERE ResetToken = :token AND ResetTokenExpiry > NOW()");
    $stmt->execute(['token' => $resetToken]);
    $user = $stmt->fetch();

    if ($user) {
        // If token is valid, proceed with password reset
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            // Check if passwords match
            if ($newPassword === $confirmPassword) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT); // Hash the new password

                // Update the password in the database
                $stmt = $pdo->prepare("UPDATE Customer SET Password = :password, ResetToken = NULL, ResetTokenExpiry = NULL WHERE CustomerID = :id");
                $stmt->execute([
                    'password' => $hashedPassword,
                    'id' => $user['CustomerID']
                ]);

                // Redirect to a success page or login page
                header('Location: login.html');
                exit();
            } else {
                $errorMessage = "Passwords do not match.";
            }
        }
    } else {
        echo "Invalid or expired reset token.";
        exit();
    }
} else {
    echo "No reset token provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | TimBuys</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 100px;
        }
        .card {
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #daa520;
            color: white;
            text-align: center;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #daa520;
            border: none;
        }
        .btn-primary:hover {
            background-color: #b59416; /* Darker shade on hover */
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Reset Your Password</div>
                <div class="card-body">
                    <?php if (isset($errorMessage)): ?>
                        <div class="alert alert-danger"><?= $errorMessage ?></div>
                    <?php endif; ?>
                    <form method="POST" onsubmit="return validatePasswords();">
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Reset Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function validatePasswords() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;

        if (password !== confirmPassword) {
            alert("Passwords do not match.");
            return false; // Prevent form submission
        }
        return true; // Allow form submission
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
