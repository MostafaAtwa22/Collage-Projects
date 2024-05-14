<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete-cart-submit'])) {
        $deletedrecord = $Cart->deleteCart($_POST['item_id']);
    }
    // save for later
    if (isset($_POST['wishlist-submit'])) {
        $Cart->saveForLater($_POST['item_id']);
    }

    // Process "Proceed to Buy" button
    if (isset($_POST['proceed-to-buy'])) {
        // Assuming you have a method in the Cart class to update the quantity in the cart table
        foreach ($product->getCartItems($user_id) as $item) {
            $itemId = $item['item_id'];
            $quantity = $_POST["quantity-$itemId"]; // Adjust the form field name as needed

            // Update the quantity in the cart table
            $Cart->updateCart($item['cart_id'], $quantity);
        }

        // Redirect to the desired page after updating the cart
        header("Location: form.php");
        exit();
    }
}
?>
<body style = 'background-color : rgb(31,31,31)'>
<section id="cart" class="py-3 mb-5">
    <div class="container-fluid w-75">
        <h5 class="font-baloo font-size-20 text-white">Shopping Cart</h5>

        <!-- shopping cart items -->
        <div class="row">
            <div class="col-sm-9">
                <?php
                $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
                $totalItems = 0; // Initialize total items counter
                foreach ($product->getCartItems($user_id) as $item):
                    $cart = $product->getProduct($item['item_id']);
                    $subTotal[] = array_map(function ($item) use ($Cart, &$totalItems) {
                        $totalItems++; // Increment total items counter
                        ?>
                        <!-- cart item -->
                        <div class="row border-top py-3 mt-3">
                            <div class="col-sm-2">
                                <img src="<?php echo $item['item_image'] ?? "./assets/products/1.png" ?>" style="height: 120px;"
                                    alt="cart1" class="img-fluid">
                            </div>
                            <div class="col-sm-8">
                                <h5 class="font-baloo font-size-20 text-white">
                                    <?php echo $item['item_name'] ?? "Unknown"; ?>
                                </h5>
                                <small class = 'text-white'>by
                                    <?php echo $item['item_brand'] ?? "Brand"; ?>
                                </small>
                                <!-- product rating -->
                                <div class="d-flex">
                                    <div class="rating text-white font-size-12">
                                        <span><i class="fas fa-star"></i></span>
                                        <span><i class="fas fa-star"></i></span>
                                        <span><i class="fas fa-star"></i></span>
                                        <span><i class="fas fa-star"></i></span>
                                        <span><i class="far fa-star"></i></span>
                                    </div>
                                </div>
                                <!-- !product rating-->

                                <!-- product qty -->
                                <div class="qty d-flex pt-2">
                                    <div class="d-flex font-rale w-25">
                                        <button class="qty-up border bg-light"
                                            data-id="<?php echo $item['item_id'] ?? '0'; ?>"><i
                                                class="fas fa-angle-up"></i></button>
                                        <input type="text" data-id="<?php echo $item['item_id'] ?? '0'; ?>"
                                            class="qty_input border px-2 w-100 bg-light" disabled value="1" placeholder="1">
                                        <button data-id="<?php echo $item['item_id'] ?? '0'; ?>"
                                            class="qty-down border bg-light"><i class="fas fa-angle-down"></i></button>
                                    </div>

                                    <form method="post">
                                        <input type="hidden" value="<?php echo $item['item_id'] ?? 0; ?>" name="item_id">
                                        <button type="submit" name="delete-cart-submit"
                                            class="btn font-baloo text-white px-3 border-right ">Delete</button>
                                    </form>

                                    <?php
                                    // Check if the item is in the wishlist
                                    $isInWishlist = $Cart->isInWishlist($item['item_id']);

                                    if (!$isInWishlist):
                                        ?>
                                        <form method="post">
                                            <input type="hidden" value="<?php echo $item['item_id'] ?? 0; ?>" name="item_id">
                                            <button type="submit" name="wishlist-submit" class="btn font-baloo text-white">Save for
                                                Later</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="btn font-baloo text-white">Saved</span>
                                    <?php endif; ?>
                                </div>
                                <!-- !product qty -->

                            </div>

                            <div class="col-sm-2 text-right">
                                <div class="font-size-20 text-danger font-baloo">
                                    <span class = 'text-white'>$</span><span class="product_price text-white" data-id="<?php echo $item['item_id'] ?? '0'; ?>">
                                        <?php echo $item['item_price'] ?? 0; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- !cart item -->
                        <?php
                        return $item['item_price'];
                    }, $cart); // closing array_map function
                endforeach;
                ?>
            </div>
            <!-- subtotal section-->
            <div class="col-sm-3">
                <div class="sub-total border text-center mt-2">
                    <h6 class="font-size-12 font-rale text-white py-3"><i class="fas fa-check text-white"></i> Your order is
                        eligible for FREE Delivery.</h6>
                    <div class="border-top py-4">
                        <h5 class="font-baloo font-size-20 text-white">Subtotal (
                            <span class="total-items">
                                <?php echo $totalItems; ?>
                            </span> item):&nbsp; <span class="text-white">$<span class="text-white" id="deal-price">
                                    <?php echo isset($subTotal) ? $Cart->getSum($subTotal) : 0; ?>
                                </span> </span>
                        </h5>
                        <button type="submit" class="btn text-dark mt-3" style = 'background-color: white;' onclick="redirectToNewPage()">Proceed to
                            Buy</button>
                    </div>
                </div>
            </div>
            <!-- !subtotal section-->
        </div>
        <!-- !shopping cart items -->
    </div>
