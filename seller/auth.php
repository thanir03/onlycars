<?php 
  include "../db.php";
  $conn = create_connection();
  $authDetails = array("isLoggedIn" => false, "email" => "");
  if(array_key_exists("sellerToken", $_COOKIE)){
    $email = isSellerAuthenticated($conn, $_COOKIE["sellerToken"] );
    if($email != null){
      $authDetails["email"] = $email;
      $authDetails["isLoggedIn"] = true;
      header("Location: http://localhost/onlycars/seller/cars");
      die();
    }else {
      setcookie('sellerToken', '', time() - 3600, '/');
    }
  }
?>
<!DOCTYPE html> 
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OnlyCars Seller's Login & Registration Form</title>
  <script src="/onlycars/script/seller/auth.js" defer></script>
  <link rel="stylesheet" href="/onlycars/styles/index.css">
  <link rel="stylesheet" href="/onlycars/styles/auth.css">
</head>

<body>
  <section id="logo-container" style="text-align: center;">
    <a href="/onlycars/seller/cars" class="logo" style="text-align: center;">Only<span>Cars</span></a>
  </section>
  <h1 class="cred-title">Seller's Credentials</h1>
  <div class="wrapper">
    <div class="form-wrapper sign-in">
      <form class="sign-in">
        <h2>Login</h2>
        <div class="input-group">
          <input type="email" id='email' name="email" required>
          <label for="email">Email</label>
        </div>
        <div class="invalid-email-login"></div>
        <div class="input-group">
          <input type="password" id="password" name="password" required>
          <label for="password">Password</label>
        </div>
        <div class="invalid-password-login"></div>
        <button type="submit">Login</button>
        <div class="signUp-link">
          <p>Don't have an account? <a href="#" class="signUpBtn-link">Sign Up</a></p>
        </div>
      </form>
    </div>
    <div class="form-wrapper sign-up">
      <form class="sign-up">
        <h2>Sign Up</h2>
        <div class="input-group">
          <input type="email" id="emailSignup" name="emailSignup" required>
          <label for="emailSignup">Email</label>
        </div>
        <div class="invalid-email-signup"></div>
        <div class="input-group">
          <input type="password" id="passwordSignup" name="passwordSignup" required>
          <label for="passwordSignup">Password</label>
        </div>
        <div class="invalid-password-signup"></div>
        <div class="input-group">
          <input type="tel" id="phoneSignup" name="phone" required>
          <label for="phoneSignup">Phone Number</label>
        </div>
        <div class="invalid-phone-signup"></div>
        <button type="submit">Sign Up</button>
        <div class="signUp-link">
          <p>Already have an account? <a href="#" class="signInBtn-link">Sign In</a></p>
        </div>
      </form>
    </div>
  </div>
  <script>
    const signInBtnLink = document.querySelector('.signInBtn-link');
    const signUpBtnLink = document.querySelector('.signUpBtn-link');
    const wrapper = document.querySelector('.wrapper');
    signUpBtnLink.addEventListener('click', function () {
      wrapper.classList.toggle('active');
    });
    signInBtnLink.addEventListener('click', function () {
      wrapper.classList.toggle('active');
    });
  </script>
  <div class="modal"></div>
</body>
</html>