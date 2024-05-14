<?php

if (!class_exists('Product')) {

// Use to fetch product data
class Product
{
    public $db = null;

    public function __construct(DBController $db)
    {
        if (!isset($db->con)) return null;
        $this->db = $db;
    }
    public function getRelatedProducts($itemId, $limit = 4)
    {
        $relatedProducts = [];
        
        // Get the brand of the current item
        $currentItemBrand = $this->getProductBrand($itemId);

        // Fetch all products with the same brand, excluding the current item
        $allProducts = $this->getData();
        foreach ($allProducts as $product) {
            if ($product['item_id'] != $itemId && $product['item_brand'] == $currentItemBrand) {
                $relatedProducts[] = $product;
            }

            // Stop when the desired number of related products is reached
            if (count($relatedProducts) >= $limit) {
                break;
            }
        }

        return $relatedProducts;
    }

    // Get the brand of a product (example)
    private function getProductBrand($itemId)
    {
        // Assuming you have a method to fetch a specific product by ID
        $product = $this->getProductById($itemId);

        // Assuming 'item_brand' is a column in your database
        return $product['item_brand'] ?? '';
    }

    // Assuming you have a method to get a product by its ID
    private function getProductById($itemId)
    {
        // Your logic to fetch a product by ID goes here
        // This could be a database query or another method to get the product data
        // For demonstration purposes, you might use another method like getData() and find the product
        $allProducts = $this->getData();
        foreach ($allProducts as $product) {
            if ($product['item_id'] == $itemId) {
                return $product;
            }
        }

        return null;
    }
    public function getData2($table, $userId = null) {
        $resultArray = array();

        if ($userId) {
            // Fetch cart items for the specified user ID
            $query = "SELECT * FROM `cart` WHERE `user_id` = '$userId'";
            $result = $this->db->con->query($query);

            while ($item = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                $resultArray[] = $item;
            }
        } else {
            // Fetch cart items for the currently logged-in user
            // Use the user ID from the session
            //session_start();
            $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

            if ($userId) {
                $query = "SELECT * FROM `cart` WHERE `user_id` = '$userId'";
                $result = $this->db->con->query($query);

                while ($item = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    $resultArray[] = $item;
                }
            }
        }

        return $resultArray;
    }
    
    // fetch product data using getData Method
    public function getData($table = 'product'){
        $result = $this->db->con->query("SELECT * FROM {$table}");

        $resultArray = array();

        // fetch product data one by one
        while ($item = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $resultArray[] = $item;
        }

        return $resultArray;
    }
    public function getCartItems($user_id)
    {
        $cartItems = array();

        if ($user_id !== null) {
            // Fetch cart items based on user_id
            $query = "SELECT * FROM `cart` WHERE `user_id` = '$user_id'";
            $result = $this->db->con->query($query);

            while ($row = $result->fetch_assoc()) {
                $cartItems[] = $row;
            }
        }

        return $cartItems;
    }

    // get product using item id
    public function getProduct($item_id = null, $table= 'product'){
        if (isset($item_id)){
            $result = $this->db->con->query("SELECT * FROM {$table} WHERE item_id={$item_id}");

            $resultArray = array();

            // fetch product data one by one
            while ($item = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                $resultArray[] = $item;
            }

            return $resultArray;
        }
    }
    public function getWishlistItems($user_id)
        {
            $wishlistItems = array();

            if ($user_id !== null) {
                // Fetch wishlist items based on user_id
                $query = "SELECT p.* FROM `wishlist` w
                          JOIN `product` p ON w.item_id = p.item_id
                          WHERE w.user_id = '$user_id'";
                $result = $this->db->con->query($query);

                while ($row = $result->fetch_assoc()) {
                    $wishlistItems[] = $row;
                }
            }

            return $wishlistItems;
        }


}
}
?>