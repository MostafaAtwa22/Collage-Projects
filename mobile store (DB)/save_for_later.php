<?php
// Include the necessary files
include('DBController.php');
include('Cart.php');

// Create an instance of the DBController class
$dbController = new DBController();

// Create an instance of the Cart class
$cart = new Cart($dbController);

// Get the saved items for later
$savedItems = $cart->getSavedForLater();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Save for Later</title>
    <!-- Add your CSS styles or include Bootstrap here -->
</head>

<body>

    <h2>Saved Items for Later</h2>

    <?php if (!empty($savedItems)) : ?>
        <table>
            <tr>
                <th>Item Name</th>
                <th>Item Price</th>
                <!-- Add more columns as needed -->
            </tr>
            <?php foreach ($savedItems as $item) : ?>
                <tr>
                    <td><?php echo $item['item_name']; ?></td>
                    <td><?php echo $item['item_price']; ?></td>
                    <!-- Add more cells as needed -->
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else : ?>
        <p>No items saved for later.</p>
    <?php endif; ?>

    <!-- Add your HTML/PHP code for displaying saved items and delete button -->

    <!-- Add your JavaScript or jQuery scripts here -->

</body>

</html>
