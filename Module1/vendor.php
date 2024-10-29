<?php
// Include the Database connection class
require_once 'connection.php';

// Create a Database object and establish a connection
$database = new Database();
$pdo = $database->getConnection();

// Vendor registration logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $contact = $_POST['contact'];

    // Check if the connection is successful
    if ($pdo) {
        // Prepare and execute the SQL query
        $stmt = $pdo->prepare("INSERT INTO Vendor (Name, Address, Email, Password, Contact) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $address, $email, $password, $contact]);

        echo "<p class='success'>Vendor registered successfully!</p>";
    } else {
        echo "<p class='error'>Database connection error. Please try again later.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vendor Registration</title>
    <style>/* General Body Style */
body {
    font-family: Arial, sans-serif;
    background-color: #000; /* Black background */
    color: #fff; /* White text */
}

/* Heading Style */
h2 {
    color: #daa520; /* Gold color for heading */
    text-align: center;
    margin-top: 1em;
}

/* Form Container */
form {
    max-width: 400px;
    margin: auto;
    padding: 1.5em;
    background: #333; /* Dark gray for form background */
    border-radius: 8px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.5); /* Slight shadow for depth */
}

/* Label Styles */
label {
    margin-top: 1em;
    display: block;
    color: #daa520; /* Gold color for labels */
    font-weight: bold;
}

/* Input Fields */
input[type="text"],
input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    margin: 0.5em 0;
    box-sizing: border-box;
    border: 1px solid #daa520; /* Gold border */
    border-radius: 5px;
    background-color: #1c1c1c; /* Darker background for input fields */
    color: #fff; /* White text */
}

input[type="text"]:focus,
input[type="email"]:focus,
input[type="password"]:focus {
    outline: none;
    border-color: #fff; /* White border on focus */
}

/* Button Style */
button {
    padding: 12px;
    width: 100%;
    color: #000; /* Black text */
    background-color: #daa520; /* Gold background */
    border: none;
    border-radius: 5px;
    font-weight: bold;
    cursor: pointer;
    margin-top: 1em;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #ffd700; /* Lighter gold on hover */
    color: #000; /* Keep text black */
}

/* Error Message Styling */
.errors {
    color: #ff4d4d; /* Red for errors */
    list-style-type: none;
    padding: 0;
    margin-bottom: 1em;
}

.errors li {
    margin-bottom: 0.5em;
}

/* Success Message Styling */
.success {
    color: #4caf50; /* Green for success message */
    text-align: center;
    margin-bottom: 1em;
}

/* Responsive adjustments */
@media (max-width: 500px) {
    form {
        padding: 1em;
    }
}
</style>
</head>
<body>
    <h2>Vendor Registration</h2>

    <form action="vendor.php" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <label for="contact">Contact:</label>
        <input type="text" id="contact" name="contact" required><br>

        <button type="submit">Register</button>
    </form>
</body>
</html>
