<?php
include "header_admin.php";
// Include the database connection class or your connection logic
require_once 'database/DBController.php';

// Check if the form is submitted for deleting a product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteProduct']) && isset($_POST['productIdToDelete'])) {
    // Process form data and delete the product from the database
    $productIdToDelete = $_POST['productIdToDelete'];

    // Add your code here to delete from the 'product' table
    $db = new DBController();
    $deleteProductQuery = "DELETE FROM `product` WHERE `item_id` = ?";
    $deleteProductStmt = $db->con->prepare($deleteProductQuery);
    $deleteProductStmt->bind_param("i", $productIdToDelete);
    $deleteProductStmt->execute();
    $deleteProductStmt->close();
}
// Handle the form submission to delete an order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteOrder']) && isset($_POST['orderIdToDelete'])) {
    $orderIdToDelete = $_POST['orderIdToDelete'];

    // Add your code here to delete the order from the 'orders' table
    $deleteOrderQuery = "DELETE FROM `orders` WHERE `order_id` = ?";
    $deleteOrderStmt = $db->con->prepare($deleteOrderQuery);
    $deleteOrderStmt->bind_param("i", $orderIdToDelete);
    $deleteOrderStmt->execute();
    $deleteOrderStmt->close();

    // Redirect after successful form submission
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();
}

// Handle the form submission to update quantity
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateQuantity'])) {
    $itemIdToUpdateQuantity = $_POST['itemIdToUpdateQuantity'];
    $itemQuantityToUpdate = $_POST['itemQuantity'];

    // Add your code here to update quantity in the 'product' table
    $updateQuantityQuery = "UPDATE `product` SET `item_quantity` = ? WHERE `item_id` = ?";
    $updateQuantityStmt = $db->con->prepare($updateQuantityQuery);
    $updateQuantityStmt->bind_param("ii", $itemQuantityToUpdate, $itemIdToUpdateQuantity);
    $updateQuantityStmt->execute();
    $updateQuantityStmt->close();

    // Redirect after successful form submission
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();
}

// Handle the form submission to update discount
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateDiscount'])) {
    $itemIdToUpdateDiscount = $_POST['itemIdToUpdateDiscount'];
    $itemDiscountToUpdate = $_POST['itemDiscount'];

    // Add your code here to update discount in the 'product' table
    $updateDiscountQuery = "UPDATE `product` SET `item_dis` = ? WHERE `item_id` = ?";
    $updateDiscountStmt = $db->con->prepare($updateDiscountQuery);
    $updateDiscountStmt->bind_param("di", $itemDiscountToUpdate, $itemIdToUpdateDiscount);
    $updateDiscountStmt->execute();
    $updateDiscountStmt->close();

    // Redirect after successful form submission
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();
}



// Check if the form is submitted for adding a product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addProduct'])) {
    // Process the form for adding a product

    // Check if form fields are set and not empty
    if (isset($_POST['itemBrand']) && isset($_POST['productName']) && isset($_POST['productPrice']) && isset($_POST['productQuantity']) && isset($_FILES['productImage'])) {
        // Get form data
        $itemBrand = $_POST['itemBrand'];
        $productName = $_POST['productName'];
        $productPrice = $_POST['productPrice'];
        $productQuantity = $_POST['productQuantity'];

        // File upload logic
        $targetDirectory = "uploads/";
        $originalFileName = basename($_FILES["productImage"]["name"]);
        $targetFile = $targetDirectory . time() . '_' . $originalFileName;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if the image file is a real image or fake image
        $check = getimagesize($_FILES["productImage"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["productImage"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            // If everything is ok, try to upload the file

            // Create a new instance of the DBController class or use your connection logic
            $db = new DBController();

            // Create the target directory if it doesn't exist
            if (!file_exists($targetDirectory) && !is_dir($targetDirectory)) {
                mkdir($targetDirectory, 0755, true);
            }

            // Add your code here to insert into the 'product' table
            $insertProductQuery = "INSERT INTO `product` (`item_brand`, `item_name`, `item_price`, `item_quantity`, `item_image`, `item_register`) VALUES (?, ?, ?, ?, ?, NOW())";
            $insertProductStmt = $db->con->prepare($insertProductQuery);
            $insertProductStmt->bind_param("ssdis", $itemBrand, $productName, $productPrice, $productQuantity, $targetFile);
            $insertProductStmt->execute();
            $insertProductStmt->close();

            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $targetFile)) {
                // Redirect after successful form submission
                header('Location: ' . $_SERVER['REQUEST_URI']);
                exit();
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        echo "Form fields are not set or empty for adding a product.";
    }
}

// Handle the form submission to delete a contact message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteMessage']) && isset($_POST['messageIdToDelete'])) {
    $messageIdToDelete = $_POST['messageIdToDelete'];

    // Add your code here to delete the message from the 'contact_messages' table
    $deleteMessageQuery = "DELETE FROM `contact_messages` WHERE `id` = ?";
    $deleteMessageStmt = $db->con->prepare($deleteMessageQuery);
    $deleteMessageStmt->bind_param("i", $messageIdToDelete);
    $deleteMessageStmt->execute();
    $deleteMessageStmt->close();

    // Redirect after successful form submission
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();
}


