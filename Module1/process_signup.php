<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the session
session_start();

// Include the database connection file
require 'connection.php';

// Create a database connection
$database = new Database();
$pdo = $database->getConnection();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $fname = htmlspecialchars(trim($_POST['fname']));
    $lname = htmlspecialchars(trim($_POST['lname']));
    $gender = htmlspecialchars(trim($_POST['gender']));
    $dob = htmlspecialchars(trim($_POST['dob']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); // Sanitize the email input
    $contact = htmlspecialchars(trim($_POST['contact']));
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Hash the password

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit();
    }

    // Prepare SQL statement to insert user into the database
    try {
        $stmt = $pdo->prepare("INSERT INTO Customer (FirstName, LastName, Gender, DOB, Contact, Email, Password) VALUES (:fname, :lname, :gender, :dob, :contact, :email, :password)");
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':dob', $dob);
        $stmt->bindParam(':contact', $contact);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        // Store user's first name and email in session
        $_SESSION['user_fname'] = $fname;
        $_SESSION['email'] = $email;

        // Redirect to the login page after successful registration
        header("Location: login.html"); 
        exit();
    } catch (PDOException $e) {
        // Handle duplicate email or other database-related errors
        if ($e->getCode() == 23000) { // Integrity constraint violation (e.g., duplicate entry)
            echo "An account with this email already exists. Please use a different email.";
        } else {
            echo "Error: " . $e->getMessage();
        }
    }
}

// Close the connection
$pdo = null;
?>
