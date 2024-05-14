<?php
include "header_sign.php";
// Include the database connection class
require_once 'database/DBController.php';

// Initialize error variables
$firstNameError = $lastNameError = $emailError = $passwordError = '';
$successMessage = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if required fields are empty
    if (empty($firstName)) {
        $firstNameError = 'First Name is required';
    }

    if (empty($lastName)) {
        $lastNameError = 'Last Name is required';
    }

    if (empty($email)) {
        $emailError = 'Email is required';
    }

    // Check if the email already exists in the database
    $db = new DBController();
    $checkEmailQuery = "SELECT * FROM user WHERE email = ?";
    $checkStmt = $db->con->prepare($checkEmailQuery);
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        $emailError = 'Email already exists. Please try again with a different email.';
    }

    // Check if any error occurred before processing the password
    if (empty($firstNameError) && empty($lastNameError) && empty($emailError)) {
        // Check if the password meets the length requirement
        if (strlen($password) < 8) {
            $passwordError = 'Password should be at least 8 characters long. Please try again';
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert user data into the 'user' table
            $insertQuery = "INSERT INTO user (first_name, last_name, email, password, register_date) VALUES (?, ?, ?, ?, NOW())";

            // Use prepared statement to prevent SQL injection
            $insertStmt = $db->con->prepare($insertQuery);
            $insertStmt->bind_param("ssss", $firstName, $lastName, $email, $hashedPassword);

            // Execute the statement
            if ($insertStmt->execute()) {
                // Set success message
                $successMessage = "User registered successfully!";
            } else {
                $emailError = 'Error: ' . $insertStmt->error;
            }

            // Close the statement
            $insertStmt->close();
        }
    }

    // Close the check statement and result
    $checkStmt->close();
    $checkResult->close();
}

// Close the database connection (automatically done in the DBController destructor)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Store</title>

    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <!-- Owl-carousel CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha256-UhQQ4fxEeABh4JrcmAJ1+16id/1dnlOEVCFOxDef9Lw=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" integrity="sha256-kksNxjDRxd/5+jGurZUJd1sdR2v+ClrCl3svESBaJqw=" crossorigin="anonymous" />

    <!-- font awesome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" integrity="sha256-h20CPZ0QyXlBuAw7A+KluUYx/3pK+c7lYEpqLTlxjYQ=" crossorigin="anonymous" />

    <!-- Custom CSS file -->
    <link rel="stylesheet" href="style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    
    <style>
        .error-message {
            color: #ff0000;
            font-size: 14px;
            margin-top: 5px;
            text-shadow: 1px 1px 2px #ff0000;
        }

        .success-message {
            color: #0000ff;
            font-size: 14px;
            margin-top: 5px;
            text-shadow: 1px 1px 2px #ff0000;
        }
    </style>
</head>
<body style = 'background-color: black;'>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-white text-center" style = 'background-color : rgb(31,31,31)'>
                    <h4>Signup</h4>
                </div>
                <div class="card-body">
                    <?php
                    // Display success message if set
                    if (!empty($successMessage)) {
                        echo '<div class="success-message">' . $successMessage . '</div>';
                    }
                    ?>
                    <form method="post" action="signup.php">
                        <div class="form-group">
                            <label for="inputFirstName">First Name</label>
                            <input type="text" class="form-control" id="inputFirstName" name="first_name" placeholder="Enter your first name" required>
                            <!-- Display first name error message -->
                            <div class="error-message"><?php echo $firstNameError; ?></div>
                        </div>
                        <div class="form-group">
                            <label for="inputLastName">Last Name</label>
                            <input type="text" class="form-control" id="inputLastName" name="last_name" placeholder="Enter your last name" required>
                            <!-- Display last name error message -->
                            <div class="error-message"><?php echo $lastNameError; ?></div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail">Email address</label>
                            <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Enter email" required>
                            <!-- Display email error message -->
                            <div class="error-message"><?php echo $emailError; ?></div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword">Password</label>
                            <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Password">
                            <!-- Display password error message -->
                            <div class="error-message"><?php echo $passwordError; ?></div>
                        </div>
                        <button type="submit" class="btn text-white btn-block" style = 'background-color: black; border-radius:10px;'>Signup</button>
                    </form>

                    <div class="form-group mt-3">
                        <p class="text-center">Already have an account? <a href="login.php">Log In</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies (jQuery, Popper.js) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js" integrity="sha384-Xr2lDLTKO7eZLlq1S3tdECStpUB1uZZTfE5nEECjgCiY4L/DXMU8LONVZCIgFkT3" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+WyTy7a5ezFqFckfv2ZfNvEp81dJdaa4tR" crossorigin="anonymous"></script>

</body>
</html>