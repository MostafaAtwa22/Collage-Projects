<?php
// Include the database connection class
include "header_sign.php";
require_once 'database/DBController.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $email = $_POST['email'];
    $password = $_POST['password'];
    $userType = $_POST['userType']; // Added this line

    // Create a new instance of the DBController class
    $db = new DBController();

    // Check if the email exists in the customer or admin table
    $checkEmailQuery = "SELECT * FROM `user` WHERE `email` = ?";
    $checkStmt = $db->con->prepare($checkEmailQuery);
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    // Check admin table if email not found in the customer table
    if ($checkResult->num_rows === 0) {
        $checkAdminQuery = "SELECT * FROM `admin` WHERE `email` = ?";
        $checkStmt = $db->con->prepare($checkAdminQuery);
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
    }

    if ($checkResult->num_rows > 0) {
        // Email exists, check password
        $userRow = $checkResult->fetch_assoc();
        $storedPassword = $userRow['password'];

        if ($userType === 'admin') {
            // For admin login, check password without hashing
            if ($password === $storedPassword) {
                // Password is correct, user is authenticated

                // Start a session
                //session_start();

                // Set session variables
                $_SESSION['admin_id'] = $userRow['admin_id'];
                $_SESSION['admin_name'] = $userRow['first_name'];
                header('Location: dashboard.php');
                exit();
            } else {
                // Password is incorrect, display error message
                echo "Incorrect password. Please try again.";
            }
        } else {
            // For customer login, check hashed password
            if (password_verify($password, $storedPassword)) {
                // Password is correct, user is authenticated

                // Start a session
                // session_start();

                // Set session variables
                $_SESSION['user_id'] = $userRow['user_id'];
                $_SESSION['user_name'] = $userRow['first_name'];
                header('Location: index.php');
                exit();
            } else {
                // Password is incorrect, display error message
                echo "Incorrect password. Please try again.";
            }
        }
    } else {
        // Email does not exist, display error message
        echo "Email not found. Please try again or register.";
    }

    // Close the check statement and result
    $checkStmt->close();
    $checkResult->close();
}
?>
<!-- The rest of your HTML code -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Store</title>

    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <!-- Owl-carousel CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"
        integrity="sha256-UhQQ4fxEeABh4JrcmAJ1+16id/1dnlOEVCFOxDef9Lw=" crossorigin="anonymous" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"
        integrity="sha256-kksNxjDRxd/5+jGurZUJd1sdR2v+ClrCl3svESBaJqw=" crossorigin="anonymous" />

    <!-- font awesome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css"
        integrity="sha256-h20CPZ0QyXlBuAw7A+KluUYx/3pK+c7lYEpqLTlxjYQ=" crossorigin="anonymous" />

    <!-- Custom CSS file -->
    <link rel="stylesheet" href="style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>

<body style="background-color: black;">
    
    <div class="container mt-5">
        <div class="row justify-content-center align-items-center">
            <!-- Logo Column -->
            <div class="col-md-6" style="max-width: 600px;">
                <div class="logo-container"
                    style="background-image: url('./assets/products/50.png'); height: 550px; background-size: cover; background-position: center; margin-right: -30px;">
                </div>
            </div>
            <!-- Form Column -->
            <div class="col-md-6">
                <div class="card" style="max-width: 400px; height: 550px; border-radius: 0;">
                    <div class="card-header text-center"
                        style="background-color: rgb(31, 31, 31); color: white; font-weight: bold;">
                        <h4>Login</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" style="margin-bottom: 30px;">
                            <div class="form-group">
                                <label for="userType">Login As:</label>
                                <select class="form-control" id="userType" name="userType" required>
                                    <option value="customer">Customer</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="form-group" style="margin-top: 30px;">
                                <label for="inputEmail">Email address</label>
                                <input type="email" class="form-control" id="inputEmail" name="email"
                                    placeholder="Enter email" required>
                            </div>
                            <div class="form-group" style="margin-top: 30px;">
                                <label for="inputPassword">Password</label>
                                <input type="password" class="form-control" id="inputPassword" name="password"
                                    placeholder="Password" required>
                            </div>
                            <?php if (!empty($errorMessage)): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $errorMessage; ?>
                                </div>
                            <?php endif; ?>
                            <button type="submit" class="btn text-white btn-block"
                                style="margin-top: 30px; background-color:black;border-radius: 10px;">Login</button>
                        </form>
                        <p class="mt-3 text-center">Don't have an account? <a style="text-decoration: none;"
                                href="signup.php">Sign up now!</a></p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Bootstrap JS and dependencies (jQuery, Popper.js) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"
        integrity="sha384-Xr2lDLTKO7eZLlq1S3tdECStpUB1uZZTfE5nEECjgCiY4L/DXMU8LONVZCIgFkT3"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+WyTy7a5ezFqFckfv2ZfNvEp81dJdaa4tR"
        crossorigin="anonymous"></script>

</body>

</html>