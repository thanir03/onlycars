<?php 
  include "../db.php";
  $conn = create_connection();

  $authDetails = array("isLoggedIn" => false, "email" => "");
  if(array_key_exists("sellerToken", $_COOKIE)){
    $email = isSellerAuthenticated($conn, $_COOKIE["sellerToken"] );
    if($email != null){
      $authDetails["email"] = $email;
      $authDetails["isLoggedIn"] = true;
    }else {
      setcookie('sellerToken', '', time() - 3600, '/');
    }
  }  
  $car_list = findSellersCar($conn,$authDetails["email"]);
  $brand_list = findBrands($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sellers cars</title>
  <link rel="stylesheet" href="/onlycars/styles/index.css">
  <link rel="stylesheet" href="/onlycars/styles/seller/cars.css">
  <script src="/onlycars/script/seller/cars.js" defer></script>
</head>
<body>
  <?php  include '../components/seller/navbar.php';?>
  <main>
    <div class="header-container">
      <h2>Seller's cars</h2>
    </div>
    <?php
    if($authDetails["isLoggedIn"] == false) {
      echo "<div class='not-auth-title'>You are not authenticated.</div>";
    }
    ?>
  
  <?php if(count($car_list) == 0 && $authDetails["isLoggedIn"] == true) echo "<p class='no-cars-title'>No cars to be sold. Add More</p>" ?>
  <?php
       if($authDetails["isLoggedIn"] && count($car_list) > 0) {
         echo "<div class='car-list'>";
          include "../components/seller/car_list.php"; 
          echo "</div>";
        }       
       ?>
  </main>
</body>
</html>