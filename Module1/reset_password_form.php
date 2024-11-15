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
        /* Background and Body */
        body {
            background: linear-gradient(135deg, #fceabb 0%, #f8b500 100%);
            background-attachment: fixed;
            color: #333;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            padding: 20px 0;
            margin: 0;
        }

        /* Reset Password Wrapper */
        .reset-password-wrapper {
            max-width: 400px;
            width: 100%;
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            margin-top: 5%;
        }

        /* Title and Button Styling */
        .form-title {
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
            color: #f39c12;
            text-align: center;
        }

        .btn-primary {
            background-color: #f39c12;
            border-color: #f39c12;
        }

        .btn-primary:hover {
            background-color: #d87d02;
            border-color: #d87d02;
        }

        /* Input Field Styling */
        .form-label {
            color: #555555;
            font-weight: 500;
        }

        .form-control {
            border: 2px solid #e8e8e8;
            border-radius: 8px;
        }

        .form-control:focus {
            border-color: #f39c12;
            box-shadow: 0 0 0 0.2rem rgba(243, 156, 18, 0.25);
        }

        /* Password Strength Indicator */
        .password-strength {
            font-size: 0.9rem;
            margin-top: 5px;
        }

        .strength-weak {
            color: #e74c3c;
        }

        .strength-medium {
            color: #f39c12;
        }

        .strength-strong {
            color: #27ae60;
        }

        /* Error Message Styling */
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
            text-align: center;
        }

        /* Responsive Styling */
        @media (max-width: 576px) {
            .form-title {
                font-size: 20px;
            }
            .reset-password-wrapper {
                width: 90%;
            }
        }
    </style>
</head>
<body>
<div class="reset-password-wrapper">
    <h2 class="form-title">Reset Password</h2>
    <form method="POST" onsubmit="return validatePasswords();">
        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input type="password" class="form-control" id="password" name="password" oninput="checkPasswordStrength()" required>
            <div id="strength-indicator" class="password-strength">Password strength</div>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Reset Password</button>

        <?php if (isset($errorMessage)): ?>
            <div class="alert alert-danger mt-3"><?= $errorMessage ?></div>
        <?php endif; ?>
    </form>
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

    function checkPasswordStrength() {
        const password = document.getElementById('password').value;
        const strengthIndicator = document.getElementById("strength-indicator");
        let strength = 0;

        // Password strength criteria
        if (password.length >= 8) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[\W_]/.test(password)) strength++;

        // Update the strength indicator
        if (strength <= 1) {
            strengthIndicator.textContent = "Weak";
            strengthIndicator.className = "password-strength strength-weak";
        } else if (strength === 2) {
            strengthIndicator.textContent = "Medium";
            strengthIndicator.className = "password-strength strength-medium";
        } else if (strength >= 3) {
            strengthIndicator.textContent = "Strong";
            strengthIndicator.className = "password-strength strength-strong";
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
