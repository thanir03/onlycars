<?php
    include "../db.php";
    $conn = create_connection();
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
        header("Location:http://localhost/onlycars/seller/auth");
        exit();
    }
    $brands = findBrands($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Sell Cars</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/onlycars/styles/index.css">
    <link rel="stylesheet" href="/onlycars/styles/seller/new.css">
    <script src="/onlycars/script/seller/new.js" defer></script>
</head>

<body>
<?php  include '../components/seller/navbar.php';?>   
    <main>
    <div class="wrapper">
        <div>
            <form class="photo-box" enctype="multipart/form-data">
            <div class="input-bx">
                <h2 class="upload-area-title">Upload File</h2>
                    <input name="images[]" multiple="multiple" type="file" id="upload" accept=".jpeg, .png, .svg, .avif" hidden>
                    <label for="upload" class="uploadLabel">
                        <span><i class="fa fa-cloud-upload"></i></span>
                        <p>Click to upload</p>
                    </label>
                <div id="filewrapper">
                    <h3 class="uploaded"> Uploaded Documents</h3>
                    <div class='img-uploaded'></div>
                    <div class="img-error"></div>
                </div>
            </div>
            <div class="info-box">
                <div id="info-form">
                    <h2 class="form-title">Cars details</h2>
                    <div class="form-fields">
                        <div class="form-group">
                            <for label="Car Brand">Car Brand: </for><br>
                            <select  class="CarBrand" name="brand" required>
                                <?php 
                                foreach ($brands as $brand) {
                                    echo sprintf("<option  class='brand-item' value=%s>%s
                                    </option>",  $brand->brand_id, ucwords($brand->brand_name));
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <for label="Car Model">Car Model: </for><br>
                            <input type="text" class="CarModel" name="model_name" placeholder="Car Model">
                            <div class='car-model-error' id="error-messages"></div>
                        </div>

                        <div class="form-group">
                            <for label="Car Year">Car Year: </for><br>
                            <input type="number" class="CarYear" name="year" placeholder="Car Year">
                            <div class='year-error' id="error-messages"></div>
                        </div>
                    
                        <div class="form-group">
                            <for label="Transmission">Transmission: </for><br>
                            <select type="transmission" id="Transmission" name="transmission" class="Transmission" size='1'>
                                <option value="Automatic" selected>Auto</option>  
                                <option value="Manual">Manual</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <for label="fuel">Fuel Type: </for><br>
                            <select type="fuel" id="Fuel" name="fuel" class="Fuel" size="1">
                                <option value="Petrol">Petrol</option>
                                <option value="Diesel">Diesel</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="Mileage">Mileage: (km) </label><br>
                            <input type="number" class="Mileage" name="mileage" required>
                            <div class='mileage-error' id="error-messages"></div>
                        </div>

                        <div class="form-group">
                            <label for="doors">Doors</label>
                            <input type="number" id="doors" class="Doors" name="doors" min="1" max="7" required>
                            <div class='doors-error' id="error-messages"></div>
                        </div>
        
                            <div class="form-group">
                                <label for="seats">Seats: </label>
                                <input type="number" id="seats" class="Seats" name="seats" min="1" max="10" required>
                            <div class='seat-error' id="error-messages"></div>

                            </div>

                            <div class="form-group">
                                <label for="width">Width: (mm)</label>
                                <input class="Width" type="number" id="width"  name="width" required>
                            <div class='width-error' id="error-messages"></div>
                            </div>

                            <div class="form-group">
                                <label for="height">Height: (mm)</label>
                                <input class="Height" type="number" id="height"  name="height" required>
                            <div class='height-error' id="error-messages"></div>
                            </div>
                            <div class="form-group">
                                <label for="length">Length: (mm) </label>
                                <input type="number" id="length" class="Length" name="length"  required>
                            <div class='length-error' id="error-messages"></div>
                            </div>
                            <div class="form-group">
                                <for label="color">Color: </for>
                                <input type="text" class="Color" name="color" >
                                <div class='color-error' id="error-messages"></div>
                            </div>
                            <div class="form-group price-group">
                                <for label="Price">Ask price: </for>
                                <input type="number" class="Price" name="price" placeholder="Your ask price">
                                <div class='price-error' id="error-messages"></div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="submit-button">Submit information</button>
            </div>
        </form>
        </div>
    </div>
</main>
    <div class="modal"></div>
</body>

</html>

<?php
    close_connection($conn);
?>