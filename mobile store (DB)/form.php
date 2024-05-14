<?php
// Include necessary files and instantiate objects
include ('header_cart.php');
include_once('database/DBController.php');
include_once('database/Product.php');

$db = new DBController();
$product = new Product($db);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process the form data

    // Assuming you have the necessary form fields in $_POST, adjust as needed
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Initialize variables to store total quantity and subtotal
    $totalQuantity = 0;
    $subtotal = 0;

    foreach ($product->getData('cart') as $item) {
        // Retrieve product details for the current item
        $productDetails = $product->getProduct($item['item_id']);

        // Check if product details are available
        if ($productDetails) {
            // Get quantity from the cart
            $quantity = $item['quantity'];

            // Get price from the product details
            $price = $productDetails[0]['item_price']; // Assuming 'item_price' is the correct key

            // Calculate subtotal for the current item
            $itemSubtotal = $quantity * $price;

            // Add quantity to the total quantity
            $totalQuantity += $quantity;

            // Add item subtotal to the overall subtotal
            $subtotal += $itemSubtotal;
        }
    }

    // Get the user ID from the session
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Insert data into the orders table, including subtotal and total quantity
    $stmt = $db->con->prepare
    ("INSERT INTO orders (user_id, name, email, phone, address, subtotal, num_of_items) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssdi", $user_id, $name, $email, $phone, $address, $subtotal, $totalQuantity);

    // Execute the prepared statement
    $stmt->execute();

    // Delete all items from the user's cart
    $deleteCartQuery = "DELETE FROM cart WHERE user_id = ?";
    $deleteCartStmt = $db->con->prepare($deleteCartQuery);
    $deleteCartStmt->bind_param("i", $user_id);
    $deleteCartStmt->execute();
    $deleteCartStmt->close();

    // Redirect to a confirmation page
    header("Location: trans.php"); // Adjust the path as needed
    exit();
}
?>
<!-- Rest of your HTML code -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Order Form</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 500px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        button {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>
<body style = 'background-color: rgb(31,31,31)'>

<div class="container">
    <h2 class="text-center mb-4">Order Form</h2>
    <form method="post">
        <!-- Name -->
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
        </div>
        
        <!-- Phone Number -->
        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter your phone number" required>
        </div>

        <!-- Shipping Address -->
        <div class="form-group">
            <label for="address">Shipping Address</label>
            <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter your shipping address" required></textarea>
        </div>
        
        <?php
// Initialize variables to store total quantity and subtotal
$totalQuantity = 0;
$subtotal = 0;

foreach ($product->getData('cart') as $item) {
    // Retrieve product details for the current item
    $productDetails = $product->getProduct($item['item_id']);

    // Check if product details are available
    if ($productDetails) {
        // Get quantity from the cart
        $quantity = $item['quantity'];

        // Get price from the product details
        $price = $productDetails[0]['item_price']; // Assuming 'item_price' is the correct key

        // Calculate subtotal for the current item
        $itemSubtotal = $quantity * $price;

        // Add quantity to the total quantity
        $totalQuantity += $quantity;

        // Add item subtotal to the overall subtotal
        $subtotal += $itemSubtotal;
    }
}
?>

<!-- Display Subtotal -->
<div class="form-group">
    <h5 class="font-baloo font-size-20">Subtotal (<?php echo $totalQuantity; ?> item):&nbsp;
        <span class="text-danger">
           <span class = 'text-danger'> $</span><span class="text-danger" id="deal-price"><?php echo $subtotal; ?></span>
        </span>
    </h5>
</div>


        <!-- Submit Button -->
        <button type="submit" class="btn text-white btn-block" style = 'background-color: black;border-radius:10px;'>Place Order</button>
    </form>
</div>

<!-- Bootstrap JS and Popper.js (for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>