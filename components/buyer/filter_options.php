<section class='filter-container'>

<div class="brand-filter-container">
  <p>Brand Name</p>
  <?php
    foreach($brand_list as $brand){
      echo "<div class='brand-checkbox-container'>";
      echo sprintf("<input type='checkbox' name='brand' class='brand-checkbox' autocomplete='off' data-brand=%s >", $brand->brand_name);
      echo sprintf("<label for=%s>%s</label>", explode(" ", ($brand->brand_name))[0], ucwords($brand->brand_name));          
      echo "</div>";
    }
  ?>
</div>


<div class='transmission-filter-container'>
  <p>Transmission Type</p>
  <div class='transmission-container'>
    <div>
      <input type="radio" name="transmission" class="transmission-radio" autocomplete="off" data-transmission='Manual'>
      <label for="Manual">Manual</label>
    </div>
    <div>
      <input type="radio" name="transmission" class="transmission-radio" autocomplete="off" data-transmission='Automatic'>
      <label for="Automatic">Automatic</label>
    </div>
  </div>
</div>

<div class="color-filter-container"> 
  <p>Color</p>
  <div class='color-container'>
    <?php 
  foreach($colors as $color){
    echo "<div class='color-item-container'>";
    echo sprintf("<input type='checkbox' name='color' id='color-checkbox-%s' class='color-checkbox'>", strtolower($color));
    echo sprintf("<label for='%s'> 
    <div class='color-selected' id='color-selected-%s'>
      <div data-color='%s'  class='color-label' style='background-color:%s;'>
    </div>
    </div>
     </label>", strtolower($color), strtolower($color) ,strtolower($color) ,strtolower($color));
    echo "</div>";
  }
  ?>
  </div>
</div>

  <div class="fuel-filter-container">
    <p>Fuel Type</p>
    <div class="fuel-type-container">
      <div>
        <input type="radio" autocomplete='off'  name="petrol" id="petrol" class="fuel-radio">
        <label for="petrol">Petrol</label>
</div>
<div>
  <input type="radio"  autocomplete='off' name="diesel" id="diesel" class="fuel-radio">
  <label for="diesel">Diesel</label>
</div>
</div>
</div>
</section>