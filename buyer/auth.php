<?php 
  include "../db.php";
  $conn = create_connection();
  $authDetails = array("isLoggedIn" => false, "email" => "");
  if(array_key_exists("userToken", $_COOKIE)){
    $email = isUserAuthenticated($conn, $userToken = $_COOKIE["userToken"] );
    if($email != null){
      $authDetails["email"] = $email;
      $authDetails["isLoggedIn"] = true;
      header("Location: http://localhost/onlycars/buyer/cars");
      die();
    }else {
      setcookie('userToken', '', time() - 3600, '/');
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OnlyCars Buyers Login & Registration Form</title>
  <link rel="stylesheet" type="text/css" href="/onlycars/styles/auth.css">
  <script src="/onlycars/script/buyer/auth.js" defer></script>
</head>

<body>
  <section id="logo-container" style="text-align: center;">
    <a href="/onlycars/buyer/cars" class="logo" style="text-align: center;">Only<span>Cars</span></a>
  </section>
  <h1 class="cred-title">Buyers's Credentials</h1>
  <div class="wrapper">
    <div class="form-wrapper sign-in">
      <form class="sign-in">
        <h2>Login</h2>
        <div class="input-group">
          <input type="email" id='email' name="email" required>
          <label for="email">Email</label>
          <div class="invalid-email-login"></div>
        </div>
        <div class="input-group">
          <input type="password" id="password" name="password" required>
          <label for="password">Password</label>
          <div class="invalid-password-login"></div>
        </div>
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
          <div class="invalid-email-signup"></div>
        </div>
        <div class="input-group">
          <input type="password" id="passwordSignup" name="passwordSignup" required>
          <label for="passwordSignup">Password</label>
          <div class="invalid-password-signup"></div>
        </div>
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