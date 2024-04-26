<?php
  require '../db.php';
  $conn = create_connection();
  $brand = array();
  $transmission = "";
  $colors = array();
  $fuel = "";
  $order = "DESC";
  $pageNumber = 0;
  $car_list = findCars($conn, $brand , $transmission , $colors , $fuel , $order , $pageNumber);
  $brand_list = findBrands($conn);
  $colors = findColors($conn); 

  $authDetails = array("isLoggedIn" => false, "email" => "");
  if(array_key_exists("userToken", $_COOKIE)){
    $email = isUserAuthenticated($conn, $userToken = $_COOKIE["userToken"] );
    if($email != null){
      $authDetails["email"] = $email;
      $authDetails["isLoggedIn"] = true;
    }else {
      setcookie('userToken', '', time() - 3600, '/');
    } 
  }
  ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OnlyCars Second Hand Vehicle</title>
  <link rel="stylesheet" href="/onlycars/styles/buyer/cars.css">
  <link rel="stylesheet" href="/onlycars/styles/index.css">
  <script src="/onlycars/script/buyer/cars.js" defer type="module"></script>
</head>
<body> 

<?php  include '../components/buyer/navbar.php';?>

<main>
    <div class='header-container'>
      <h1 class='car-title'>All Cars</h1>
      <select name="price-range" id="price-select">
          <option value="htl" selected="selected">Price : High to Low</option>
          <option value="lth" >Price : Low to High</option>
        </select>
    </div>
    <div class='content-container'>
        <?php include "../components/buyer/filter_options.php"  ?>
        <section class="car-content-container">
          <div class="car-list">
            <?php include "../components/buyer/car_list.php"; ?>
          </div>
          <div class="bottom"></div>
      </section>
    </div>
  </main>
</body>
</html>
<?php 
  close_connection($conn);
?>

<!-- 
  TOMORROW TODO LIST
  1. Build buy-car page 
  2. Build individual car page
  3. Build buy-car with brand page
  4. Build sell car page
-->



