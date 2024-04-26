<?php 
  include "../db.php";
  $conn = create_connection();


  $authDetails = array("isLoggedIn" => false, "email" => "");
    if(array_key_exists("userToken", $_COOKIE)){
      $email = isUserAuthenticated($conn, $_COOKIE["userToken"] );
      if($email != null){
        $authDetails["email"] = $email;
        $authDetails["isLoggedIn"] = true;
        }else {
        setcookie('userToken', '', time() - 3600, '/');
        }
    }
    if($authDetails["isLoggedIn"] == false){
      echo json_encode(array("status" => "error", "message" => "User is not authenticated"));
      exit();
    }
    $email = $authDetails["email"];
  
  if($_SERVER['REQUEST_METHOD'] == "POST"){
    
  $body = file_get_contents('php://input');
  $data = json_decode($body, true);

  http_response_code(201);
  header('Content-Type: application/json; charset=utf-8');
  if(!isset($data["carId"])){
    http_response_code(403);
    echo json_encode(array("error"=> "Empty car id"));
    exit();
  }

  $carId = $data["carId"];
  $cars = findCarById($conn, $carId);
  if(count($cars) === 0){
    http_response_code(403);
    echo json_encode(array("error"=> "Invalid car id"));
    exit();
  }


  $isCarBooked = checkCarBooked($conn, $carId);
  if($isCarBooked){
    http_response_code(409);
    echo json_encode(array("error"=> "Car has already booked"));
    exit();
  }

  $bookings = getUserBooking($conn, $carId, $email);
  if(count($bookings) > 0){
    http_response_code(400);
    echo json_encode(array("error"=> "You already have a booking"));
    exit();
  }
// Optional only when the item is in cart
  deleteCarFromCart($conn, $carId,$email);
  $isCarBooked = createBooking($conn , $carId, $email);
  if(!isset($isCarBooked)){
    http_response_code(500);
    echo json_encode(array("error"=> "Error processing booking"));
  }else {
    $carBookedDetails = getUserBooking($conn, $carId , $email);
    if(count($carBookedDetails) === 0){
      echo "empty";
    }
    http_response_code(201);
    echo json_encode(array("success" => "Successful making booking", 'data' => $carBookedDetails[0] ));
  }
}

if($_SERVER['REQUEST_METHOD'] == "DELETE"){
    $bookingId = (int) $_GET["bookingId"];
    $isDeleted = deleteBooking($conn, $bookingId, $email);
  if($isDeleted){
    http_response_code(202);
    echo  json_encode(array("status" =>  "success", "message"=> "Successful deletion" ));
  }else {
    http_response_code(500);
    echo  json_encode(array("status" =>  "error", "message"=> "Fail to delete booking" ));
  }
}
close_connection($conn) ;
