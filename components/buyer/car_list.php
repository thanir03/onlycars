<?php

    if(count($car_list) == 0){
      echo '<p> No car can be found </p>';
    }
      for($i=0; $i<count($car_list); $i++){
        $car = $car_list[$i];
        // echo "<div class='car-list'>";
          echo "<div class='car-container'>";
            echo "<div class='car-details'>";
                echo "<div class='car-brand-details'>";
                  echo sprintf("<p class='car-brand'>%s</p>", strtoupper($car->brand_name));
                  echo sprintf("<img src=%s width='40px'   alt=%s>", $car->brand_logo, $car->brand_name);
                echo  "</div>";
                echo sprintf("<p class='car-price'>RM %s</p>",$car->price);
                echo "</div>";
                echo "<div class='car-minor-container'>";
                echo "<div class='car-minor-details'>";
                echo  sprintf("<p>%s</p>", $car->model_name);
                echo sprintf("<p>%s km</p>",$car->mileage);
                echo "</div>";
                echo "<div class='car-minor-details'>";
                echo  sprintf("<p>%s Doors</p>", $car->doors);
                echo sprintf("<p>%s Seat</p>",$car->seat);
                echo "</div>";
                echo "</div>";
                echo "<div class='car-images-container'>";
                echo sprintf('<svg xmlns="http://www.w3.org/2000/svg" class="left-arrow-icon" data-index=%s  xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" height="30px" width="30px" version="1.1" id="Layer_1" viewBox="0 0 330 330" xml:space="preserve">
                <path id="XMLID_6_" d="M165,0C74.019,0,0,74.019,0,165s74.019,165,165,165s165-74.019,165-165S255.981,0,165,0z M205.606,234.394  c5.858,5.857,5.858,15.355,0,21.213C202.678,258.535,198.839,260,195,260s-7.678-1.464-10.606-4.394l-80-79.998  c-2.813-2.813-4.394-6.628-4.394-10.606c0-3.978,1.58-7.794,4.394-10.607l80-80.002c5.857-5.858,15.355-5.858,21.213,0  c5.858,5.857,5.858,15.355,0,21.213l-69.393,69.396L205.606,234.394z"/></svg>', $i);
                echo sprintf('<svg fill="#000000" height="30px" width="30px" class="right-arrow-icon" data-index=%s  version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 330 330" xml:space="preserve">
                <path id="XMLID_2_" d="M165,0C74.019,0,0,74.019,0,165s74.019,165,165,165s165-74.019,165-165S255.981,0,165,0z M225.606,175.605  l-80,80.002C142.678,258.535,138.839,260,135,260s-7.678-1.464-10.606-4.394c-5.858-5.857-5.858-15.355,0-21.213l69.393-69.396  l-69.393-69.392c-5.858-5.857-5.858-15.355,0-21.213c5.857-5.858,15.355-5.858,21.213,0l80,79.998  c2.814,2.813,4.394,6.628,4.394,10.606C230,168.976,228.42,172.792,225.606,175.605z"/>
                </svg>', $i);
                for($j=0; $j<count($car->image_urls); $j++){
                  if($j == 0){
                    echo sprintf("<img loading='lazy' width='550px' height='420px' class='car-image car-image-active car-image-%s'src=%s alt=%s id='image-%s-%s' data-image-index=%s>", $i, $car->image_urls[$j], $car->model_name, $i,$j, $j);
                  }else {
                    echo sprintf("<img loading='lazy' width='550px' height='420px' class='car-image car-image-%s'src=%s alt=%s id='image-%s-%s' data-image-index=%s>",$i, $car->image_urls[$j], $car->model_name, $i,$j, $j);
                  }
                }
                echo "</div>";
                echo sprintf("<a href='%s' class='view-car-btn'>View Details</a>", '/onlycars/buyer/id.php?id=' .  $car->car_id );
              echo "</div>";
              // echo "</div>";
      }
        
  ?>