<?php
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the digits from the form
    $input_code = $_POST['digit1'] . $_POST['digit2'] . $_POST['digit3'] . $_POST['digit4'];

    // Check if the session contains a verification code and expiry time
    if (isset($_SESSION['verification_code'], $_SESSION['code_expiry'])) {
        $stored_code = $_SESSION['verification_code'];
        $expiry_time = $_SESSION['code_expiry'];

        // Verify the code and check if it's expired
        if (time() <= $expiry_time) {
            if ($input_code == $stored_code) {
                echo "Code verified successfully!";
                // Clear session and proceed with the next steps (e.g., account creation or login)
                session_unset();
                session_destroy();
                header("Location: http://localhost/TimBuys/Module1/vendor.php"); // Redirect to the signup page
                exit();
            } else {
                echo "Incorrect verification code.";
            }
        } else {
            echo "The code has expired. Please request a new one.";
        }
    } else {
        echo "No verification code found. Please try again.";
    }
}
?>
