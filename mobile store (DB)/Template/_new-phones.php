<!-- New Phones -->
<?php
shuffle($product_shuffle);

// request method post
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['new_phones_submit'])) {
        // call method addToCart
        $Cart->addToCart($_POST['user_id'], $_POST['item_id']);
    }
}
?>
<section id="new-phones" style = "margin:50px;">
    <div class="container" style = "border-radius: 30px; background-color: black;">
        <h4 class="font-rubik font-size-20 text-white" style = "padding:20px;">New Phones</h4>
        <hr>

        <!-- owl carousel -->
        <div class="owl-carousel owl-theme">
            <?php foreach ($product_shuffle as $item) { ?>
                <div class="item py-2">
                    <div class="product font-rale">
                        <a href="<?php printf('%s?item_id=%s', 'product.php', $item['item_id']); ?>"><img src="<?php echo $item['item_image'] ?? "./assets/products/1.png"; ?>" alt="product1" class="img-fluid"></a>
                        <div class="text-center">
                            <h6 class = "text-white"><?php echo  $item['item_name'] ?? "Unknown"; ?></h6>
                            <div class="rating text-white font-size-12 text-align ">
                                <span><i class="fas fa-star"></i></span>
                                <span><i class="fas fa-star"></i></span>
                                <span><i class="fas fa-star"></i></span>
                                <span><i class="fas fa-star"></i></span>
                                <span><i class="far fa-star"></i></span>
                            </div>
                            <div class="price py-2 text-white">
                            <?php
            $originalPrice = $item['item_price'] ?? 0;
            $discount = $item['item_dis'] ?? 0;

            // Check if there is a discount
            if ($discount > 0) {
                $discountedPrice = $originalPrice - $discount;
                echo '$' . $discountedPrice;
            } else {
                echo '$' . $originalPrice;
            }
            ?> </div>
                            <form method="post">
                                <input type="hidden" name="item_id" value="<?php echo $item['item_id'] ?? '1'; ?>">
                                <input type="hidden" name="user_id" value="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; ?>">
                                <?php
                                // Check if the item is out of stock
                                $quantity = $item['item_quantity'] ?? 0;
                                if ($quantity > 0) {
                                    // Check if the item is in the user's cart
                                    $userCart = $product->getCartItems($_SESSION['user_id'] ?? null);
                                    if ($userCart && in_array($item['item_id'], $Cart->getCartId($userCart) ?? [])) {
                                        echo '<button type="submit" disabled class="btn btn-success font-size-12">In the Cart</button>';
                                    } else {
                                        echo '<button type="submit" name="top_sale_submit" class="btn btn-warning font-size-12"style = "background-color: white;border-radius:10px;">Add to Cart</button>';
                                    }
                                } else {
                                    echo '<button type="button" class="btn btn-danger font-size-12" disabled>Out of Stock</button>';
                                }
                                ?>
                            </form>
                        </div>
                    </div>
                </div>
            <?php 
        } // closing foreach function ?>
        </div>
        <!-- !owl carousel -->

    </div>
</section>
<!-- !New Phones -->
