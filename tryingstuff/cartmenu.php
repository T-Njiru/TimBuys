<?php 


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-link:hover{background-color:gray;
        color:whitesmoke;}
    </style>
    <title>Document</title>
</head>
<header>
  <nav class="navbar navbar-expand-lg bg-body-tertiary " data-bs-theme="dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Navbar</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Features</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Pricing</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Dropdown link
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Action</a></li>
              <li><a class="dropdown-item" href="#">Another action</a></li>
              <li><a class="dropdown-item" href="#">Something else here</a></li>
            </ul>
        </li>

        </ul>
        <button class="btn btn-light ms-auto" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
          </svg></button>
       
      </div>
    </div>
  </nav>
</header>
<div class="offcanvas offcanvas-end " data-bs-backdrop="static" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel" style="width:50%">
  <div class="offcanvas-header ">
    <h5 class="offcanvas-title mx-auto" id="offcanvasWithBothOptionsLabel">Cart</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class=" offcanvas-body">
    <div class=" mb-2 pb-2 border-bottom">
        <?php ?>
        <div>
            <div class="mx-auto pb-1 mb-1 bottom-border"><h3>Product Sellers Name<h3></div>
            <div class="row">
                <div class="col-md-3 my-auto"><img src="C:\Users\jsnki\OneDrive\Pictures\Screenshots\food.jpg" class="img-thumbnail" alt="Prodcut" style="max-width: 150px; max-height: 150px;"> </div>
                <div class="d-flex flex-column col-md-9">
                    <div class="mx-auto p-0 m-0"><h3>Product Name<h3></div>
                    <div class="p-0 m-0"><p>In Stock</p></div> 
                    <div class="p-0 m-0 row">
                        <div class="p-0 m-0 col-md-6"><h4>$Price</h4></div> 
                            <div class="col-md-6 d-flex flex-column">
                                <form class="p-0 m-0">
                                    <input type="number" value="1">
                                </form>
                                <button class="btn btn-link text-dark p-0" style="text-decoration:none;" type="button">remove</button>
                            </div>
                        </div>
                            
                    </div>
                </div>
            </div>
        </div>
        
    
    </div>

    
  </div>
</div>
<body>
    
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</html>
