<?php 
include "../db.php";
use Firebase\JWT\JWT;

$conn = create_connection();

if($_SERVER["REQUEST_METHOD"] == "POST"){
  $body = file_get_contents('php://input');
  $data = json_decode($body, true);
  $email = $data['email'];
  $password = $data['password'];
  $phone = $data['phone'];
  $emailExist = doesSellerEmailExist($conn , $email);
  if($emailExist){
    echo json_encode(array('status'=> 'error','message'=> 'Email already exist'));
    exit();
  }
  $isAdded = createNewSeller($conn , $email, $password, $phone);
  if(!$isAdded){
    echo json_encode(array('status'=> 'error','message'=> 'Unknown error'));
    exit();
  }
  $key = "onlycars-seller";
  $payload = ["email" => $email];
  $jwt = JWT::encode($payload, $key, "HS256");
  setcookie("sellerToken", $jwt,  [
    'expires' => time() + 365 * 24 * 60 * 60,
    'path' => '/',
  ]);
  echo json_encode(array('status'=> 'success','message'=> 'Successfully created account'));
}



if($_SERVER["REQUEST_METHOD"] == "GET"){
  $data = $_GET;
  $email = $data['email'];
  $password = $data['password'];
  $user = checkSellerCredential($conn , $email, $password);
  if(!$user){
    echo json_encode(array('status'=> 'error','message'=> 'Invalid username and password'));
    exit();
  }
  $key = "onlycars-seller";
  $payload = ["email" => $email];
  $jwt = JWT::encode($payload, $key, "HS256");
  setcookie("sellerToken", $jwt,  [
    'expires' => time() + 365 * 24 * 60 * 60,
    'path' => '/',
  ]);
  echo json_encode(array('status'=> 'success','message'=> 'Successfully login'));
}


