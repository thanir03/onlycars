<?php
include "../db.php";
require_once '../vendor/autoload.php';
use Dotenv\Dotenv;
$conn = create_connection();
$dotenv = Dotenv::createImmutable(__DIR__ . "\\..");
$dotenv->load();

$endpoint_secret = $_ENV["STRIPE_WEBHOOK_SECRET"];
$STRIPE_PUB_KEY = $_ENV["STRIPE_PUB_KEY"];
$STRIPE_SECRET_KEY = $_ENV["STRIPE_SECRET_KEY"];
\Stripe\Stripe::setApiKey($STRIPE_SECRET_KEY);

$payload = file_get_contents('php://input');
$event = null;

try {
  $event = \Stripe\Event::constructFrom(
    json_decode($payload, true)
  );
} catch(\UnexpectedValueException $e) { 
  // Invalid payload
  echo 'Webhook error while parsing basic request.';
  http_response_code(400);
  exit();
}
if ($endpoint_secret) {
  // Only verify the event if there is an endpoint secret defined
  // Otherwise use the basic decoded event
  $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
  try {
    $event = \Stripe\Webhook::constructEvent(
      $payload, $sig_header, $endpoint_secret
    );
  } catch(\Stripe\Exception\SignatureVerificationException $e) {
    // Invalid signature
    echo 'Webhook error while validating signature.';
    http_response_code(400);
    exit();
  }
}

  
// Handle the event
switch ($event->type) {
  case 'checkout.session.completed':
    $paymentIntent = $event->data->object;
    $customer = \Stripe\Customer::retrieve($paymentIntent->customer);
    $bookingId = $customer->metadata["booking_id"];
    successPayment($conn, $bookingId);
    $carId = getCarByBookingId($conn, $bookingId);
    updateCarAsSold( $conn,$carId );
    break; 
  default : 
    break;
}
close_connection($conn);
http_response_code(200);