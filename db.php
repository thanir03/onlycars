<?php
  include 'carModel.php';
  include 'bookingModel.php';
  use Dotenv\Dotenv;  
  use Firebase\JWT\JWT;
  use Firebase\JWT\Key;
  require __DIR__.'/vendor/autoload.php';

  function create_connection(){
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    $dbHost = $_ENV['DB_HOST'];
    $dbName = $_ENV['DB_DATABASE'];
    $dbUsername = $_ENV['DB_USERNAME'];
    $dbPassword = $_ENV['DB_PASSWORD'];
    $conn = mysqli_connect($dbHost , $dbUsername , $dbPassword , $dbName);
    if(!$conn){
      die("Database Connection failed". mysqli_connect_error());    
    }
    return $conn;
  }
  
  function close_connection($conn){
    mysqli_close($conn);
  }

  function findBrands($conn) :array{
    $sql = "SELECT * FROM car_brand";
    $result = mysqli_query($conn, $sql);
    $brand_result = array();
    while( $row = mysqli_fetch_assoc($result) ){
        $brand = new stdClass();
        $brand->brand_name = $row["name"];
        $brand->brand_id = $row["brand_id"];
        $brand->brand_logo = $row["brand_logo"];
        array_push($brand_result, $brand);
      }
      return $brand_result;
  }
  
  function findColors($conn){
    $sql = "SELECT DISTINCT(color) FROM car" ;
    $result = mysqli_query($conn, $sql);
    $color_list = array();
    while( $row = mysqli_fetch_assoc($result) ){
        array_push($color_list, $row["color"]);
      }
      return $color_list;
  }

function findBrandByName($conn, $brand){
  $sql = "SELECT * FROM car_brand WHERE NAME = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "s", $brand);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
    $brand_result = array();
    while( $row = mysqli_fetch_assoc($result) ){
        $brand = new stdClass();
        $brand->brand_name = $row["name"];
        $brand->brand_id = $row["brand_id"];
        $brand->brand_logo = $row["brand_logo"];
        array_push($brand_result, $brand);
      }
      mysqli_stmt_close($stmt);
      return $brand_result;
}
function findCarsByBrand($conn, $brand_id){
  $sql = "SELECT * FROM
  (SELECT
      car.*,
      car_brand.name as brand_name,
      car_brand.brand_logo as brand_logo,
      JSON_ARRAYAGG(car_image.image_url)AS image_urls
  FROM
      car
  INNER JOIN
      car_brand ON car_brand.brand_id = car.brand_id
  INNER JOIN
      car_image ON car_image.car_id = car.car_id
    WHERE car.BRAND_ID = ?
  GROUP BY
      car.car_id) as subquery
ORDER BY subquery.price DESC
LIMIT 10;";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "i", $brand_id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
    $car_result = array();
    while( $row = mysqli_fetch_assoc($result) ){
        array_push($car_result, new Car($row));
    }

    mysqli_stmt_close($stmt);
    return $car_result;
}


function findMoreCars($conn, $pageNumber){
$sql = "SELECT * FROM
  (SELECT
      car.*,
      car_brand.name as brand_name,
      car_brand.brand_logo as brand_logo,
      JSON_ARRAYAGG(car_image.image_url)AS image_urls
  FROM
      car
  INNER JOIN
      car_brand ON car_brand.brand_id = car.brand_id
  INNER JOIN
      car_image ON car_image.car_id = car.car_id
  GROUP BY
      car.car_id) as subquery
ORDER BY subquery.price DESC
LIMIT 10 OFFSET ?;";

  $stmt = mysqli_prepare($conn, $sql);
  $offset = $pageNumber * 10;
  mysqli_stmt_bind_param($stmt , "i",  $offset);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $car_result = array();
  while( $row = mysqli_fetch_assoc($result) ){
      array_push($car_result, new Car($row));
    }
    mysqli_stmt_close($stmt);
    return $car_result;
}


