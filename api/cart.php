<?php 
  include "../db.php";
  $method = $_SERVER['REQUEST_METHOD'];

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

  if($method === 'POST') {
    // create a new instance in the cart
    // read the body from the http request 
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);
    $carId = $data["carId"];
    $carExist = isCarInCart($conn, $email , $carId);
    header('Content-Type: application/json');
    
    if($carExist) {
      echo json_encode(array("status"=> "error","message"=> "Car already exist in cart"));
      exit();
    }
    $result = addCarToCart($conn,$email,$carId);
    if($result) {
      echo json_encode(array("status"=> "success","message"=> "Successful adding to cart"));
    }else {
      echo json_encode(array("status"=> "error","message"=>"Unknown error occurred"));
    }
  }


  if($method === "GET"){
    $cart_list = getAllCarsInCart($conn, $email);
    header('Content-Type: application/json');
    echo json_encode($cart_list);
    close_connection($conn);
  }


  if($method === "DELETE"){
    $cartId = (int) $_GET["cartId"];
    $doesCarExist = false;
    $cart_list = getAllCarsInCart($conn, $email);
    for( $i = 0; $i < count($cart_list); $i++ ){
      if( $cart_list[$i]["cart_id"] == $cartId ){
          $doesCarExist = true;
          break;
      }    
    }

    if(!$doesCarExist){
      echo json_encode(array("status"=> "error","message"=> "Cart Id does not exist"));
      exit();
    }
    $res = deleteCartById($conn, $cartId, $email);
    if( $res === true ){
      echo json_encode(array("status"=> "success","message"=> "Sucessful removing from cart"));
    }else {
      echo json_encode(array("status"=> "error","message"=> "Unknown error"));
    }
  }
  close_connection($conn);

?>
