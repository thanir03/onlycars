-- tables for onlycars db
CREATE TABLE USER (
    user_email VARCHAR(100) PRIMARY KEY  NOT NULL,
    name VARCHAR(255),
    password VARCHAR(255)
);

CREATE TABLE DEALER(
  email VARCHAR(100) PRIMARY KEY  NOT NULL,
  phone_number VARCHAR(15),
  password VARCHAR(255) NOT NULL
);

CREATE TABLE CAR_BRAND(
    brand_id  SERIAL AUTO_INCREMENT PRIMARY KEY  NOT NULL,
    name VARCHAR(100),
    brand_logo VARCHAR(2048)w
);

CREATE TABLE CAR (
    car_id SERIAL AUTO_INCREMENT PRIMARY KEY NOT NULL ,
    brand_id BIGINT UNSIGNED NOT NULL,
    dealer_email VARCHAR(100) NOT NULL,
    model_name VARCHAR(100),
    year YEAR,
    mileage INT,
    price INT,
    fuel_type ENUM('Diesel', 'Petrol'),
    transmission ENUM('Manual', 'Automatic'),
    seat INT,
    doors INT ,
    width DOUBLE,
    height DOUBLE,
    length DOUBLE,
    color VARCHAR(10),
    is_listed boolean DEFAULT TRUE,
    is_sold boolean DEFAULT  FALSE,
    FOREIGN KEY  (brand_id) REFERENCES CAR_BRAND(brand_id),
    FOREIGN KEY  (dealer_email) REFERENCES DEALER(email)
);

CREATE TABLE CAR_IMAGE(
    car_image_id SERIAL AUTO_INCREMENT PRIMARY KEY  NOT NULL,
    car_id BIGINT UNSIGNED NOT NULL,
    image_url VARCHAR(2048),
    FOREIGN KEY  (car_id) REFERENCES  CAR(car_id)
);

CREATE TABLE BOOKING (
    booking_id SERIAL AUTO_INCREMENT PRIMARY KEY  NOT NULL,
    user_email VARCHAR(100) NOT NULL,
    car_id BIGINT UNSIGNED NOT NULL,
    -- ONLY PENDING AND COMPLETED ARE USED
    status ENUM('PENDING', 'PROCESSING', 'BOOKED', 'COMPLETED', 'CANCELLED'),
    FOREIGN KEY  (user_email) REFERENCES  USER(user_email),
    FOREIGN KEY  (car_id) REFERENCES  CAR(car_id)
);

CREATE TABLE CART(
    cart_id SERIAL AUTO_INCREMENT PRIMARY KEY  NOT NULL,
    user_email VARCHAR(100) NOT NULL,
    car_id BIGINT UNSIGNED NOT NULL,
    FOREIGN KEY  (user_email) REFERENCES  USER(user_email),
    FOREIGN KEY  (car_id) REFERENCES  CAR(car_id)
);

CREATE TABLE CONTACT (
  contact_id SERIAL AUTO_INCREMENT NOT NULL PRIMARY KEY,
  firstname VARCHAR(100),
  lastname VARCHAR(100),
  email VARCHAR(100),
  phone VARCHAR(100),
  feedback  VARCHAR(255)
);

S