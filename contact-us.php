<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnlyCars Contact-Us</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link rel="stylesheet" href="/onlycars/styles/contact-us.css">
</head>
<body>
    <div id="logo-container" style="text-align: center; margin-top: 30px;">
        <a href="/onlycars/" class="logo" style="text-align: center;">Only<span>Cars</span></a>
    </div>
    
        <section id="section-wrapper">
            <div class="box-wrapper">
                <div class="info-wrap">
                    <h2 class="info-title">Contact Information</h2>
                    <h3 class="info-sub-title">Your feedback will be greatly appreciated</h3>
                    <ul class="info-details">
                        <li>
                            <i class="fas fa-phone-alt"></i>
                            <span>Phone:</span> <a href="tel:+ 6015 2355 9822">+ 6015 2355 9822</a>
                        </li>
                        <li>
                            <i class="fas fa-paper-plane"></i>
                            <span>Email:</span> <a href="mailto:info@OnlyCars.com">info@OnlyCars.com</a>
                        </li>
                        <li>
                            <i class="fas fa-globe"></i>
                            <span>Address:</span> <a href="#">59 1st Floor, Wisma Rampai, Rampai Town Centre</a>
                        </li>
                    </ul>
                    <ul class="social-icons">
                        <li><a href="#"><i class="fab fa-facebook"></i></a></li>
                        <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                    </ul>
                </div>
                <div class="form-wrap">
                    <form action="#" method="POST" id="contactForm">
                        <h2 class="form-title">Share us your experience</h2>
                        <div class="form-fields">
                            <div id="error-messages"></div>
                            <div class="form-group">
                                <input type="text" class="fName" name = "firstName" placeholder="First Name">
                                <div id="error-messages"></div>
                            </div>
                            <div class="form-group">
                                <input type="text" class="lname" name = "lastName" placeholder="Last Name">
                                <div id="error-messages"></div>
                            </div>
                            <div class="form-group">
                                <input type="email" class="email" name = "email" placeholder="Email">
                                <div id="error-messages"></div>
                            </div>
                            <div class="form-group">
                                <input type="number" class="phone" name = "phone" placeholder="Phone">
                                <div id="error-messages"></div>
                            </div>
                            <div class="form-group">
                                <textarea name="message" class = "feedback" name = "message" placeholder="Write your message"></textarea>
                                <div id="error-messages"></div>
                            </div>
                        </div>
                        <input type="submit" value="Send Message" class="submit-button">
                    </form>
                </div>
            </div>
        </section>
        
        <script>
     var form = document.getElementById('contactForm');

form.addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    var fname = document.querySelector('.fName').value.trim();
    var lname = document.querySelector('.lname').value.trim();
    var email = document.querySelector('.email').value.trim();
    var phone = document.querySelector('.phone').value.trim();
    var feedback = document.querySelector('.feedback').value.trim();
    var errorMessages = [];

    // Validation for first name and last name
    if (fname === '' ) {
    errorMessages.push('Please enter your first name');
    document.querySelector('.fName').nextElementSibling.innerHTML = 'Please enter your first name';
    document.querySelector('.fName').nextElementSibling.style.color = 'red';
    console.log('first name empty');
    } else {
    document.querySelector('.fName').nextElementSibling.innerHTML = '';
    }


    if(lname === ''){
        errorMessages.push('Please enter your last name');
        document.querySelector('.lname').nextElementSibling.innerHTML = 'Please enter your last name';
        document.querySelector('.lname').nextElementSibling.style.color = 'red';
    }else{
        document.querySelector('.lname').nextElementSibling.innerHTML = '';
    }

    // Validation for email
    if (email === '') {
        errorMessages.push('Please enter your email');
        document.querySelector('.email').nextElementSibling.innerHTML = 'Please enter your email address';
        document.querySelector('.email').nextElementSibling.style.color = 'red';
    } else if (!isValidEmail(email)) {
        errorMessages.push('Please enter a valid email address');
        document.querySelector('.email').nextElementSibling.innerHTML = 'Please enter a valid email address';
        document.querySelector('.email').nextElementSibling.style.color = 'red';
    } else {
        document.querySelector('.email').nextElementSibling.innerHTML = '';
    }

    // Validation for phone number
    if (phone === '') {
        errorMessages.push('Please enter your phone number');
        document.querySelector('.phone').nextElementSibling.innerHTML = 'Please enter your phone number';
        document.querySelector('.phone').nextElementSibling.style.color = 'red';
    } else {
        document.querySelector('.phone').nextElementSibling.innerHTML = '';
    }

    // Validation for feedback/message
    if (feedback === '') {
        errorMessages.push('Please enter your message');
        document.querySelector('.feedback').nextElementSibling.innerHTML = 'Please enter your message';
        document.querySelector('.feedback').nextElementSibling.style.color = 'red';
    } else {
        document.querySelector('.feedback').nextElementSibling.innerHTML = '';
    }

    // Display error messages if any
    var errorMessageContainer = document.getElementById('error-messages');
    errorMessageContainer.innerHTML = ''; // Clear previous error messages

    if (errorMessages.length === 0) {
        form.submit();
    }
  });

 function isValidEmail(email) {
    var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
    return emailRegex.test(email);
 }
 </script>
        
<?php
    include "db.php";
    $conn = create_connection();   
   if(!$conn){
        die("Connection failed"); 
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Define error array to store validation errors
        $errors = array();
    
        // Validate first name
        $firstName = $_POST['firstName'];
        if (empty($firstName)) {
            $errors['firstName'] = "Please enter your first name";
        }
    
        // Validate last name
        $lastName = $_POST['lastName'];
        if (empty($lastName)) {
            $errors['lastName'] = "Please enter your last name";
        }
    
        // Validate email
        $email = $_POST['email'];
        if (empty($email)) {
            $errors['email'] = "Please enter your email";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Please enter a valid email address";
        }
    
        // Validate phone number
        $phone = $_POST['phone'];
        if (empty($phone)) {
            $errors['phone'] = "Please enter your phone number";
        }
    
        // Validate feedback/message
        $feedback = $_POST['message'];
        if (empty($feedback)) {
            $errors['feedback'] = "Please enter your message";
        }
    
        // If there are no errors, proceed with inserting data into the database
        if (empty($errors)) {
            // Prepare an insert statement
            $stmt = mysqli_prepare($conn, "INSERT INTO CONTACT(firstName, lastName, email, phone, feedback) VALUES (?, ?, ?, ?, ?)");
    
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $firstName, $lastName, $email, $phone, $feedback);
    
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                echo "Records inserted successfully.";
            } else {
                echo "ERROR: Could not execute query: $sql. " . mysqli_error($conn);
            }
    
            // Close statement
            mysqli_stmt_close($stmt);
        } else {
            // Output validation errors
            foreach ($errors as $error) {
                echo $error . "<br>";
            }
        }
    }
    
      // Close connection
      mysqli_close($conn);
    ?>

</body>
</html>