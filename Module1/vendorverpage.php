<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Code | TimBuys</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #fceabb 0%, #f8b500 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            padding-top: 30px;
        }

        .verification-wrapper {
            max-width: 400px;
            margin: 0 auto;
            background-color: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .form-title {
            margin-bottom: 20px;
            font-size: 24px;
            color: #f39c12;
        }

        .form-subtitle {
            margin-bottom: 30px;
            font-size: 16px;
            color: #555;
        }

        .code-input input {
            width: 60px;
            height: 60px;
            font-size: 24px;
            text-align: center;
            margin: 0 5px;
            border-radius: 8px;
            border: 1px solid #ddd;
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

        .resend-link {
            margin-top: 20px;
        }

        .resend-timer {
            margin-top: 5px;
            font-size: 14px;
            color: #f39c12;
        }

        .resend-link a {
            text-decoration: none;
            color: #007bff;
        }

        .resend-link a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="verification-wrapper">
        <h2 class="form-title">Enter Verification Code</h2>
        <p class="form-subtitle">We have sent a 4-digit code to your email. Please enter it below.</p>

        <form action="vendorcode.php" method="POST">
            <div class="code-input">
                <input type="text" maxlength="1" name="digit1" required>
                <input type="text" maxlength="1" name="digit2" required>
                <input type="text" maxlength="1" name="digit3" required>
                <input type="text" maxlength="1" name="digit4" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-4">Verify</button>
        </form>

        <div class="resend-link">
            <a href="resend_code.php" id="resendCodeLink">Resend Code</a>
        </div>
        <div class="resend-timer" id="timer">Resend available in <span id="countdown">30</span> seconds</div>
    </div>

    <script>
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
