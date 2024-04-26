<?php
  include "../db.php";
  $conn = create_connection();
  $type = isset($_GET["type"]) ? $_GET["type"] : "all";
  if($type != "all" && $type != "completed" && $type != "unpaid"){
    $type = "all";
  } 
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
  $bookings = getBookings( $conn, $type , $authDetails["email"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Orders</title>
  <script src="/onlycars/script/buyer/order.js" defer></script>
  <link rel="stylesheet" href="/onlycars/styles/index.css">
  <link rel="stylesheet" href="/onlycars/styles/buyer/orders.css">
</head>
<body>
  
<?php  include '../components/buyer/navbar.php';?>
  <main>
    <div class="title-container">
      <h2>My Orders</h2>
    </div>
    <div class="section-container">
      <a href="/onlycars/buyer/orders">
        <p class='type-title  <?php echo ($type === "all") ? 'selected' : ''; ?>'>
          All <?php if($type === "all") echo sprintf("(%s)", count($bookings))  ?></p>
      </a>
      <a href="/onlycars/buyer/orders?type=unpaid">
        <p class='type-title <?php echo ($type === "unpaid") ? 'selected' : ''; ?>'>
          Unpaid <?php if($type === "unpaid") echo sprintf("(%s)", count($bookings))  ?></p>
      </a>
      <a href="/onlycars/buyer/orders?type=completed">
        <p class='type-title <?php echo ($type === "completed") ? 'selected' : ''; ?>'>
          Complete <?php if($type === "completed") echo sprintf("(%s)", count($bookings)) ?></p>
      </a>
    </div>

    <div class="booking-container">

      <div class='no-items <?php if(count($bookings) == 0) echo "show"; ?>'> <p>No items</p> </div>
      <?php
        foreach($bookings as $booking) {
          echo sprintf("<div class='booking-item-container' data-booking-id=%s>",$booking["booking_id"]);
          echo sprintf("<img class='car-image' src=%s alt=%s />", $booking["image_url"], $booking["model_name"]);	 
          echo "<div class='car-detail'>";
          echo "<div class='car-container'>";
          echo "<div>";
          if($booking["is_sold"] == 1){
            echo sprintf("<p class='bold'>Car has been sold</p>");
          }
          echo sprintf("<p class='car-title'>%s %s %s</p>",$booking["year"], ucwords($booking["name"]), $booking["model_name"]);
          echo sprintf("<p>Dealer Email: %s</p>", $booking["dealer_email"]);
          echo sprintf("<p>Dealer Phone: +6%s</p>", $booking["phone_number"]);
          echo sprintf("<p>Mileage: %s</p>", $booking["mileage"]);
          echo sprintf("<p>Fuel Type: %s</p>", $booking["fuel_type"]);
          echo sprintf("<p>Transmission: %s</p>", $booking["transmission"]);
          echo sprintf("<p>Color: %s</p>", $booking["color"]);
          if($booking["status"] == "COMPLETED"){
            echo sprintf("<p >Amount Paid : RM %s</p>", $booking["price"] * 0.1);
          }
          echo sprintf("<p class='price'>RM %s</p>", $booking["price"]);
          echo "</div>";
          echo "<div class='price-container'>";
          echo sprintf("<a href=/onlycars/buyer/id.php?id=%s class='btn'>View Car</a>", $booking["car_id"]);  
          if($booking["status"] == "PENDING" && $booking["is_sold"] !== 1){
            echo sprintf("<a href=/onlycars/buyer/checkout?id=%s class='btn'>Checkout</a>", $booking["booking_id"]);
          }
          if($booking["status"] == "PENDING"){
            echo sprintf('<div data-booking-id=%s class="delete-container">
            <svg class="delete-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" id="delete"><path fill="#000" d="M15 3a1 1 0 0 1 1 1h2a1 1 0 1 1 0 2H6a1 1 0 0 1 0-2h2a1 1 0 0 1 1-1h6Z"></path><path fill="#000" fill-rule="evenodd" d="M6 7h12v12a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V7Zm3.5 2a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 1 0v-9a.5.5 0 0 0-.5-.5Zm5 0a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 1 0v-9a.5.5 0 0 0-.5-.5Z" clip-rule="evenodd"></path></svg>
            <p>Delete</p> 
        </div>', $booking["booking_id"]);
          }
          echo "</div>";
          echo "</div>";
          echo "</div>";
          echo "</div>";
        }
      ?>
    </div>
  </main>
</body>
</html>
<?php 
    close_connection($conn);
?>