<?php
session_start();
require 'connection.php';  // Database connection

// Create a database connection
$database = new Database();
$pdo = $database->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure both email and password fields are submitted
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = htmlspecialchars(trim($_POST['email']));
        $password = htmlspecialchars(trim($_POST['password']));

        // Retrieve the user record from the database by email
        $stmt = $pdo->prepare("SELECT * FROM Customer WHERE Email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Use password_verify to check the submitted password against the stored hashed password
            if (password_verify($password, $user['Password'])) {
                // If the password is correct, log in the user and start a session
                $_SESSION['user_fname'] = $user['FirstName'];  // Store user's first name in the session
                $_SESSION['customer_id'] = $user['CustomerID'];  // Optionally store user ID for later use
                $_SESSION['Role_As'] = $user['role_as'];  // Store the role in the session

                // Redirect based on role
                if ($user['role_as'] == 1) {
                    // Redirect to admin dashboard if user is an admin
                    header("Location: admin/index.php");
                } else {
                    // Redirect to homepage if user is a regular customer
                    header("Location: home.php");
                }
                exit();
            } else {
                // Password mismatch
                echo "Invalid password. Please try again.";
            }
        } else {
            // No user found with the provided email
            echo "No account found with that email.";
        }
    } else {
        // If email or password is not provided
        echo "Both email and password are required.";
    }
}
?>
