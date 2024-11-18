<?php
session_start();
require 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
    header('Location: login.php'); // Redirect to login page
    exit();
}

// Get user details
$customer_id = $_SESSION['customer_id'];
$database = new Database();
$pdo = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_details'])) {
        // Update user details
        $first_name = htmlspecialchars($_POST['first_name']);
        $last_name = htmlspecialchars($_POST['last_name']);
        $contact = htmlspecialchars($_POST['contact']);
        $gender = htmlspecialchars($_POST['gender']);

        $stmt = $pdo->prepare("UPDATE Customer SET FirstName = :first_name, LastName = :last_name, Contact = :contact, Gender = :gender WHERE CustomerID = :customer_id");
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':contact', $contact);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':customer_id', $customer_id);

        if ($stmt->execute()) {
            $message = "Details updated successfully!";
        } else {
            $message = "Failed to update details. Please try again.";
        }
    }

    if (isset($_POST['change_password'])) {
        // Change password
        $current_password = $_POST['current_password'];
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

        // Verify current password
        $stmt = $pdo->prepare("SELECT Password FROM Customer WHERE CustomerID = :customer_id");
        $stmt->bindParam(':customer_id', $customer_id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (password_verify($current_password, $user['Password'])) {
            // Update to new password
            $stmt = $pdo->prepare("UPDATE Customer SET Password = :new_password WHERE CustomerID = :customer_id");
            $stmt->bindParam(':new_password', $new_password);
            $stmt->bindParam(':customer_id', $customer_id);

            if ($stmt->execute()) {
                $message = "Password changed successfully!";
            } else {
                $message = "Failed to change password.";
            }
        } else {
            $message = "Current password is incorrect.";
        }
    }
}

// Fetch user details for the form
$stmt = $pdo->prepare("SELECT * FROM Customer WHERE CustomerID = :customer_id");
$stmt->bindParam(':customer_id', $customer_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Account Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
        }
        .container h2 {
            text-align: center;
            color: #daa520;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            font-weight: bold;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-group button {
            padding: 10px 15px;
            background-color: #daa520;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #ff9900;
        }
        .message {
            color: green;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Account Management</h2>
        <?php if (isset($message)) echo "<p class='message'>$message</p>"; ?>
        
        <!-- Update Details Form -->
        <form method="post">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['FirstName']); ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['LastName']); ?>" required>
            </div>
            <div class="form-group">
                <label for="contact">Contact</label>
                <input type="text" id="contact" name="contact" value="<?php echo htmlspecialchars($user['Contact']); ?>" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="Male" <?php if ($user['Gender'] === 'Male') echo 'selected'; ?>>Male</option>
                    <option value="Female" <?php if ($user['Gender'] === 'Female') echo 'selected'; ?>>Female</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" name="update_details">Update Details</button>
            </div>
        </form>

        <!-- Change Password Form -->
        <form method="post">
            <h3>Change Password</h3>
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <button type="submit" name="change_password">Change Password</button>
            </div>
        </form>
    </div>
    <div class="back-button">
    <a href="profile.php" class="btn-back">Back to Profile</a>
</div>

<style>
    .back-button {
        text-align: center;
        margin-top: 20px;
    }
    .btn-back {
        display: inline-block;
        padding: 10px 15px;
        font-size: 16px;
        color: #fff;
        background-color: #007bff;
        text-decoration: none;
        border-radius: 5px;
    }
    .btn-back:hover {
        background-color: #0056b3;
    }
</style>

</body>
</html>
