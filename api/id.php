<?php 

  include "../db.php";

  if(!isset($_GET["id"])){
    http_response_code(401);
    echo "INVALID ID";
    exit();
  }

  if(!is_numeric($_GET["id"])){
    http_response_code(401);
    echo "INVALID ID";
    exit();
  }

  $id = (int)$_GET["id"];

  $conn = create_connection();
  $carDetails = getCarById($conn, $id);
  close_connection($conn);

  http_response_code(200);


  header('Content-Type: application/json');
  $json = json_encode($carDetails);

  echo $json;
  
