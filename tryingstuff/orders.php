<?php 
require "global.php";
class Orders{
public function viewOrders(){
    $connection= new mysqli("localhost","root","","timbuys");
    $sql= "SELECT o.OrderID,p.ProductName,vp.Price,op.Quantity,o.OrderDate,o.Address
    from orders o
    JOIN orderedproduct op ON o.OrderID=op.OrderID
    JOIN vendorproduct vp ON op.VendorProductID=vp.VendorProductID
    JOIN products p ON vp.ProductID= p.ProductID";
    $result=$connection->query($sql);

    echo '<table class="table table-bordered">';
        echo '<thead class="table-dark">';
        echo '<tr>';
        echo '<th>OrderID</th>';
        echo '<th>ProductName(KSh)</th>';
        echo '<th>Quantity(KSh)</th>';
        echo '<th>OrderDate</th>';
        echo '<th>Delivery Address</th>';
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
            echo '<td><a href="" class="btn">Track</a></td>';
            // echo '<td>';
            // echo '<form method="POST" action="remove_from_cart.php" onsubmit="return confirm(\'Are you sure you want to remove this item?\');">';
            // echo '<input type="hidden" name="product_id" value="' . htmlspecialchars($item['ProductID']) . '">';
            // echo '<button type="submit" class="btn btn-danger btn-sm">Remove</button>';
            // echo '</form>';
            // echo '</td>';
            echo '</tr>';
        
        }
  

      

        echo '</tbody>';
        echo '</table>';
  
} 
}