function findCars($conn ,array $brand , $transmission, $colors , $fuel, $order, $pageNumber){
  $sql = "SELECT * FROM
      (SELECT
          car.*,
          car_brand.name as brand_name,
          car_brand.brand_logo as brand_logo,
          JSON_ARRAYAGG(car_image.image_url)AS image_urls
      FROM
          car
      INNER JOIN
          car_brand ON car_brand.brand_id = car.brand_id
      INNER JOIN 
          car_image ON car_image.car_id = car.car_id ";

  $filter_query = "";


  if(count($brand) > 0){
    $brand = array_map(function ($value){
      return sprintf("'%s'", $value);
    }, $brand);
    $filter_query .= sprintf("car_brand.name IN ( %s ) ",implode(" , " ,$brand));
  }

  if($transmission){
    if (strlen($filter_query) > 0 ) {
      $filter_query .= " AND ";
    }
    $filter_query .= sprintf(" car.transmission = '%s' ", $transmission); 
  }

  if(count($colors) > 0){
    if (strlen($filter_query) > 0 ) {
      $filter_query .= " AND ";
    }
    $colors = array_map(function ($value){
      return sprintf("'%s'", ucwords($value));
    }, $colors);
    $filter_query .= sprintf("car.color IN ( %s ) ",implode(" , " , ($colors)));
  }

  if($fuel){
    if (strlen($filter_query) > 0 ) {
      $filter_query .= " AND ";
    }
    $filter_query.= sprintf(" car.fuel_type IN ( '%s' ) ", $fuel);
  }

  if(strlen($filter_query) == 0){
    $sql .= " WHERE is_sold = 0 AND is_listed = 1 ";
  }

  if(strlen($filter_query) > 0){
    $sql .= " WHERE " . $filter_query . " AND is_sold = 0 AND is_listed = 1 ";
  }
  $sql .= sprintf("GROUP BY
      car.car_id) as subquery
ORDER BY subquery.price %s LIMIT 10 OFFSET %s" , $order, $pageNumber * 10);
  
    $result = mysqli_query($conn, $sql);
  $car_result = array();
  while( $row = mysqli_fetch_assoc($result) ){
      array_push($car_result, new Car($row));
    }

  return $car_result;
}


function getCarById($conn ,int $id){
  $sql = "SELECT
  car.*,
  car_brand.name as brand_name,
  car_brand.brand_logo as brand_logo,
  JSON_ARRAYAGG(car_image.image_url)AS image_urls
FROM
  (SELECT * FROM car WHERE car_id = ?) as car
      INNER JOIN
  car_brand ON car_brand.brand_id = car.brand_id
      INNER JOIN
  car_image ON car_image.car_id = car.car_id
";

  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt , "i",  $id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $car_result = array();
  while( $row = mysqli_fetch_assoc($result) ){
    array_push($car_result, new Car($row));
    }
    mysqli_stmt_close($stmt);
    return $car_result[0];
}

function getSellersCarById($conn ,int $id, string $email){
  $sql = "SELECT
  car.*,
  car_brand.name as brand_name,
  car_brand.brand_logo as brand_logo,
  JSON_ARRAYAGG(car_image.image_url)AS image_urls
FROM
  (SELECT * FROM car WHERE car_id = ?) as car
      INNER JOIN
  car_brand ON car_brand.brand_id = car.brand_id
      INNER JOIN
  car_image ON car_image.car_id = car.car_id
  WHERE dealer_email = ?
";

  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt , "is",  $id , $email);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $car_result = array();
  while( $row = mysqli_fetch_assoc($result) ){
    if($row["car_id"] == null){
      return $car_result;
    }
    array_push($car_result, new Car($row));
    }
    mysqli_stmt_close($stmt);
    return $car_result[0];
}



function findCarById($conn ,int $id){
  $sql = "SELECT * FROM car WHERE car.car_id = ? ";

  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt , "i",  $id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $car_result = array();
  while( $row = mysqli_fetch_assoc($result) ){
    array_push($car_result, new Car($row));
  }
  mysqli_stmt_close($stmt);
  return $car_result;
}


