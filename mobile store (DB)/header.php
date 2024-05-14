<?php
// Include the necessary files
require_once 'database/DBController.php';
require_once 'database/Product.php';
require_once 'database/Cart.php';

// Create instances of the required classes
$db = new DBController();
$product = new Product($db);
$Cart = new Cart($db);

// Check if the user is logged in
session_start();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Use getData2 method to get cart items based on the user ID
$cartItems = $product->getData2('cart', $user_id);
?>
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

    <?php
    // require functions.php file
    require('functions.php');
    ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        .navbar {
            background-color: rgba(66, 66, 69, 0.7);
        }
    </style>

</head>

<body style='background-color: rgb(31,31,31)'>

    <!-- start #header -->
    <header id="header">
        <div class="strip d-flex justify-content-between px-4 py-1" style="background-color: black;">
            <p class="font-rale font-size-16 text-white m-0" style="padding: 5px; text-align: center; font-size: 16px;">
                This is Our mobile shop
            </p>
            <div class="font-rale font-size-14" style="padding: 5px; text-align: center; font-size: 16px; margin-left: 10px; 
    color: black;">
                <?php
                if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])) {
                    $user_id = $_SESSION['user_id'];
                    $user_name = $_SESSION['user_name'];
                    echo "<span class = 'text-white'>Hello, $user_name! </span> <span class = 'text-white'>|</span> <a href='orders.php' class = 'text-white'>My Orders</a> <span class = 'text-white'>|</span> 
    <a href='wish.php' class = 'text-white'>WishList</a> <span class = 'text-white'>|</span> <a href='logout.php' class = 'text-white'>Logout</a>";
                } elseif (isset($_SESSION['admin_id']) && isset($_SESSION['admin_name'])) {
                    // Check if the user is an admin
                    $admin_id = $_SESSION['admin_id'];
                    $admin_name = $_SESSION['admin_name'];
                    echo "<span class = 'text-white'>Hello, Admin $admin_name!</span> <span class = 'text-white'>|</span> <a href='dashboard.php' class = 'text-white'>Dashboard</a> <span class = 'text-white'>|</span>  <a href='signupadmin.php' class = 'text-white'>Add_new_admin</a> <span class = 'text-white'>|</span>  <a href='logout.php' class = 'text-white'>Logout</a>  ";
                } else {
                    // If not logged in, show login and signup buttons
                    echo '<a href="login.php" class="px-3 border-right border-left text-white" style="font-size: 16px;">Login</a>
    <a href="signup.php" class="px-3 border-right text-white" style="font-size: 16px;">Sign Up</a>';
                }
                ?>
                <!-- Add the rest of your header content here -->
            </div>
        </div>



        <!-- Primary Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: rgb(240,240,240)">
            <a class=" navbar-brand text-dark" href="index.php">Mobile Store</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav m-auto font-rubik">
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="#top-sale">Top Sale</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="#special-price">Special Price</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="#new-phones">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="#blogs">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="#footer">Contact Us</a>
                    </li>
                </ul>
                <form action="#" class="font-size-14 font-rale">
                    <a href="cart.php" class="py-2 rounded-pill " style="background-color: gray;">
                        <span class="font-size-16 px-2 text-white"><i class="fas fa-shopping-cart"></i></span>
                        <span class="px-3 py-2 rounded-pill text-dark bg-light">
                            <?php echo count($cartItems); ?>
                        </span>
                    </a>
                </form>
            </div>
        </nav>
        <!-- !Primary Navigation -->

    </header>
    <!-- !start #header -->

    <!-- start #main-site -->
    <main id="main-site">