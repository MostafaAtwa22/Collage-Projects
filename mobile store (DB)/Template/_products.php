<?php
shuffle($product_shuffle);

// request method post
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['top_sale_submit'])) {
        // call method addToCart
        $Cart->addToCart2($_POST['user_id'], $_POST['item_id']);
    }

    // Save for Later logic
    if (isset($_POST['save_for_later_submit'])) {
        // Call the saveForLater method with the appropriate parameters
        $Cart->saveForLater($_POST['item_id']);
    }
}
?>

<?php
$item_id = $_GET['item_id'] ?? 1;
foreach ($product->getData() as $item):
    if ($item['item_id'] == $item_id):
        ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>
                <?php echo $item['item_name'] ?? "Product Details"; ?> - Mobile Store
            </title>

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

            <style>
                /* Add your custom styles here */
                #product {
                    margin-top: 50px;
                }

                #policy {
                    margin-top: 20px;
                    margin-bottom: 20px;
                }

                #pd {
                    margin-top: 30px;
                    margin-bottom: 20px;
                }

                /* Style for the related products section */
                #related-products {
                    margin-top: 30px;
                }

                #related-products h4 {
                    margin-bottom: 20px;
                }

                #related-products .card {
                    margin-bottom: 20px;
                }
            </style>
        </head>

        <body style = 'background-color: rgb(31,31,31);'>
                
            <!-- Product Section -->
            <section id="product" class="py-3">
                <div class="container">
                    <div class="row">
                        <!-- Product Image Section -->
                        <div class="col-sm-6">
                            <img src="<?php echo $item['item_image'] ?? "./assets/products/1.png" ?>" alt="product"
                                class="img-fluid">
                            <div class="form-row pt-4 font-size-16 font-baloo">
                                <div class="col">
                                    <form method="post">
                                        <input type="hidden" name="item_id" value="<?php echo $item['item_id'] ?? '1'; ?>">
                                        <input type="hidden" name="user_id"
                                            value="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; ?>">
                                        <?php
                                        // Check if the item is out of stock
                                        $quantity = $item['item_quantity'] ?? 0;
                                        if ($quantity > 0) {
                                            // Check if the item is in the user's cart
                                            $userCart = $product->getCartItems($_SESSION['user_id'] ?? null);
                                            if ($userCart && in_array($item['item_id'], $Cart->getCartId($userCart) ?? [])) {
                                                echo '<button type="submit" disabled class="btn btn-success font-size-12">In the Cart</button>';
                                            } else {
                                                echo '<button type="submit" name="top_sale_submit" class="btn text-dark font-size-12" style = "background-color: white;">Add to Cart</button>';
                                            }
                                        } else {
                                            echo '<button type="button" class="btn btn-danger font-size-12" disabled>Out of Stock</button>';
                                        }
                                        ?>
                                    </form>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Product Information Section -->
                        <div class="col-sm-6 py-5">
                            <h5 class="font-baloo font-size-20 text-white">
                                <?php echo $item['item_name'] ?? "Unknown"; ?>
                            </h5>
                            <small class = 'text-white'>by
                                <?php echo $item['item_brand'] ?? "Brand"; ?>
                            </small>
                            <div class="d-flex">
                                <div class="rating text-white font-size-12">
                                    <span><i class="fas fa-star"></i></span>
                                    <span><i class="fas fa-star"></i></span>
                                    <span><i class="fas fa-star"></i></span>
                                    <span><i class="fas fa-star"></i></span>
                                    <span><i class="far fa-star"></i></span>
                                </div>
                            </div>
                            <hr class="m-0">

                            <!-- Product Price Section -->
                            <table class="my-3">
                                <tr class="font-rale font-size-14">
                                    <td class = 'text-white'>Deal Price:</td>
                                    <td class="font-size-20 text-white">
                                        <?php
                                        $originalPrice = $item['item_price'] ?? 0;
                                        $discount = $item['item_dis'] ?? 0;

                                        // Check if there is a discount
                                        if ($discount > 0) {
                                            $discountedPrice = $originalPrice - $discount;
                                            echo '<del>$' . $originalPrice . '</del> $' . $discountedPrice;
                                        } else {
                                            echo '$' . $originalPrice;
                                        }
                                        ?>
                                        <small class="text-white font-size-12">&nbsp;&nbsp;Inclusive of all taxes</small>
                                    </td>
                                </tr>
                            </table>
                            <!-- !Product Price Section -->

                            <!-- Policy Section -->
                            <div id="policy">
                                <div class="d-flex">
                                    <div class="return text-center mr-5">
                                        <div class="font-size-20 my-2 color-second">
                                            <span class="fas fa-retweet border p-3 rounded-pill"></span>
                                        </div>
                                        <a href="#" class="font-rale font-size-12 text-white">10 Days <br> Replacement</a>
                                    </div>
                                    <div class="return text-center mr-5">
                                        <div class="font-size-20 my-2 color-second">
                                            <span class="fas fa-truck  border p-3 rounded-pill"></span>
                                        </div>
                                        <a href="#" class="font-rale font-size-12 text-white">Daily Tuition <br>Deliverd</a>
                                    </div>
                                    <div class="return text-center mr-5">
                                        <div class="font-size-20 my-2 color-second">
                                            <span class="fas fa-check-double border p-3 rounded-pill"></span>
                                        </div>
                                        <a href="#" class="font-rale font-size-12 text-white">1 Year <br>Warranty</a>
                                    </div>
                                </div>
                            </div>
                            <!-- !Policy Section -->
                            <hr>

                        </div>
                    </div>
                </div>
            </section>
            <!-- !Product Section -->

            <!-- Product Description Section -->
            <section id="pd" class="container mt-5">
                <h6 class="font-rubik text-white font-size-16">Product Description</h6>
                <hr>
                <p class="font-rale font-size-14 text-white">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Repellat inventore
                    vero numquam error est ipsa, consequuntur temporibus debitis nobis sit, delectus officia ducimus dolorum sed
                    corrupti. Sapiente optio sunt provident, accusantium eligendi eius reiciendis animi? Laboriosam, optio qui?
                    Numquam, quo fuga. Maiores minus, accusantium velit numquam a aliquam vitae vel?</p>
                <p class="font-rale font-size-14 text-white">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Repellat inventore
                    vero numquam error est ipsa, consequuntur temporibus debitis nobis sit, delectus officia ducimus dolorum sed
                    corrupti. Sapiente optio sunt provident, accusantium eligendi eius reiciendis animi? Laboriosam, optio qui?
                    Numquam, quo fuga. Maiores minus, accusantium velit numquam a aliquam vitae vel?</p>
            </section>
            <!-- !Product Description Section -->

            <!-- Related Products Section -->
            <section id="related-products" class="container">
                <h4 class="font-rubik text-white">Related Products</h4>
                <div class="row">
                    <?php
                    // Get related products (you need to implement a method for this in your Product class)
                    $relatedProducts = $product->getRelatedProducts($item['item_id']);
                    foreach ($relatedProducts as $relatedItem):
                        ?>
                        <div class="col-md-3">
                            <div class="card" style = 'background-color: black; border-radius:50px;'>
                                <img src="<?php echo $relatedItem['item_image'] ?? "./assets/products/1.png" ?>" alt="product"
                                    class="card-img-top">
                                <div class="card-body">
                                    <h6 class="card-title text-white">
                                        <?php echo $relatedItem['item_name'] ?? "Unknown"; ?>
                                    </h6>
                                    <p class="card-text font-size-14 text-white">
                                        <?php echo $relatedItem['item_brand'] ?? "Brand"; ?>
                                    </p>
                                    <a href="product.php?item_id=<?php echo $relatedItem['item_id']; ?>"
                                        class="btn text-dark" style = 'background-color: white;'>View</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <!-- !Related Products Section -->

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

        <?php
    endif;
endforeach;
?>