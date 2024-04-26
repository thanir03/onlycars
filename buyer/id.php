<?php 
  include "../db.php";

  if(!isset($_GET["id"])){
    http_response_code(401);
    echo "EMPTY ID";
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
  if(!isset($carDetails->car_id)){
    echo " DOES NOT EXIST";
    exit();
  }
  $authDetails = array("isLoggedIn" => false, "email" => "");
  if(array_key_exists("userToken", $_COOKIE)){
    $email = isUserAuthenticated($conn, $userToken = $_COOKIE["userToken"] );
    if($email != null){
      $authDetails["email"] = $email;
      $authDetails["isLoggedIn"] = true;
    }
  }
  
  
  for($i=0; $i<count($carDetails->image_urls); $i++){
    $carDetails->image_urls[$i] = str_replace("width=480", "width=1840", $carDetails->image_urls[$i]);
  }

?>
<!-- If this car is sold out or unlisted, display it somewhere -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo ucwords($carDetails->brand_name) . $carDetails->model_name ?></title>
  <link rel="stylesheet" href="/onlycars/styles/index.css">
  <link rel="stylesheet" href="/onlycars/styles/buyer/id.css">
  <script src="/onlycars/script/buyer/id.js" defer type="module"></script>
</head>
<body data-car_id=<?php echo $carDetails->car_id ?>>
  
  <nav class="nav-bar">

  <a href="/onlycars/buyer/cars">
    <svg class="back-btn" width="30px" height="30px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg"><path fill="#000000" d="M224 480h640a32 32 0 1 1 0 64H224a32 32 0 0 1 0-64z"/><path fill="#000000" d="m237.248 512 265.408 265.344a32 32 0 0 1-45.312 45.312l-288-288a32 32 0 0 1 0-45.312l288-288a32 32 0 1 1 45.312 45.312L237.248 512z"/></svg>
  </a>
    <a href="/onlycars/buyer/cars.php">
      <h1 class="onlycars-title">Only<span class="onlycars-title-span">Cars</span></h1>
  </a>    
  </nav>

  <main class="main-content-container">

    
  <div class="car-dummy-container"></div>
<!-- Image list -->
  <div class="car-image-container">
      <svg xmlns="http://www.w3.org/2000/svg" class="left-arrow-icon" data-index=%s  xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" height="30px" width="30px" version="1.1" id="Layer_1" viewBox="0 0 330 330" xml:space="preserve">
          <path id="XMLID_6_" d="M165,0C74.019,0,0,74.019,0,165s74.019,165,165,165s165-74.019,165-165S255.981,0,165,0z M205.606,234.394  c5.858,5.857,5.858,15.355,0,21.213C202.678,258.535,198.839,260,195,260s-7.678-1.464-10.606-4.394l-80-79.998  c-2.813-2.813-4.394-6.628-4.394-10.606c0-3.978,1.58-7.794,4.394-10.607l80-80.002c5.857-5.858,15.355-5.858,21.213,0  c5.858,5.857,5.858,15.355,0,21.213l-69.393,69.396L205.606,234.394z"/>
    </svg>
    <svg fill="#000000" height="30px" width="30px" class="right-arrow-icon" data-index=%s  version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 330 330" xml:space="preserve">
          <path id="XMLID_2_" d="M165,0C74.019,0,0,74.019,0,165s74.019,165,165,165s165-74.019,165-165S255.981,0,165,0z M225.606,175.605  l-80,80.002C142.678,258.535,138.839,260,135,260s-7.678-1.464-10.606-4.394c-5.858-5.857-5.858-15.355,0-21.213l69.393-69.396  l-69.393-69.392c-5.858-5.857-5.858-15.355,0-21.213c5.857-5.858,15.355-5.858,21.213,0l80,79.998  c2.814,2.813,4.394,6.628,4.394,10.606C230,168.976,228.42,172.792,225.606,175.605z"/>
    </svg>
    
    <?php 
      for($i =0; $i<count($carDetails->image_urls); $i++){
        if($i === 0){
          echo sprintf("<img data-image=%s class='car-image image-active' src=%s alt=%s />", $i, $carDetails->image_urls[$i], ucwords($carDetails->brand_name) . $carDetails->model_name);
        }else {
          echo sprintf("<img data-image=%s class='car-image' src=%s alt=%s />", $i , $carDetails->image_urls[$i], ucwords($carDetails->brand_name) . $carDetails->model_name);
        }
      }
    ?>

  </div>

  <div class="car-details-container">
    <?php echo sprintf("<p class='sold'>%s</p>", $carDetails->is_sold ? "Car has been sold" : ""); ?>
    <?php echo sprintf("<p class='sold'>%s</p>", !$carDetails->is_listed ? "Car has been unlisted" : ""); ?>
    <div class="brand-details">  
      <p class="brand-name"><?php echo ucwords($carDetails->brand_name)  ?></p>
      <img width="50px" src=<?php echo $carDetails->brand_logo?> alt=<?php echo ucwords($carDetails->brand_name)  ?> />
    </div>
    <div class="model-name">
      <p><?php echo ($carDetails->year)  ?></p>
      <p><?php echo ucwords($carDetails->model_name)  ?></p>
    </div>
      <div class="car-minor-details">
      <div class="mileage-details">
        <svg width="30px"  viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg"><path fill="#000000" d="M512 896a384 384 0 1 0 0-768 384 384 0 0 0 0 768zm0 64a448 448 0 1 1 0-896 448 448 0 0 1 0 896z"/><path fill="#000000" d="M192 512a320 320 0 1 1 640 0 32 32 0 1 1-64 0 256 256 0 1 0-512 0 32 32 0 0 1-64 0z"/><path fill="#000000" d="M570.432 627.84A96 96 0 1 1 509.568 608l60.992-187.776A32 32 0 1 1 631.424 440l-60.992 187.776zM502.08 734.464a32 32 0 1 0 19.84-60.928 32 32 0 0 0-19.84 60.928z"/></svg>
        <p class="bold-text"><?php echo (number_format($carDetails->mileage))  ?> km </p>
      </div>    
      <div class="door-details">
        <svg fill="#000000" width="20px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
	 viewBox="0 0 512 512" xml:space="preserve">  
        <g>
	        <g>
          <path d="M506.583,9.847C501.727,3.635,494.286,0,486.402,0h-256c-6.793,0-13.303,2.697-18.099,7.501l-204.8,204.8
          c-4.804,4.796-7.501,11.307-7.501,18.099v256c0,14.14,11.46,25.6,25.6,25.6h409.6c14.14,0,25.6-11.46,25.6-25.6V233.549
          l50.432-201.745C513.145,24.158,511.43,16.06,506.583,9.847z M435.202,486.4h-409.6V256h409.6V486.4z M435.202,230.4h-409.6
          l204.8-204.8h256L435.202,230.4z"/>
      </g>
    </g>
    <g>
      <g>
        <rect x="332.802" y="281.6" width="76.8" height="25.6"/>
      </g>
    </g>
  </svg>
  <p class="bold-text"><?php echo ($carDetails->doors)  ?> doors</p>
</div>
  <div class="seat-details">
        <svg width="20px" fill="#000000" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
      viewBox="0 0 240.235 240.235" xml:space="preserve">
        <path d="M211.744,6.089C208.081,2.163,203.03,0,197.52,0h-15.143c-11.16,0-21.811,8.942-23.74,19.934l-0.955,5.436
        c-0.96,5.47,0.332,10.651,3.639,14.589c3.307,3.938,8.186,6.106,13.74,6.106h19.561c2.714,0,5.339-0.542,7.778-1.504l-2.079,17.761
        c-2.001-0.841-4.198-1.289-6.507-1.289h-22.318c-9.561,0-18.952,7.609-20.936,16.961l-19.732,93.027l-93.099-6.69
        c-5.031-0.36-9.231,1.345-11.835,4.693c-2.439,3.136-3.152,7.343-2.009,11.847l10.824,42.618
        c2.345,9.233,12.004,16.746,21.53,16.746h78.049h1.191h39.729c9.653,0,18.336-7.811,19.354-17.411l15.272-143.981
        c0.087-0.823,0.097-1.634,0.069-2.437l5.227-44.648c0.738-1.923,1.207-3.967,1.354-6.087l0.346-4.97
        C217.214,15.205,215.407,10.016,211.744,6.089z"/>
    </svg>
        <p class="bold-text"><?php echo ($carDetails->seat)  ?> seats</p>
      </div>
      </div>
      
      <p class="price">RM <?php echo number_format($carDetails->price)  ?> Vehicle Price</p>
      <p class="price-details-text">Excludes taxes and additional fees</p>

      
      <h3 class="vehicle-details-text">Vehicle Details</h3>
      <ul class="car-major-details">
        <li class="fuel-details">
          <img src="https://www.carsome.my/_nuxt/img/car-value-type.8112295.svg" alt=<?php echo ($carDetails->fuel_type)  ?>>
          <p><?php echo ($carDetails->fuel_type)  ?></p>
        </li>
        
        <li class="transmission-details">
          <img  src="https://www.carsome.my/_nuxt/img/car-value-transmission.b6affe9.svg" alt="gear-stick"/>
          <p><?php echo ($carDetails->transmission)  ?></p>
        </li>
        
        <?php
        if($carDetails->width){
          echo sprintf("<li>Width : %s cm</li>", number_format($carDetails->width));
        }
        ?>
        <?php
        if($carDetails->height){
          echo sprintf("<li>Height : %s cm</li>", number_format($carDetails->height) );
        }
        ?>
        <?php
        if($carDetails->length){
          echo sprintf("<li>Length : %s cm</li>", number_format($carDetails->length));
        }
        ?>
        <li class="color-container">
          <p class="color" style="background-color: <?php echo ($carDetails->color)  ?>;">
            <p>Color : <?php echo ($carDetails->color)  ?></p>
        </li>
    </li>
      </ul>
      <button class="add-cart-btn" <?php if (!$authDetails["isLoggedIn"] || $carDetails->is_sold == 1 || $carDetails->is_listed == 0) echo 'disabled'; ?>>
        <img class="app-preview__image-origin" srcset="https://img.icons8.com/?size=256&id=59997&format=png 1x, https://img.icons8.com/?size=512&id=59997&format=png 2x" width="25" height="25" alt="Cart icon"> 
        <p> Add to Cart</p>
      </button>
      <!-- disable button if it is not user logged in  -->
      <button class="book-now-btn"  <?php if (!$authDetails["isLoggedIn"] || $carDetails->is_sold == 1 || $carDetails->is_listed == 0) echo 'disabled'; ?>>Book Now</button>
      <?php if(!$authDetails["isLoggedIn"]) echo '<p>You are not authenticated</p>'; ?>
    </div>

    </main>
      <div class="modal">
      </div>
  </body>
</html>

<?php 
  close_connection($conn);
?>