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
            $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the new password

            // Update the password in the database
            $stmt = $pdo->prepare("UPDATE Customer SET Password = :password, ResetToken = NULL, ResetTokenExpiry = NULL WHERE CustomerID = :id");
            $stmt->execute([
                'password' => $newPassword,
                'id' => $user['CustomerID']
            ]);

            // Redirect to a success page or login page
            header('Location: login.html');
            exit();
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
    <title>Reset Password | My Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Reset Your Password</div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Reset Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
