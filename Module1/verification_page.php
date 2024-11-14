<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Code | TimBuys</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        /* Background and Body */
        body {
            background: linear-gradient(135deg, #fceabb 0%, #f8b500 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Verification Wrapper Styling */
        .verification-wrapper {
            max-width: 400px;
            width: 100%;
            background-color: #fff;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* Title and Subtitle Styling */
        .form-title {
            font-size: 22px;
            font-weight: bold;
            color: #f39c12;
            margin-bottom: 10px;
        }
        .form-subtitle {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }

        /* Code Input Styling */
        .code-input input {
            width: 60px;
            height: 60px;
            font-size: 24px;
            text-align: center;
            margin: 0 5px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
        }
        .code-input input:focus {
            border-color: #f39c12;
            box-shadow: 0 0 0 0.2rem rgba(243, 156, 18, 0.25);
        }

        /* Button Styling */
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

        /* Resend Link and Timer Styling */
        .resend-link {
            font-size: 14px;
            color: #007bff;
            text-decoration: none;
            display: block;
            margin-top: 20px;
        }
        .resend-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }
        .resend-timer {
            font-size: 13px;
            color: #666;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="verification-wrapper">
        <h2 class="form-title">Enter Verification Code</h2>
        <p class="form-subtitle">We've sent a 4-digit code to your email. Please enter it below.</p>

        <form action="code.php" method="POST">
            <div class="code-input">
                <input type="text" maxlength="1" name="digit1" required>
                <input type="text" maxlength="1" name="digit2" required>
                <input type="text" maxlength="1" name="digit3" required>
                <input type="text" maxlength="1" name="digit4" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-4">Verify</button>
        </form>

        <a href="resend_code.php" class="resend-link" id="resendCodeLink">Resend Code</a>
        <div class="resend-timer" id="timer">Resend available in <span id="countdown">30</span> seconds</div>
    </div>

    <script>
        // Code Input Navigation
        const inputs = document.querySelectorAll('.code-input input');
        
        inputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                if (input.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });
            input.addEventListener('keydown', (e) => {
                if (e.key === "Backspace" && index > 0 && input.value === "") {
                    inputs[index - 1].focus();
                }
            });
        });

        // Countdown Timer for Resend Link
        let countdownElement = document.getElementById("countdown");
        let resendLink = document.getElementById("resendCodeLink");
        let timer = 30;

        const countdownInterval = setInterval(() => {
            timer--;
            countdownElement.textContent = timer;
            if (timer <= 0) {
                clearInterval(countdownInterval);
                resendLink.style.pointerEvents = 'auto';
                resendLink.style.color = '#007bff';
                countdownElement.style.display = 'none';
            }
        }, 1000);

        resendLink.style.pointerEvents = 'none';
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
