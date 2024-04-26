<?php 

include "../db.php";
  require_once __DIR__ . '/../vendor/autoload.php';
  use Dotenv\Dotenv;  
  use Aws\S3\S3Client;


  $conn = create_connection();
  if($_SERVER["REQUEST_METHOD"] == "GET"){
    $pageNumber = (int)isset($_GET["page"]) ? (int)$_GET["page"] : 0;
    $brand = isset($_GET["brand"]) ? explode(",", $_GET["brand"]) : array();
    $transmission = isset($_GET["transmission"]) ? $_GET["transmission"] : null;
    $colors = isset($_GET["color"]) ? explode(",", $_GET["color"]) : array();
    $fuel = isset($_GET["fuel"]) ? $_GET["fuel"] : null;
    $order = isset($_GET["order"]) ? $_GET["order"] : "DESC";
    
    
    if($order != "ASC" && $order != "DESC"){
      http_response_code(401);
      echo json_encode("Invalid order");
      exit();
    }
    
  $car_list = findCars($conn, $brand, $transmission, $colors, $fuel , $order, $pageNumber);

  http_response_code(200);

  header('Content-Type: application/json');
    echo json_encode($car_list); 
  }

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    $dotenv = Dotenv::createImmutable(__DIR__ . "//../");
    $dotenv->load();      

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

    if($authDetails["isLoggedIn"] == false){
      echo json_encode(array("status" => "error", "message" => "User is not authenticated"));
      exit();
    }

    $images =   $_FILES["images"];
    $bucket = $_ENV["S3_BUCKET"];
    $s3 = new S3Client([
      'version'  => 'latest',
      'region'   => 'us-east-1',
      "credentials"=> [
        "key" =>  $_ENV['S3_ACCESS_KEY'],
        "secret" => $_ENV['S3_SECRET_KEY']
      ]
    ]);
    $uploadedData = array();
    $s3Message = "";
    $isFailedToUploadImage = false;
    try{
      // upload images to s3 store
      for($i=0; $i<count($images["name"]); $i++){
        $result = $s3->putObject([
          "Bucket" => $bucket,
          "Key" => $_POST["model_name"] . "/" .(string) ($i + 1)  . "." . pathinfo($images["name"][$i], PATHINFO_EXTENSION),
          "ACL" => "public-read",
          "SourceFile" => $images["tmp_name"][$i],
        ]);
        $result_arr = $result->toArray();
        array_push($uploadedData, $result_arr);
      }
    } catch(AWS\S3\Exception\S3Exception $e){
      $s3Message = $e->getMessage();
      $isFailedToUploadImage = true;
    }
    if($isFailedToUploadImage) {
      echo json_encode(array("status" => "error", "message" => "Failed to upload images", "s3" =>$s3Message));
      exit();
    }

    $imageUrls = array();
    foreach($uploadedData as $img){
      array_push($imageUrls, $img["ObjectURL"]);
    }

    $carId = createNewCar($conn , $authDetails["email"], $_POST);
    $hasAddedImages = addCarImages($conn , $carId, $imageUrls);
    echo json_encode(array("status" => "success", "carId" => $carId));

}


if($_SERVER["REQUEST_METHOD"] == "PUT"){
  $body = file_get_contents('php://input');
  $data = json_decode($body, true);
  $carId = $data['carId'];

  $carDetails = array();
  if(isset($data["price"])){
    $carDetails["price"] = $data["price"];
  }
  if(isset($data["isListed"])){
    $carDetails["isListed"] = $data["isListed"];
  }

  $isSuccess = updateCarDetails($conn, $carId, $carDetails);
  if($isSuccess){
    echo json_encode(array("status"=> "success"));
  }else {
    echo json_encode(array("status"=> "error", "message"=> "Failed to update details"));
  }
}

close_connection($conn);