function createBooking($conn ,int $id, $email){
  $sql = "INSERT INTO booking (user_email, car_id, status) VALUES ( ? , ? , 'PENDING');";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt , "si", $email ,  $id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  mysqli_stmt_close($stmt);
  return $result;
}


function checkCarBooked($conn, int $id){
  $sql = "SELECT * FROM booking WHERE booking.car_id = ? and ( status = 'COMPLETED' or status = 'PROCESSING'or status = 'BOOKED' or status = 'COMPLETED')";

  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt , "i",  $id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $carBooked = false;
  while( $row = mysqli_fetch_assoc($result) ){
    $carBooked = true;
  }
  mysqli_stmt_close($stmt);
  return $carBooked;
}

function getUserBooking($conn, int $id, $email){
  $sql = "SELECT * FROM booking WHERE booking.car_id = ? and booking.user_email = ? ";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt , "is",  $id, $email);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $bookings = array();
  while( $row = mysqli_fetch_assoc($result) ){
    array_push($bookings, new Booking($row));    
  }
  mysqli_stmt_close($stmt);
  return $bookings;
}

function getBookingById($conn, int $id, $email){
  $sql = "SELECT * FROM booking WHERE booking.booking_id = ? and booking.user_email = ? ";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt , "is",  $id, $email);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $bookings = array();
  while( $row = mysqli_fetch_assoc($result) ){
    array_push($bookings, new Booking($row));    
  }
  mysqli_stmt_close($stmt);
  return $bookings;
}

function isCarAvailableToBePurchased($conn, $carId){
  $sql = "SELECT * FROM car WHERE car_id = ? and is_sold = false and is_listed = true";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt , "i",  $carId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $car = array();
  while( $row = mysqli_fetch_assoc($result) ){
    array_push($car, $row);    
  }
  mysqli_stmt_close($stmt);
  return $car;
}

function successPayment($conn, $bookingId){
  $sql = "UPDATE booking SET status = 'COMPLETED' WHERE booking_id = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt , "i", $bookingId);
  mysqli_stmt_execute($stmt);
  $count = mysqli_affected_rows($conn);
  mysqli_stmt_close($stmt);
  return $count > 0; 
}

function getCarByBookingId($conn, $bookingId){
  $sql = "SELECT car_id FROM booking WHERE booking_id = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt , "i",  $bookingId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $car = array();
  while( $row = mysqli_fetch_assoc($result) ){
    array_push($car, $row);    
  }
  mysqli_stmt_close($stmt);
  return $car[0]["car_id"];
}

function updateCarAsSold($conn, $carId){
  $sql = "UPDATE car SET is_sold = 1 WHERE car_id = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt , "i", $carId);
  mysqli_stmt_execute($stmt);
  mysqli_affected_rows($conn);
  $count = mysqli_affected_rows($conn);
  mysqli_stmt_close($stmt);
  return $count > 0; 
}

function getBookings($conn, $type, $email){
  $sql = "SELECT * FROM booking
  INNER JOIN car C on booking.car_id = C.car_id
  INNER JOIN car_brand CB on C.brand_id = CB.brand_id
  INNER JOIN (SELECT image_url, car_id from car_image group by car_id) CI on C.car_id = CI.car_id
  INNER JOIN dealer D on C.dealer_email = D.email WHERE booking.user_email = ?";
  if($type == "unpaid"){
    $sql .= " AND booking.status = 'PENDING'";
  }else if($type == "completed"){
    $sql .= " AND booking.status = 'COMPLETED'";
  }
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt , "s",  $email);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $bookings = array();
  while( $row = mysqli_fetch_assoc($result) ){
    array_push($bookings, $row);    
  }
  mysqli_stmt_close($stmt);
  return $bookings;
}

function deleteBooking ($conn , int $bookingId, $email){
  $sql = "DELETE FROM booking WHERE booking_id = ? AND user_email = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt , "is", $bookingId, $email);
  mysqli_stmt_execute($stmt);
  $count = mysqli_affected_rows($conn);
  mysqli_stmt_close($stmt);
  return $count > 0; 
}


