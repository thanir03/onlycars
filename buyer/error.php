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
  <title>Unsuccessful Payment</title>
  <link rel="stylesheet" href="/onlycars/styles/index.css">
  <link rel="stylesheet" href="/onlycars/styles/buyer/success.css">
</head>
<body>
  

<?php  include '../components/buyer/navbar.php';?>
<main>
  <div class="success-title">
    <h2>Unsuccessful Payment</h2>
  </div>

  <div class="main-content">
    <p>You have not paid yet.</p>
    <p>Please try again.</p>
    <a class="view-orders" href="/onlycars/buyer/orders?type=unpaid">View Orders</a>
  </div>
  
</main>  
</body>
</html>

<?php 
  close_connection($conn);
?>