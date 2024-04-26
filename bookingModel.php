<?php 
class Booking {
   public string $user_email; 
   public int $car_id; 
   public string $status; 
   public string $booking_id; 

   public function __construct($row) {
      foreach($row as $key => $value) {   
          $this->{$key} = $value;
    }
  }
}