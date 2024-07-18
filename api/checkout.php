<?php 
use Dotenv\Dotenv;
include "../db.php";
require __DIR__ ."\\..\\vendor\\autoload.php";
$dotenv = Dotenv::createImmutable(__DIR__ . "\\..");
$dotenv->load();

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

if($_SERVER['REQUEST_METHOD'] === "POST"){
  $STRIPE_PUB_KEY = $_ENV["STRIPE_PUB_KEY"];
  $STRIPE_SECRET_KEY = $_ENV["STRIPE_SECRET_KEY"];
  $body = file_get_contents('php://input');
  $data = json_decode($body, true);
  $bookingId = (int) $data["bookingId"];


  $bookingList = getBookingById($conn , $bookingId, $email);
  if(count( $bookingList ) == 0){
    echo json_encode(array("status"=> "error", "message"=> "Booking does not exist"));
    exit();
  }
  $bookingDetails = $bookingList[0];
  $cars = isCarAvailableToBePurchased($conn, $bookingDetails->car_id);
  // check if the car is listed or sold
  if( count($cars) == 0){
    echo json_encode(array("status"=> "error", "message"=> "Car is either not listed or sold out"));
    exit();
  }
  $selectedCar = $cars[0];
  \Stripe\Stripe::setApiKey($STRIPE_SECRET_KEY);
  $YOUR_DOMAIN = "http://localhost/onlycars";
  $customer = \Stripe\Customer::create(["metadata" => array("booking_id" => $bookingId)]);

  try{
    $checkout_session = \Stripe\Checkout\Session::create([
      'line_items' => [[
        'price_data' => [
          "currency" =>  "myr",
            "unit_amount" =>  $selectedCar["price"] * 100 * 0.1,
            "product_data" => [
              "name" =>   $selectedCar["model_name"]
              ]
            ],
        "quantity" => 1
      ],
    ],
    'mode' => 'payment',
    'success_url' => $YOUR_DOMAIN . '/buyer/success.php',
    'cancel_url' => $YOUR_DOMAIN . '/buyer/error.php', 
    "customer" => $customer->id
    ]);
    echo json_encode(array("status" => "success", "url" => $checkout_session->url));
  }catch(\Stripe\Exception\ApiErrorException $e){
    echo json_encode(array('status'=> 'error'));
  }
    close_connection($conn);
  }