function createNewCar($conn, $email, $data){
  $sql = "INSERT INTO car (brand_id, dealer_email, model_name, year, mileage, price, fuel_type, transmission, seat, doors, width, height, length, color) values (?, ?, ?,?, ?, ?,?, ?, ?,?, ?, ?,?, ?);";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt , "issiiissiiiiis", $data["brand"], $email , $data["model_name"], $data["year"],$data["mileage"],$data["price"], $data["fuel"], $data["transmission"], $data["seats"], $data["doors"], $data["width"], $data["height"], $data["length"], $data["color"]);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_get_result($stmt);
  $latestId = mysqli_insert_id($conn);
  mysqli_stmt_close($stmt);
  return $latestId;
}

function addCarImages($conn , $carId, $imageUrls){
  $sql = "INSERT INTO car_image (car_id, image_url) values ";
  foreach($imageUrls as $url){
    $sql .= sprintf("(%s, '%s') ,", $carId, $url);
  }
  $sql = substr($sql,0,-1);
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $count = mysqli_affected_rows($conn);
  mysqli_stmt_close($stmt);
  return $count > 0; 
}

function findSellersCar($conn , $email){
  $sql = "SELECT * FROM
  (SELECT
      car.*,
      car_brand.name as brand_name,
      car_brand.brand_logo as brand_logo,
      JSON_ARRAYAGG(car_image.image_url)AS image_urls
  FROM
      car
  INNER JOIN
      car_brand ON car_brand.brand_id = car.brand_id
  INNER JOIN
      car_image ON car_image.car_id = car.car_id
  GROUP BY
  car.car_id) as subquery
WHERE dealer_email = ? ";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt , "s",  $email);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $car_result = array();
  while( $row = mysqli_fetch_assoc($result) ){
    array_push($car_result, new Car($row));
  }
  mysqli_stmt_close($stmt);
  return $car_result;
}


function updateCarDetails($conn ,$carId , $carDetails){
    $sql = "UPDATE car SET ";
    $update = "";
    if(array_key_exists("price", $carDetails)){
      $update .= sprintf("PRICE = %s" , $carDetails["price"] ); 
    }

    if(array_key_exists("isListed", $carDetails)){
      if(strlen($update) == 0){
        $update .= sprintf("is_listed = %s" , (boolean) $carDetails["isListed"] ?  1 : 0 );
      }else {
        $update .= sprintf(", is_listed = %s" , (boolean) $carDetails["isListed"] );
      }
    }
    
    $sql .= " " . $update . sprintf(" WHERE car_id = %s;", $carId);
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $count = mysqli_affected_rows($conn);
    mysqli_stmt_close($stmt);
    return $count > 0; 
  }
  

function doesEmailExist($conn, $email){
  $sql = "SELECT * FROM user WHERE user_email = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt , "s",  $email);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $exist = false;
  while( $row = mysqli_fetch_assoc($result) ){
    $exist = true;
  }
  mysqli_stmt_close($stmt);
  return $exist;
}

function createNewUser($conn , $email, $password){
  $sql = "INSERT INTO user (user_email, password) VALUES (? , ?);";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt , "ss", $email ,  $password);
  mysqli_stmt_execute($stmt);
  $count = mysqli_affected_rows($conn);
  mysqli_stmt_close($stmt);
  return $count > 0; 
}

function checkCredential($conn ,$email, $password ){
  $sql = "SELECT * FROM user WHERE user_email = ? AND password = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt , "ss",  $email, $password);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $exist = false;
  while( $row = mysqli_fetch_assoc($result) ){
    if($row["user_email"] != null){
      $exist = true;
    }
  }
  mysqli_stmt_close($stmt);
  return $exist;
}


function doesSellerEmailExist($conn, $email){
  $sql = "SELECT * FROM dealer WHERE email = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt , "s",  $email);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $exist = false;
  while( $row = mysqli_fetch_assoc($result) ){
    $exist = true;
  }
  mysqli_stmt_close($stmt);
  return $exist;
}

