<?php
session_start();
include('connection.php');

// Initialize Database and get connection
$database = new Database();
$pdo = $database->getConnection();

$vendorEmail = $_SESSION['vendor_email']; // Get vendor email from session

// Function to check vendor approval status
function isVendorApproved($pdo, $email) {
    $stmt = $pdo->prepare("SELECT * FROM vendor WHERE Email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Check approval status
$vendorApproved = isVendorApproved($pdo, $vendorEmail);

// Redirect if approved
if ($vendorApproved) {
    header("Location: vendorlogin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Loading</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" />
</head>
<body>
    <div class="container text-center mt-5">
        <h2>Waiting for Approval...</h2>
        <p>Your registration is currently pending approval. Please check back later.</p>
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <script>
        // Check for approval every few seconds
        setInterval(() => {
            window.location.reload();
        }, 5000); // Check every 5 seconds
    </script>
</body>
</html>
