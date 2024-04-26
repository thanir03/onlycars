<?php
  include "../db.php";
  $conn = create_connection();
  $bookingId = (int) $_GET["id"];
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
  $bookingDetails = findBooking($conn , $bookingId, $authDetails["email"])[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Onlycars Checkout</title>
  <link rel="stylesheet" href="/onlycars/styles/index.css">
  <link rel="stylesheet" href="/onlycars/styles/buyer/checkout.css">
  <script src="/onlycars/script/buyer/checkout.js" defer></script>
</head>
<body>

<?php  include '../components/buyer/navbar.php';?>
  <main>
    <div>
      <h2 class="title">Checkout Details</h2>
    </div>
    <div class="booking-container">
      <div class="car-image-container">
        <?php
          echo sprintf("<img class='img' src=%s alt=%s />", $bookingDetails["image_url"], $bookingDetails["model_name"])
          ?>
      </div>
    <div class="car-container">
      <p class="car-name"><?php echo sprintf("%s %s %s", $bookingDetails['year'],ucwords($bookingDetails["name"]) , $bookingDetails["model_name"])  ?></p>
      <div class="major-details">

      <div class="car-details">
        <?php 
        echo '<p>Mileage: ' . $bookingDetails['mileage'] . ' km'. '</p>';
        echo '<p>Fuel Type: ' . $bookingDetails['fuel_type'] . '</p>';
        echo '<p>Transmission: ' . $bookingDetails['transmission'] . '</p>';
        echo '<p>Seat: ' . $bookingDetails['seat'] . '</p>';
        echo '<p>Doors: ' . $bookingDetails['doors'] . '</p>';
        echo '<p>Color: ' . $bookingDetails['color'] . '</p>';
        ?>
      </div>

      <div class="dealer-details">
        <p>Dealer Email : <?php echo $bookingDetails["user_email"] ?></p>
        <p>Dealer Contact Number : <?php echo "+6" .$bookingDetails["phone_number"] ?></p>
      </div>
      </div>
    </div>        
  </div>
  <div class="pricing-container">
    <p>Total Price : <span class="price">RM <?php echo $bookingDetails["price"] ?></span></p>
    <p>Down Payment : <span class="price">RM <?php echo $bookingDetails["price"] * 0.1 ?></span></p>
  </div>
  <div class="btn-container">
      <button class="pay-now" data-booking-id=<?php echo $bookingDetails["booking_id"]; ?>>Pay Now</button>
    </div>
  </main>
</body>
</html>



<?php 
  close_connection($conn);
?>