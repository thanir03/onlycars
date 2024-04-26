<?php 

  include "../db.php";
  $conn = create_connection();
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
  if($authDetails["isLoggedIn"] == false){
    header("Location: http://localhost/onlycars/buyer/auth.php");
    die();
  }
  $car_list = getAllCarsInCart($conn, $authDetails["email"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Cart</title>

  <script src="/onlycars/script/buyer/cart.js" defer type="module"></script>
  <link rel="stylesheet" href="/onlycars/styles/index.css">
  <link rel="stylesheet" href="/onlycars/styles/buyer/cart.css">
</head>
<body>

<?php  include '../components/buyer/navbar.php';?>
  <h2 class="cart-title">My Cart</h2>  
  <main class="cart-container">
    <?php
      if(count($car_list) == 0){
        echo "<div style='display:flex' class='no-cart-container'> 
        <p>No items in the cart</p> 
        </div>";
      }else {
        echo "<div style='display:none' class='no-cart-container'> 
        <p>No items in the cart</p> 
        </div>";
      }
    ?>
    <?php
      for( $i = 0; $i < count($car_list); $i++ ) {
        $car =  $car_list[$i];
        echo sprintf("<div class='cart-item-container' data-cart-id=%s>",$car["cart_id"]);
        echo sprintf("<img width='300px' src=%s alt=%s />", $car["image_url"], $car["model_name"]);
        echo "<div class='car-details-container'>";
        if($car["is_sold"] == 1){
          echo sprintf("<p>Car has been sold</p>");
        }
        echo sprintf("<div class='car-name'><p>%s</p> <p>%s</p></div>", ucwords($car["name"]) , $car["model_name"]);
        echo "<div class='car-minor-details'>";
        echo sprintf("<p>Year : %s </p>",$car["year"]  );
        echo sprintf("<p>Mileage : %s km </p>",$car["mileage"]  );
        echo sprintf("<p>Color : %s </p>",$car["color"]  );
        echo "</div>";
        echo sprintf("<p class='price'>RM %s</p>",$car["price"]  );
        echo "<div class='btn-container'>";
        if($car["is_sold"] !== 1){
          echo sprintf("<button data-car-id=%s data-cart-id=%s class='checkout-btn'>Checkout</button>",$car["car_id"], $car["cart_id"] );
        }
        echo sprintf('<div data-cart-id=%s class="delete-container">
            <svg class="delete-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" id="delete"><path fill="#000" d="M15 3a1 1 0 0 1 1 1h2a1 1 0 1 1 0 2H6a1 1 0 0 1 0-2h2a1 1 0 0 1 1-1h6Z"></path><path fill="#000" fill-rule="evenodd" d="M6 7h12v12a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V7Zm3.5 2a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 1 0v-9a.5.5 0 0 0-.5-.5Zm5 0a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 1 0v-9a.5.5 0 0 0-.5-.5Z" clip-rule="evenodd"></path></svg> 
            <p>Delete</p> 
        </div>', $car["cart_id"]);
        echo "</div>";
        echo "</div>";
        echo "</div>";
      }
    ?>
  </main>

  <div class="modal">
        <p>Sucessful booking</p>
      </div>
</body>
</html>



<?php
  close_connection($conn);
?>