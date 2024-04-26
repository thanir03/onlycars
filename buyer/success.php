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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Successful Payment</title>
  <link rel="stylesheet" href="/onlycars/styles/index.css">
  <link rel="stylesheet" href="/onlycars/styles/buyer/success.css">
</head>
<body>
  
<?php  include '../components/buyer/navbar.php';?>
<main>
  <div class="success-title">
    <h2>Successful Payment</h2>
  </div>

  <div class="main-content">
    <p>You have succesfully paid.</p>
    <p>Please contact the seller for further payments.</p>
    <p>Enjoy your ride</p>
    <a class="view-orders" href="/onlycars/buyer/orders?type=completed">View Orders</a>
  </div>
  
</main>  
</body>
</html>

<?php 
  close_connection($conn);
?>