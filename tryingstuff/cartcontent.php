<?php 
class cart{
    function load(){
        include_once('../Module3/cart_functions.php'); 
        $conn = new mysqli('localhost', 'root', '', 'timbuys');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "SELECT p.ProductID, p.ProductName, vp.Price, vp.Quantity, vp.Description, v.Name AS VendorName, c.CategoryName
        FROM Product p
        JOIN VendorProduct vp ON p.ProductID = vp.ProductID
        JOIN Vendor v ON vp.VendorID = v.VendorID
        JOIN Category c ON p.CategoryID = c.CategoryID";

        $result = $conn->query($sql);
        $Specific=array();
        while($List=$result->fetch_assoc()){
        $Specific[$List['ProductID']]=$List;
        }
        return $Specific;
    }
    function cart($Specific){ ?>
        <div class="offcanvas offcanvas-end " data-bs-backdrop="static" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel" style="width:50%">
        <div class="offcanvas-header ">
          <h5 class="offcanvas-title mx-auto" id="offcanvasWithBothOptionsLabel">Cart</h5>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class=" offcanvas-body">
        <?php if (isset($_SESSION['cart']) && $_SESSION['cart']!=null):
                    foreach($_SESSION['cart'] as $product){ ?>
          <div class=" mb-2 pb-2 border-bottom">
              <div>
                  <div class="ms-auto pb-1 mb-1"><h4 class="ms-auto text-dark"><?php echo $product['VendorName']; ?></h4></div>
                  
                  <div class="row">
                  <div class="col-md-3 my-auto"><img src="food.jpg" class="img-thumbnail show" alt="Product" style="max-width: 150px; max-height: 150px; color: black;"> </div>
                      <div class=" ms-5 d-flex flex-column col-md-8">
                          <div class="mx-auto p-0 m-0 text-dark"><h3><?php echo $product['ProductName'];?></h3></div>
                          <div class="p-0 m-0 "><?php print ($Specific[$product['ProductID']]['Quantity']>0)? "<p class='text-success'>In Stock</p>":"<p class='text-danger'>Out of Stock</p>"; ?></div> 
                          <div class="p-0 m-0 row">
                              <div class="p-0 m-0 col-md-6"><h4 class="text-dark"><?php echo $product['Price'];?></h4></div> 
                                  <div class="col-md-6 d-flex flex-column m-0 p-0 " >
                                      <form class="p-0 m-0 mx-auto " style="width:20%;">
                                          <input type="number" style="width: 100%; "  value="<?php echo $product['Quantity']; ?>">
                                      </form>
                                      <form method="POST" action="../Module3/remove_from_cart.php" class="p-0 m-0 mx-auto ">
                                      <input type="hidden" name="product_id" value="<?php echo $product['ProductID'];?>">
                                      <button class="btn"  type="submit">remove</button>
                                      </form>
                                  </div>
                              </div>
                                  
                          </div>
                      </div>
                  </div>
          </div>
                  
                <?php }?>
             
              <div class="offcanvas-footer">
                <a class="btn btn-dark"  type="button" href="http://localhost/TimBuys/tryingstuff/checkout.php#">Go to Checkout</a>
              </div>
              <?php 
              
            elseif($_SESSION['cart']==null):
                  echo "<p class='text-dark'>Your Cart is empty! </p>";
                  echo '<a href="../Module2/listing/product_listing.php" class="btn btn-secondary mb-3">Browse the Store</a>';
              endif;
                 
                ?>
          </div>
    </div>
    </div><?php
    }
}?>