<?php
session_start();
require 'connection.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch county and city data for dropdown options
$database = new Database();
$pdo = $database->getConnection();

$counties = $pdo->query("SELECT * FROM County")->fetchAll(PDO::FETCH_ASSOC);
$cities = $pdo->query("SELECT * FROM City")->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_SESSION['customer_id'];
    $house_no = $_POST['house_no'];
    $street = $_POST['street'];
    $area = $_POST['area'];
    $county_id = $_POST['county_id'];
    $city_id = $_POST['city_id'];

    // Insert the address into the database
    $stmt = $pdo->prepare("
        INSERT INTO Address (HouseNo, Street, Area, CountyID, CityID, CustomerID)
        VALUES (:house_no, :street, :area, :county_id, :city_id, :customer_id)
    ");
    $stmt->execute([
        ':house_no' => $house_no,
        ':street' => $street,
        ':area' => $area,
        ':county_id' => $county_id,
        ':city_id' => $city_id,
        ':customer_id' => $customer_id,
    ]);

    // Redirect to profile page after adding the address
    header('Location: profile.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Address</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .form-container {
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-container label {
            display: block;
            margin-bottom: 5px;
        }
        .form-container input, .form-container select, .form-container button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-container button {
            background-color: #daa520;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add Address</h2>
        <form method="POST">
            <label for="house_no">House Number:</label>
            <input type="text" id="house_no" name="house_no" required>

            <label for="street">Street:</label>
            <input type="text" id="street" name="street" required>

            <label for="area">Area:</label>
            <input type="text" id="area" name="area" required>

            <label for="county_id">County:</label>
            <select id="county_id" name="county_id" required>
                <option value="">Select a County</option>
                <?php foreach ($counties as $county): ?>
                    <option value="<?= $county['CountyID']; ?>"><?= htmlspecialchars($county['CountyName']); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="city_id">City:</label>
            <select id="city_id" name="city_id" required>
                <option value="">Select a City</option>
                <?php foreach ($cities as $city): ?>
                    <option value="<?= $city['CityID']; ?>"><?= htmlspecialchars($city['CityName']); ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Add Address</button>
        </form>
    </div>
</body>
</html>
