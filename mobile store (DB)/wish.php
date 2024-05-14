<?php
include_once('database/Product.php');
include_once('database/Cart.php');
include_once('database/DBController.php');
include 'header_cart.php';

$product = new Product(new DBController());
$Cart = new Cart(new DBController());

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete-wishlist-submit'])) {
        $deletedrecord = $Cart->deleteCart($_POST['item_id'], 'wishlist');
    }

    if (isset($_POST['cart-submit'])) {
        $Cart->saveForLater($_POST['item_id'], 'cart', 'wishlist');
    }
}
?>
<body style = 'background-color: rgb(31,31,31);'>
<section id="cart" class="py-3 mb-5">
    <div class="container-fluid w-75">
        <h5 class="font-baloo font-size-20 text-white">Wishlist</h5>

        <!-- Shopping cart items -->
        <div class="row">
            <div class="col-sm-9">
                <?php
                $wishlistItems = $product->getWishlistItems($_SESSION['user_id'] ?? null);

                if (empty($wishlistItems)) {
                    // Display Empty Wishlist section
                    echo '
                        <!-- Empty Wishlist -->
                        <div class="row border-top py-3 mt-3">
                            <div class="col-sm-12 text-center py-2">
                                <img src="./assets/blog/empty_cart.png" alt="Empty Wishlist" class="img-fluid" style="height: 200px; border-radius: 20px;">
                                <p class="font-baloo font-size-16 text-white">Empty Wishlist</p>
                            </div>
                        </div>
                        <!-- .Empty Wishlist -->
                    ';
                } else {
                    // Display wishlist items
                    foreach ($wishlistItems as $item) {
                        // Wishlist item
                        echo '
                            <div class="row border-top py-3 mt-3">
                                <div class="col-sm-2">
                                    <img src="' . ($item['item_image'] ?? "./assets/products/1.png") . '" style="height: 120px;" alt="wishlist1" class="img-fluid">
                                </div>
                                <div class="col-sm-8">
                                    <h5 class="font-baloo font-size-20 text-white">' . ($item['item_name'] ?? "Unknown") . '</h5>
                                    <small class = "text-white">by ' . ($item['item_brand'] ?? "Brand") . '</small>
                                    <!-- Product rating -->
                                    <div class="d-flex">
                                        <div class="rating text-white font-size-12">
                                            <span><i class="fas fa-star"></i></span>
                                            <span><i class="fas fa-star"></i></span>
                                            <span><i class="fas fa-star"></i></span>
                                            <span><i class="fas fa-star"></i></span>
                                            <span><i class="far fa-star"></i></span>
                                        </div>
                                    </div>
                                    <!-- Product qty -->
                                    <div class="qty d-flex pt-2">
                                        <form method="post">
                                            <input type="hidden" value="' . ($item['item_id'] ?? 0) . '" name="item_id">
                                            <button type="submit" name="delete-wishlist-submit" class="btn font-baloo text-white pl-0 pr-3 border-right">Remove</button>
                                        </form>';

                        // Check if the item is in the cart
                        $userCart = $product->getCartItems($_SESSION['user_id'] ?? null);
                        if ($userCart && in_array($item['item_id'], $Cart->getCartId($userCart) ?? [])) {
                            echo '
                                <button type="submit" name="" class="btn font-baloo text-white">In The Cart</button>
                            ';
                        } else {
                            // If not in the cart, show the "Move to Cart" button
                            echo '<form method="post">
                                <input type="hidden" value="' . ($item['item_id'] ?? 0) . '" name="item_id">
                                <button type="submit" name="cart-submit" class="btn font-baloo text-white">Move to Cart</button>
                            </form>';
                        }

                        echo '
                                    </div>
                                </div>
                                <div class="col-sm-2 text-right">
                                    <div class="font-size-20 text-danger font-baloo">
                                        <span class = "text-white">$</span><span class="product_price text-white" data-id="' . ($item['item_id'] ?? '0') . '">' . ($item['item_price'] ?? 0) . '</span>
                                    </div>
                                </div>
                            </div>
                            <!-- Wishlist item -->
                        ';
                    }
                }
                ?>
            </div>
        </div>
        <!-- Shopping cart items -->
    </div>
</section>
            </body>