<?php
session_start();
require 'connection.php'; // Include your database connection file

// Create a database connection
$database = new Database();
$pdo = $database->getConnection();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    // Validate email
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Prepare SQL statement to fetch user data
        $stmt = $pdo->prepare("SELECT * FROM Customer WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Check if user exists
        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Store user data in session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_fname'] = $user['FirstName']; // Assuming you want to store first name

                // Redirect to the home page or user dashboard
                header("Location: home.php");
                exit();
            } else {
                echo "Invalid password. Please try again.";
            }
        } else {
            echo "No user found with that email address.";
        }
    } else {
        echo "Invalid email format.";
    }
}

// Close the connection
$pdo = null;
?>
