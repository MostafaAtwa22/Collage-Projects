<!-- Special Price -->
<?php
include_once('database/Cart.php');
include_once('database/Product.php');
include_once('database/DBController.php');

$product = new Product(new DBController());
$Cart = new Cart(new DBController());

$brand = array_map(function ($pro) {
    return $pro['item_brand'];
}, $product->getData());

$unique = array_unique($brand);
sort($unique);
shuffle($product_shuffle);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    foreach ($product_shuffle as $item) {
        $button_name = "top_sale_submit_" . $item['item_id'];

        if (isset($_POST[$button_name])) {
            // call method addToCart
            $Cart->addToCart($_POST['user_id'], $_POST['item_id']);
        }
    }
}

$in_cart = $Cart->getCartId($product->getData('cart'));
?>

<section id="special-price">
    <div class="container">
        <h4 class="font-rubik font-size-20 text-white" style = "padding-top:30px;">Special Price</h4>
        <div id="filters" class="button-group text-right font-baloo font-size-16">
            <button class="btn is-checked text-white" data-filter="*">All Brand</button>
            <?php
            array_map(function ($brand) {
                printf('<button class="btn text-white" data-filter=".%s">%s</button>', $brand, $brand);
            }, $unique);
            ?>
        </div>

        <div class="grid">
            <?php
            foreach ($product_shuffle as $item) {
                $form_name = "add_to_cart_" . $item['item_id'];
                $button_name = "top_sale_submit_" . $item['item_id'];
                ?>
                <div class="grid-item  <?php echo $item['item_brand'] ?? "Brand"; ?>">
                    <div class="item py-2" style="width: 200px;background-color:black;border-radius: 50px; ">
                        <div class="product font-rale">
                            <a href="<?php printf('%s?item_id=%s', 'product.php', $item['item_id']); ?>"><img
                                    src="<?php echo $item['item_image'] ?? "./assets/products/13.png"; ?>" alt="product1"
                                    class="img-fluid"></a>
                            <div class="text-center">
                                <h6 class = 'text-white'>
                                    <?php echo $item['item_name'] ?? "Unknown"; ?>
                                </h6>
                                <div class="rating font-size-12" style = 'color: white;'>
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
                                    ?>
                                </div>
                                <form method="post" name="<?php echo $form_name; ?>">
                                    <input type="hidden" name="item_id" value="<?php echo $item['item_id'] ?? '1'; ?>">
                                    <input type="hidden" name="user_id"
                                        value="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; ?>">
                                    <?php
                                    $quantity = $item['item_quantity'] ?? 0;
                                    if ($quantity > 0) {
                                        $userCart = $product->getCartItems($_SESSION['user_id'] ?? null);
                                        if ($userCart && in_array($item['item_id'], $Cart->getCartId($userCart) ?? [])) {
                                            echo '<button type="submit" disabled class="btn btn-success font-size-12">In the Cart</button>';
                                        } else {
                                            echo '<button type="submit" name="' . $button_name . '" class="btn btn-dark font-size-12 text-dark" style = "background-color: white;border-radius:10px;">Add to Cart</button>';
                                        }
                                    } else {
                                        echo '<button type="button" class="btn btn-danger font-size-12" disabled>Out of Stock</button>';
                                    }
                                    ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</section>
<!-- !Special Price -->