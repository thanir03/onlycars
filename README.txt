Steps to run the project 

Php External dependencies
1. Install composer from https://getcomposer.org/download/
2. Run `composer install` in the project directory (where the composer.json locates) to install the dependencies

To solve Php Curl SSL certificate error (SSL certificate problem: unable to get local issuer certificate) Occurs during uploading images to aws s3 bucket
3. Download this file http://curl.haxx.se/ca/cacert.pem and place it in C:\wamp\bin\php\(currently using php version)\extras\ssl\
4. Open php.ini (configuration file) from the wampserver icon below by pressing php section in the wampserver menu
5. search for ;curl.cainfo
6. Change it to curl.cainfo = (location fo the cacert.pem file)
7. Remove the starting semicolon in ;curl.cainfo
8. Save the file and restart the server


Extra tips:
Open the project into your wampserver folder directory, ie: C:\wamp64\www\OnlyCars

Notes
1. The database is hosted online through aws rds.
2. The db credentials can be found .env file (you dont have to worry, the code will automatically connect to the db using the credentials)
3. The payment is done using stripe api (real payment service)
4. The images uploaded is hosted to aws s3 bucket.
5. The default password is ABCD123
6. The dealers email address always starts with the brand name 
for example 
audi -> audi@gmail.com  password: ABCD123
bmw ->  bmw@gmail.com password : ABCD123

7. To try out the payment feature , 
use 4242 4242 4242 4242 (this is a test card number provided by stripe that will not charge you any money)
use any number as cvc and any month and year in the future for the test card 