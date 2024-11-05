<?php
include 'connection.php'; // Include your database connection file

// Create an instance of the Database class
$database = new Database();
$conn = $database->getConnection(); // Get the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the email from the POST request
    $email = $_POST['email'];

    // Prepare the SQL statement to insert the email into the newsletter_subscribers table
    $stmt = $conn->prepare("INSERT INTO newsletter_subscribers (email) VALUES (:email)");
    $stmt->bindParam(':email', $email);

    try {
        // Execute the statement
        $stmt->execute();
        echo "<script>alert('Thank you for subscribing to TimBuys!'); window.location.href='home.html';</script>";
    } catch (PDOException $e) {
        // Handle duplicate entry error
        if ($e->getCode() == 23000) { // Duplicate entry error code
            echo "<script>alert('This email is already subscribed.'); window.location.href='home.html';</script>";
        } else {
            // Handle other errors
            echo "<script>alert('Error: " . addslashes($e->getMessage()) . "'); window.location.href='homepage.html';</script>";
        }
    }
}
?>
