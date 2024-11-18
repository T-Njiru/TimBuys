<?php
// Include the Database connection class and PHPMailer
require_once 'connection.php';
require 'PHPMailer/src/Exception.php';  
require 'PHPMailer/src/PHPMailer.php';  
require 'PHPMailer/src/SMTP.php';  

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
        // Prepare and execute the SQL query to insert into pendingvendors
        $stmt = $pdo->prepare("INSERT INTO pendingvendors (Name, Address, Email, Password, Contact) VALUES (?, ?, ?, ?, ?)");
        
        // Check for successful execution
        if ($stmt->execute([$name, $address, $email, $password, $contact])) {
            // Send notification to the admin
            $adminEmail = 'ted.njiru@strathmore.edu';

            // Create a new PHPMailer instance
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->isSMTP();                                            // Set mailer to use SMTP
                $mail->Host       = 'smtp.gmail.com';                     // Specify your SMTP server
                $mail->SMTPAuth   = true;                                 // Enable SMTP authentication
                $mail->Username   = 'fatumamm99@gmail.com';               // Your SMTP username
                $mail->Password   = 'mjdn nvnf qkcq iiyi';                // Your SMTP password
                $mail->SMTPSecure = 'tls';                                // Enable TLS encryption
                $mail->Port       = 587;                                  // TCP port for TLS

                // Recipients
                $mail->setFrom('fatumamm99@gmail.com', 'Admin');
                $mail->addAddress($adminEmail);                           // Add the admin's email as a recipient

                // Content
                $mail->isHTML(true);                                      // Set email format to HTML
                $mail->Subject = 'New Vendor Registration';
                $mail->Body    = "A new vendor has registered:<br>
                                  <strong>Name:</strong> $name<br>
                                  <strong>Address:</strong> $address<br>
                                  <strong>Email:</strong> $email<br>
                                  <strong>Contact:</strong> $contact<br>
                                  Please review and approve this vendor.";

                $mail->send();
                
                // Store vendor email in session to check later
                session_start();
                $_SESSION['vendor_email'] = $email;

                // Redirect to loading page
                header("Location: loading.php");
                exit();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "<p class='error' style='color: red;'>Error: Unable to register vendor. Please try again later.</p>";
        }
    } else {
        echo "<p class='error' style='color: red;'>Database connection error. Please try again later.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Vendor Registration | TimBuys</title>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
    />
    <style>
        body {
            background: linear-gradient(135deg, #fceabb 0%, #f8b500 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            padding-top: 20px; /* Added padding to prevent content from being hidden */
        }

        .signup-wrapper {
            max-width: 500px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
        }

        .form-title {
            margin-bottom: 20px;
            font-size: 24px;
            color: #f39c12;
        }

        .form-control {
            font-size: 16px;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .password-strength {
            font-size: 14px;
            color: #f39c12;
            margin-top: 5px;
        }

        .btn-primary {
            background-color: #f39c12;
            border: none;
            font-size: 16px;
            padding: 12px;
            border-radius: 8px;
        }

        .btn-primary:hover {
            background-color: #d87d02;
        }
    </style>
    <script>
        // Password strength validation function
        function checkPasswordStrength() {
            const password = document.querySelector('input[name="password"]').value;
            const strengthMessage = document.querySelector('.password-strength');

            const regexWeak = /^(?=.*[a-z]).{6,}$/;
            const regexMedium = /^(?=.*[a-z])(?=.*[A-Z]).{6,}$/;
            const regexStrong = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()]).{8,}$/;

            if (regexStrong.test(password)) {
                strengthMessage.textContent = 'Password Strength: Strong';
                strengthMessage.style.color = 'green';
            } else if (regexMedium.test(password)) {
                strengthMessage.textContent = 'Password Strength: Medium';
                strengthMessage.style.color = 'orange';
            } else if (regexWeak.test(password)) {
                strengthMessage.textContent = 'Password Strength: Weak';
                strengthMessage.style.color = 'red';
            } else {
                strengthMessage.textContent = 'Password Strength: Very Weak';
                strengthMessage.style.color = 'red';
            }
        }

        // Password matching validation
        function validatePasswords() {
            const password = document.querySelector('input[name="password"]').value;
            const confirmPassword = document.querySelector('input[name="confirm_password"]').value;
            if (password !== confirmPassword) {
                alert("Passwords do not match!");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="signup-wrapper">
        <form action="vendor.php" method="post" onsubmit="return validatePasswords()">
            <h2 class="form-title">Vendor Registration</h2>
        
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" class="form-control" required />
            </div>
            
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" name="address" class="form-control" required />
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required />
            </div>
            
            <div class="mb-3">
                <label for="contact" class="form-label">Contact</label>
                <input type="text" name="contact" class="form-control" required />
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" oninput="checkPasswordStrength()" required />
                <div class="password-strength">Password Strength: </div>
            </div>
            
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required />
            </div>
            
            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