// Handle the form submission to update the status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['statusToUpdate']) && isset($_POST['orderIdToUpdate'])) {
        $orderIdToUpdate = $_POST['orderIdToUpdate'];
        $statusToUpdate = $_POST['statusToUpdate'];

        // Add your code here to update the status in the 'orders' table
        $updateStatusQuery = "UPDATE `orders` SET `status` = ? WHERE `order_id` = ?";
        $updateStatusStmt = $db->con->prepare($updateStatusQuery);
        $updateStatusStmt->bind_param("si", $statusToUpdate, $orderIdToUpdate);
        $updateStatusStmt->execute();
        $updateStatusStmt->close();


        exit();
    }
}

// Retrieve all products from the database
$productsQuery = "SELECT * FROM `product`";
$productsResult = $db->con->query($productsQuery);

// Retrieve the number of users
$usersCountQuery = "SELECT COUNT(*) AS userCount FROM `user`";
$usersCountResult = $db->con->query($usersCountQuery);
$userCount = $usersCountResult->fetch_assoc()['userCount'];

// Retrieve the number of admins
$adminsCountQuery = "SELECT COUNT(*) AS adminCount FROM `admin`";
$adminsCountResult = $db->con->query($adminsCountQuery);
$adminCount = $adminsCountResult->fetch_assoc()['adminCount'];

