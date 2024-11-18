<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Us | TimBuys</title>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
    />
    <style>
      /* Background and Body */
      body {
        background: linear-gradient(135deg, #fceabb 0%, #f8b500 100%);
        background-attachment: fixed;
        color: #333;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        display: flex;
        justify-content: center;
        padding: 20px 0;
      }

      /* Form Wrapper */
      .form-wrapper {
        max-width: 400px;
        width: 100%;
        background-color: white;
        padding: 30px;
        border-radius: 15px;
        margin-top: 30px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
      }

      /* Title and Button Styling */
      .form-title {
        margin-bottom: 20px;
        font-size: 24px;
        font-weight: bold;
        color: #f39c12;
        text-align: center;
      }
      .btn-primary {
        background-color: #f39c12;
        border-color: #f39c12;
      }
      .btn-primary:hover {
        background-color: #d87d02;
        border-color: #d87d02;
      }

      /* Input Field Styling */
      .form-label {
        color: #555555;
        font-weight: 500;
      }
      .form-control {
        border: 2px solid #e8e8e8;
        border-radius: 8px;
      }
      .form-control:focus {
        border-color: #f39c12;
        box-shadow: 0 0 0 0.2rem rgba(243, 156, 18, 0.25);
      }
    </style>
  </head>
  <body>
    <div class="form-wrapper">
      <h2 class="form-title">Contact Us</h2>
      <form action="submit_contact.php" method="POST">
        <div class="mb-3">
          <label for="email" class="form-label">Your Email</label>
          <input
            type="email"
            id="email"
            name="email"
            class="form-control"
            placeholder="Enter your email"
            required
          />
        </div>
        <div class="mb-3">
          <label for="subject" class="form-label">Subject</label>
          <input
            type="text"
            id="subject"
            name="subject"
            class="form-control"
            placeholder="Enter the subject"
            required
          />
        </div>
        <div class="mb-3">
          <label for="message" class="form-label">Message</label>
          <textarea
            id="message"
            name="message"
            class="form-control"
            rows="5"
            placeholder="Write your message here"
            required
          ></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100">Send Message</button>
      </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