</section>
                        </body>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const qtyUp = document.querySelectorAll(".qty-up");
        const qtyDown = document.querySelectorAll(".qty-down");
        const qtyInput = document.querySelectorAll(".qty_input");

        qtyUp.forEach(button => {
            button.addEventListener("click", function () {
                updateQuantity(this, 1);
            });
        });

        qtyDown.forEach(button => {
            button.addEventListener("click", function () {
                updateQuantity(this, -1);
            });
        });

        // ... (previous code)

        function updateQuantity(button, value) {
            const itemId = button.getAttribute("data-id");
            const inputElement = document.querySelector(`.qty_input[data-id="${itemId}"]`);
            const productPriceElement = document.querySelector(`.product_price[data-id="${itemId}"]`);
            const dealPriceElement = document.getElementById("deal-price");
            const totalItemsElement = document.querySelector('.total-items');

            // Update quantity, but prevent it from going below 1
            let newValue = Math.max(parseInt(inputElement.value) + value, 1);
            if (newValue === parseInt(inputElement.value)) {
                // If the new value is the same as the current value, do not update
                return;
            }
            inputElement.value = newValue;

            // Update total number of items
            totalItemsElement.innerText = parseInt(totalItemsElement.innerText) + value;

            // Update product price
            const itemPrice = parseFloat(productPriceElement.innerText);
            const newProductPrice = (itemPrice / (inputElement.value - value) * inputElement.value).toFixed(2);
            productPriceElement.innerText = newProductPrice;

            // Update total deal price
            const subTotalElements = document.querySelectorAll('.product_price');
            let total = 0;
            subTotalElements.forEach(element => {
                total += parseFloat(element.innerText);
            });

            dealPriceElement.innerText = total.toFixed(2);

            // Send the updated quantity to the server using an AJAX request
            const formData = new FormData();
            formData.append('item_id', itemId);
            formData.append('quantity', newValue);

            fetch('update_cart.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === 'success') {
                        console.log('Quantity updated successfully');
                    } else {
                        console.error('Failed to update quantity');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }


    });

    function redirectToNewPage() {
        // Change the URL to the desired new page
        window.location.href = "form.php";
    }
</script>