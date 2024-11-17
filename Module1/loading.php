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

// Function to check if vendor is still pending approval
function isVendorPending($pdo, $email) {
    $stmt = $pdo->prepare("SELECT * FROM pendingvendors WHERE Email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Check approval status
$vendorApproved = isVendorApproved($pdo, $vendorEmail);
$vendorPending = isVendorPending($pdo, $vendorEmail);

// Redirect if approved
if ($vendorApproved) {
    header("Location: vendorlogin.php");
    exit();
}

// If not approved and no longer pending, show rejection message
if (!$vendorApproved && !$vendorPending) {
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8' />
        <meta name='viewport' content='width=device-width, initial-scale=1.0' />
        <title>Registration Rejected</title>
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css' />
    </head>
    <body>
        <div class='container text-center mt-5'>
            <h2>Registration Rejected</h2>
            <p>We regret to inform you that your vendor registration has been rejected. If you have any questions, please <a href='contact.php'>contact us</a>.</p>
            <a href='home.html' class='btn btn-primary mt-3'>Go Back to Home</a>
        </div>
    </body>
    </html>";
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
        // Check for approval or rejection every few seconds
        setInterval(() => {
            window.location.reload();
        }, 5000); // Check every 5 seconds
    </script>
</body>
</html>
