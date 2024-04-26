<?php 

class Car {
   public int $car_id; 
   public int $brand_id; 
   public string $brand_name; 
   public string $dealer_email; 
   public string $model_name; 
   public int $year; 
   public int $mileage; 
   public int $price; 
   public string $fuel_type;
   public string $transmission;
   public int $doors; 
   public int $seat; 
   public string $color; 
   public ?int $width; 
   public ?int $height; 
   public ?int $length; 
   public ?int $is_sold; 
   public ?int $is_listed; 

   public string $brand_logo;

   public array $image_urls;
   public function __construct($row) {
      foreach($row as $key => $value) {
        if(!$value){
          $this->{$key} = null;
          continue;
        }
        if($key == 'image_urls'){
          $this->image_urls = json_decode($value);
        }else {
          $this->{$key} = $value;
        }
    }
  }


}