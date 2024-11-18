<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <link rel="stylesheet" href="Track.css">
</body>
</html>

<?php 
require "C:/xampp/htdocs/TimBuys/tryingstuffglobal.php";
class Orders{
public function viewOrders(){
    $connection= new mysqli("localhost","root","","timbuys");
    $sql= "SELECT o.OrderID, p.ProductName, vp.Price, op.Quantity, o.OrderDate, o.Address
    FROM orders o
    JOIN orderedproduct op ON o.OrderID=op.OrderID
    JOIN vendorproduct vp ON op.VendorProductID=vp.VendorProductID
    JOIN product p ON vp.ProductID= p.ProductID";
    $result=$connection->query($sql);

    echo '<div class="table-container">';
    echo '<table class="table">';
    echo '<thead class="table-dark">';
    echo '<tr>';
    echo '<th>OrderID</th>';
    echo '<th>ProductName</th>';
    echo '<th>Price (KSh)</th>';
    echo '<th>Quantity</th>';
    echo '<th>OrderDate</th>';
    echo '<th>Delivery Address</th>';
    echo '<th>Actions</th>'; 
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

        while($list=$result->fetch_assoc()){
            echo '<tr>';
            echo '<td>' . htmlspecialchars($list['OrderID']) . '</td>';
            echo '<td>' . htmlspecialchars($list['ProductName']) . '</td>';
            echo '<td>' . htmlspecialchars($list['Quantity']) . '</td>';
            echo '<td>' . htmlspecialchars($list['OrderDate']) . '</td>';
            echo '<td>' . htmlspecialchars($list['Address']) . '</td>';
            echo '<td><a href="tracking_page.php?OrderID=' . htmlspecialchars($list['OrderID']) . '" class="btn btn-primary">Track</a></td>'; // Updated Track button
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
        echo '</div>';
} 
}
$Orders=new Orders();
$Orders->viewOrders();
?>