function createNewSeller($conn , $email, $password, $phone){
  $sql = "INSERT INTO dealer (email, password, phone_number) VALUES (? , ?, ?);";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt , "sss", $email ,  $password, $phone);
  mysqli_stmt_execute($stmt);
  $count = mysqli_affected_rows($conn);
  mysqli_stmt_close($stmt);
  return $count > 0; 
}

function checkSellerCredential($conn ,$email, $password ){
  $sql = "SELECT * FROM dealer WHERE email = ? AND password = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt , "ss",  $email, $password);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $exist = false;
  while( $row = mysqli_fetch_assoc($result) ){
    if($row["email"] != null){
      $exist = true;
    }
  }
  mysqli_stmt_close($stmt);
  return $exist;
}


function isUserAuthenticated($conn , $token ){
  $key = "onlycars-user";
  try{
    $jwt = JWT::decode($token,new Key($key, "HS256"));
  }catch(Firebase\JWT\SignatureInvalidException $e){
    return null;
  }
  $email = $jwt->email;
  if(doesEmailExist($conn, $email)) return $email;
  return null;
}


function isSellerAuthenticated($conn , $token){
  $key = "onlycars-seller";
  try{
    $jwt = JWT::decode($token,new Key($key, "HS256"));
  }catch(Firebase\JWT\SignatureInvalidException $e){
    return null;
  }
  $email = $jwt->email;
  if(doesSellerEmailExist($conn, $email)) return $email;
  return null;
}

function addCarToCart($conn, string $email ,int $carId){
  $sql = "INSERT INTO cart (user_email, car_id ) VALUES ( ? , ? );";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt , "si", $email ,  $carId);
  mysqli_stmt_execute($stmt);
  $count = mysqli_affected_rows($conn);
  mysqli_stmt_close($stmt);
  return $count > 0; 
}

function isCarInCart($conn, string $email ,int $carId){
  $sql = "SELECT * FROM cart WHERE user_email = ? AND car_id = ?;";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt , "si", $email, $carId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $carBooked = false;
  while($row = mysqli_fetch_assoc($result) ){
    $carBooked = true;
  }
  mysqli_stmt_close($stmt);
  return $carBooked;
}


function getAllCarsInCart($conn, string $email ){
  $sql = "SELECT * FROM car
INNER JOIN (SELECT * FROM cart WHERE user_email = ?) cart on cart.car_id = car.car_id
INNER JOIN (SELECT * FROM car_image GROUP BY car_id) as CI on car.car_id = CI.car_id
INNER JOIN car_brand CB on car.brand_id = CB.brand_id;";

  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt , "s", $email);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $arr = array();
  while($row = mysqli_fetch_assoc($result) ){
    array_push($arr, $row);
  }
  mysqli_stmt_close($stmt);
  return $arr;
}

function deleteCartById($conn , $cartId, $email){
  $sql = "DELETE FROM cart WHERE cart_id = ? AND user_email = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt , "is", $cartId, $email);
  mysqli_stmt_execute($stmt);
  $count = mysqli_affected_rows($conn);
  mysqli_stmt_close($stmt);
  return $count > 0; 
}

function deleteCarFromCart($conn , $carId, $email){
  $sql = "DELETE FROM cart WHERE car_id = ? AND user_email = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt , "is", $carId, $email);
  mysqli_stmt_execute($stmt);
  $count = mysqli_affected_rows($conn);
  mysqli_stmt_close($stmt);
  return $count > 0; 
}

function findBooking($conn, int $bookingId, string $email){
  $sql = "SELECT * FROM booking
  INNER JOIN car C on booking.car_id = C.car_id
  INNER JOIN car_brand CB on C.brand_id = CB.brand_id
  INNER JOIN (SELECT image_url, car_id from car_image group by car_id) CI on C.car_id = CI.car_id
  INNER JOIN dealer D on D.email = C.dealer_email WHERE user_email = ? AND booking_id = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt , "si", $email, $bookingId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $bookings = array();
  while($row = mysqli_fetch_assoc($result) ){
    array_push($bookings , $row);
  }
  mysqli_stmt_close($stmt);
  return $bookings;
}