// Retrieve the number of orders
$ordersCountQuery = "SELECT COUNT(*) AS orderCount FROM `orders`";
$ordersCountResult = $db->con->query($ordersCountQuery);
$orderCount = $ordersCountResult->fetch_assoc()['orderCount'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Head section with styles and meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+WyTy7a5ezFqFckfv2ZfNvEp81dJdaa4tR" crossorigin="anonymous">
    <style>
        /* Add your custom styles here */

        .custom-margin {
            margin: 40px;
        }

        .status-ordered {
            color: blue;
        }

        .status-shipped {
            color: blueviolet;
        }

        .status-delivered {
            color: green;
        }

        .dashboard-table {
            margin-top: 20px;
        }

        .forms-container {
            display: flex;
            justify-content: space-between;
        }

        .dashboard-form {
            width: 48%;
        }

        body {
            background-color: rgb(31, 31, 31) !important;

        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8">
                <!-- Display All Products -->
                <h3 id="dep" class='text-white'>All Products</h3>
                <table class="table" style="width: 150%; color : black;">
                    <thead>
                        <tr>
                            <th scope="col" class='text-white'>ID</th>
                            <th scope="col" class='text-white'>Brand</th>
                            <th scope="col" class='text-white'>Name</th>
                            <th scope="col" class='text-white'>Price</th>
                            <th scope="col" class='text-white'>Discount</th> <!-- New column for discount -->
                            <th scope="col" class='text-white'>Quantity</th>
                            <th scope="col" class='text-white'>Image</th>
                            <th scope="col" class='text-white'>Register Date</th>
                            <!-- Additional column for quantity -->
                            <!-- Additional column for actions -->
                            <th scope="col" class='text-white'>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($product = $productsResult->fetch_assoc()): ?>
                            <tr>
                                <th scope="row" class='text-white'>
                                    <?php echo $product['item_id']; ?>
                                </th>
                                <td class='text-white'>
                                    <?php echo $product['item_brand']; ?>
                                </td>
                                <td class='text-white'>
                                    <?php echo $product['item_name']; ?>
                                </td>
                                <td class='text-white'>
                                    <?php echo $product['item_price']; ?>
                                </td>
                                <td class='text-white'>
                                    <?php echo $product['item_dis']; ?>%
                                </td> <!-- Display discount -->
                                <td class='text-white'>
                                    <?php echo $product['item_quantity']; ?>
                                </td>
                                <td class='text-white'><img src="<?php echo $product['item_image']; ?>" alt="Product Image"
                                        class="img-thumbnail" style="max-width: 100px;"></td>
                                <td class='text-white'>
                                    <?php echo $product['item_register']; ?>
                                </td>
                                <!-- Add a delete button for each product -->
                                <td>
                                    <form method="post" action="">
                                        <input type="hidden" name="productIdToDelete"
                                            value="<?php echo $product['item_id']; ?>">
                                        <button type="submit" class="btn btn-danger" name="deleteProduct">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <div class="forms-container" style='width: 150%; margin-top: 30px;'>

                    <!-- Form for updating quantity -->
                    <form method="post" action="" class="dashboard-form">
                        <h3 class='text-white'>Update Quantity</h3>
                        <div class="form-group">
                            <label for="itemIdToUpdateQuantity" class='text-white'>Product ID:</label>
                            <input type="text" class="form-control" id="itemIdToUpdateQuantity"
                                name="itemIdToUpdateQuantity" required>
                        </div>
                        <div class="form-group">
                            <label for="itemQuantity" class='text-white'>Quantity:</label>
                            <input type="number" class="form-control" id="itemQuantity" name="itemQuantity" required>
                        </div>
                        <button type="submit" class="btn text-dark" name="updateQuantity"
                            style='background-color: white;border-radius: 10px;'>Update
                            Quantity</button>
                    </form>

                    <!-- Form for updating discount -->
                    <form method="post" action="" class="dashboard-form">
                        <h3 class='text-white'>Update Discount</h3>
                        <div class="form-group">
                            <label for="itemIdToUpdateDiscount" class='text-white' class='text-white'>Product
                                ID:</label>
                            <input type="text" class="form-control" id="itemIdToUpdateDiscount"
                                name="itemIdToUpdateDiscount" required>
                        </div>
                        <div class="form-group">
                            <label for="itemDiscount" class='text-white'>Discount:</label>
                            <input type="number" class="form-control" id="itemDiscount" name="itemDiscount" required>
                        </div>
                        <button type="submit" class="btn text-dark" name="updateDiscount" class='text-white'
                            style='background-color: white;border-radius: 10px;'>Update
                            Discount</button>
                    </form>

                </div>
            </div>


            <div class="col-md-4 custom-margin">
                <!-- Form for adding a product -->
                <form method="post" action="" enctype="multipart/form-data" style="margin-bottom: 40px;">
                    <h3 id="adp" class='text-white'>Add Product</h3>
                    <div class="form-group">
                        <label for="itemBrand" class='text-white'>Brand:</label>
                        <select class="form-control" id="itemBrand" name="itemBrand" required>
                            <option value="Apple" class='text-white'>Apple</option>
                            <option value="Samsung" class='text-white'>Samsung</option>
                            <option value="Redmi" class='text-white'>Redmi</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="productName" class='text-white'>Product Name:</label>
                        <input type="text" class="form-control" id="productName" name="productName" required>
                    </div>
                    <div class="form-group">
                        <label for="productPrice" class='text-white'>Price:</label>
                        <input type="number" step="0.01" class="form-control" id="productPrice" name="productPrice"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="productQuantity" class='text-white'>Quantity:</label>
                        <input type="number" class="form-control" id="productQuantity" name="productQuantity" value="1"
                            min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="productImage" class='text-white'>Image:</label>
                        <input type="file" class="form-control-file" id="productImage" name="productImage" required
                            class='text-white'>
                    </div>
                    <button type="submit" class="btn text-dark" name="addProduct"
                        style='background-color: white;border-radius: 10px; border:5px solid black'>Add
                        Product</button>
                </form>



                <!-- Additional Info -->
                <h3 id="adi" class='text-white'>Additional Information</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col" class='text-white'>Category</th>
                            <th scope="col" class='text-white'>Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class='text-white'>Users</td>
                            <td class='text-white'>
                                <?php echo $userCount; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class='text-white'>Admins</td>
                            <td class='text-white'>
                                <?php echo $adminCount; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class='text-white'>Orders</td>
                            <td class='text-white'>
                                <?php echo $orderCount; ?>
                            </td>
                        </tr>

                    </tbody>
                </table>

                <!-- Contact Messages Table -->
                <h3 class='text-white' id="qqqq">Contact Messages</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col" class='text-white'>ID</th>
                            <th scope="col" class='text-white'>Name</th>
                            <th scope="col" class='text-white'>Email</th>
                            <th scope="col" class='text-white'>Message</th>
                            <th scope="col" class='text-white'>Created At</th>
                            <th scope="col" class='text-white'>Actions</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch and display contact messages from the database
                        $contactMessagesQuery = "SELECT * FROM `contact_messages`";
                        $contactMessagesResult = $db->con->query($contactMessagesQuery);

                        while ($message = $contactMessagesResult->fetch_assoc()):
                            ?>
                            <tr>
                                <th scope="row" class='text-white'>
                                    <?php echo $message['id']; ?>
                                </th>
                                <td class='text-white'>
                                    <?php echo $message['name']; ?>
                                </td>
                                <td class='text-white'>
                                    <?php echo $message['email']; ?>
                                </td>
                                <td class='text-white'>
                                    <?php echo $message['message']; ?>
                                </td>
                                <td class='text-white'>
                                    <?php echo $message['created_at']; ?>
                                </td>
                                <td>
                                    <!-- Add a form for deleting the contact message -->
                                    <form method="post" action="">
                                        <input type="hidden" name="messageIdToDelete" value="<?php echo $message['id']; ?>">
                                        <button type="submit" class="btn btn-danger" name="deleteMessage">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>


                <!-- Orders Table -->
                <h3 id="alo" class='text-white'>All Orders</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col" class='text-white'>Order ID</th>
                            <th scope="col" class='text-white'>User ID</th>
                            <th scope="col" class='text-white'>First Name</th>
                            <th scope="col" class='text-white'>Email</th>
                            <th scope="col" class='text-white'>Subtotal</th>
                            <th scope="col" class='text-white'>Order Date</th>
                            <th scope="col" class='text-white'>Status</th>
                            <th scope="col" class='text-white'>Update Status</th>
                            <th scope="col" class='text-white'>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch and display orders from the database
                        $ordersQuery = "SELECT * FROM `orders`";
                        $ordersResult = $db->con->query($ordersQuery);

                        while ($order = $ordersResult->fetch_assoc()):
                            ?>
                            <tr>
                                <th scope="row" class='text-white'>
                                    <?php echo $order['order_id']; ?>
                                </th>
                                <td class='text-white'>
                                    <?php echo $order['user_id']; ?>
                                </td>
                                <td class='text-white'>
                                    <?php echo $order['name']; ?>
                                </td>
                                <td class='text-white'>
                                    <?php echo $order['email']; ?>
                                </td>
                                <td class='text-white'>
                                    <?php echo '$' . number_format($order['subtotal'], 2); ?>
                                </td>
                                <td class='text-white'>
                                    <?php echo $order['created_at']; ?>
                                </td>
                                <td class="status-<?php echo strtolower($order['status']); ?>" class='text-white'>
                                    <?php echo $order['status']; ?>
                                </td>
                                <td>
                                    <!-- Add a form for updating the status -->
                                    <form method="post" action="">
                                        <input type="hidden" name="orderIdToUpdate"
                                            value="<?php echo $order['order_id']; ?>">
                                        <select name="statusToUpdate" class="form-control" onchange="this.form.submit()">
                                            <option value="ordered" <?php echo ($order['status'] == 'ordered') ? 'selected' : ''; ?>>Ordered</option>
                                            <option value="shipped" <?php echo ($order['status'] == 'shipped') ? 'selected' : ''; ?>>Shipped</option>
                                            <option value="delivered" <?php echo ($order['status'] == 'delivered') ? 'selected' : ''; ?>>Delivered</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <!-- Add a form for deleting the order -->
                                    <form method="post" action="">
                                        <input type="hidden" name="orderIdToDelete"
                                            value="<?php echo $order['order_id']; ?>">
                                        <button type="submit" class="btn btn-danger" name="deleteOrder">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies (jQuery, Popper.js) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+WyTy7a5ezFqFckfv2ZfNvEp81dJdaa4tR"
        crossorigin="anonymous"></script>
</body>

</html>