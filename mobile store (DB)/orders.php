<?php
// Include the necessary files and perform any required checks
include 'header_cart.php';
// Check if the form is submitted for deleting an order
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

// Fetch and display orders from the database
$ordersQuery = "SELECT * FROM `orders`";
$ordersResult = $db->con->query($ordersQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Head section with styles and meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+WyTy7a5ezFqFckfv2ZfNvEp81dJdaa4tR" crossorigin="anonymous">
    <style>
        /* Add your custom styles here */
        .custom-margin {
            margin: 40px;
        }

        /* Styles for order status colors */
        .status-ordered {
            color: blue;
        }

        .status-shipped {
            color: blueviolet;
        }

        .status-delivered {
            color: green;
        }
    </style>
</head>
<body style = 'background-color: rgb(31,31,31)'>
    <div class="container mt-5">
        <h2 class = 'text-white'>Orders</h2>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col"  class = 'text-white'>Order ID</th>
                    <th scope="col"  class = 'text-white'>First Name</th>
                    <th scope="col"  class = 'text-white'>Email</th>
                    <th scope="col"  class = 'text-white'>Subtotal</th>
                    <th scope="col"  class = 'text-white'>Order Date</th>
                    <th scope="col"  class = 'text-white'>Status</th>
                    <th scope="col"  class = 'text-white'>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = $ordersResult->fetch_assoc()) : 
                    if($order['user_id'] == $user_id) : ?>
                    <tr>
                        <th scope="row"  class = 'text-white'><?php echo $order['order_id']; ?></th>
                        <td  class = 'text-white'><?php echo $order['name']; ?></td>
                        <td  class = 'text-white'><?php echo $order['email']; ?></td>
                        <td  class = 'text-white'><?php echo '$' . number_format($order['subtotal'], 2); ?></td>
                        <td  class = 'text-white'><?php echo $order['created_at']; ?></td>
                        <td class="status-<?php echo strtolower($order['status']); ?>"><?php echo $order['status']; ?></td>
                        <td>
                            <?php if ($order['status'] == 'ordered') : ?>
                                <!-- Add a form for deleting the order -->
                                <form method="post" action="">
                                    <input type="hidden" name="orderIdToDelete" value="<?php echo $order['order_id']; ?>">
                                    <button type="submit" class="btn btn-danger text-white" name="deleteOrder">Cancel Order</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; 
                endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS and dependencies (jQuery, Popper.js) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+WyTy7a5ezFqFckfv2ZfNvEp81dJdaa4tR" crossorigin="anonymous"></script>
</body>
</html>
