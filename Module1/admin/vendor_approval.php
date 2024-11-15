<?php
include('../connection.php');

// Initialize Database and get connection
$database = new Database();
$pdo = $database->getConnection();

// Function to send approval email
function sendApprovalEmail($email, $vendorName) {
    $subject = "Vendor Registration Approved";
    $message = "Dear $vendorName,\n\nYour vendor registration has been approved. You can now log in to your account.\n\nBest regards,\nThe Team";
    mail($email, $subject, $message);
}

// Approve vendor if approved button is clicked
if (isset($_POST['approve'])) {
    $vendorId = $_POST['vendor_id'];

    // Fetch vendor details
    $stmt = $pdo->prepare("SELECT * FROM pendingvendors WHERE id = :vendorId");
    $stmt->bindParam(':vendorId', $vendorId);
    $stmt->execute();
    $vendor = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($vendor) {
        // Insert into vendors table
        $stmtInsert = $pdo->prepare("INSERT INTO vendor (VendorID, Name, Email, Password , Contact) VALUES (:vendorId, :vendorName, :email , :password , :contact)");
        $stmtInsert->bindParam(':vendorId', $vendor['VendorID']);
        $stmtInsert->bindParam(':vendorName', $vendor['Name']);
        $stmtInsert->bindParam(':email', $vendor['Email']);
        $stmtInsert->bindParam(':password', $vendor['Password']);
        $stmtInsert->bindParam(':contact', $vendor['Contact']);
        $stmtInsert->execute();

        // Send approval email
        sendApprovalEmail($vendor['Email'], $vendor['Name']);

        // Remove from pendingvendors table
        $stmtDelete = $pdo->prepare("DELETE FROM pendingvendors WHERE id = :vendorId");
        $stmtDelete->bindParam(':vendorId', $vendorId);
        $stmtDelete->execute();

        echo "<script>alert('Vendor approved and notification sent.'); window.location.href='vendor_approval.php';</script>";
    } else {
        echo "<script>alert('Vendor not found.'); window.location.href='vendor_approval.php';</script>";
    }
}
?>

<?php include('includes/header.php'); ?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Admin Dashboard</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="vendor_approval.php">Vendors</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="product_management.php">Products</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="order_management.php">Orders</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="customer_management.php">Customers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="financials.php">Financials</a>
            </li>
        </ul>
    </div>
</nav>
<div class="container mt-4">
    <h2>Pending Vendor Registrations</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Vendor ID</th>
                <th>Vendor Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $pdo->query("SELECT * FROM pendingvendors");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['Name']}</td>
                        <td>{$row['Email']}</td>
                        <td>
                            <form action='vendor_approval.php' method='post'>
                                <input type='hidden' name='vendor_id' value='{$row['id']}' />
                                <button type='submit' name='approve' class='btn btn-success'>Approve</button>
                            </form>
                        </td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<?php include('includes/footer.php'); ?>
