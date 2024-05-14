<?php

if (!class_exists('Cart')) {

    // php cart class
    class Cart
    {
        public $db = null;

        public function __construct(DBController $db)
        {
            if (!isset($db->con))
                return null;
            $this->db = $db;
        }

        // insert into cart table
        public function insertIntoCart($params = null, $table = "cart")
        {
            if ($this->db->con != null) {
                if ($params != null) {
                    // Check if the user is logged in
                    if (isset($_SESSION['user_id'])) {
                        // If logged in, include the user_id in the params
                        $params['user_id'] = $_SESSION['user_id'];
                    } else {
                        // If not logged in, you may choose to handle this case differently
                        // For example, you can redirect the user to the login page
                        // Or you can choose to insert a default value for user_id or nothing
                        // For now, I'm setting user_id to 0 when the user is not logged in
                        $params['user_id'] = 0;
                        header("Location: " . 'signup.php');
                    }

                    // get table columns
                    $columns = implode(',', array_keys($params));
                    $values = implode(',', array_values($params));

                    // create sql query
                    $query_string = sprintf("INSERT INTO %s(%s) VALUES(%s)", $table, $columns, $values);

                    // execute query
                    $result = $this->db->con->query($query_string);
                    return $result;
                }
            }
        }


        // to get user_id and item_id and insert into cart table
        public function addToCart($userid, $itemid)
        {
            if (isset($userid) && isset($itemid)) {
                $params = array(
                    "user_id" => $userid,
                    "item_id" => $itemid
                );

                // insert data into cart
                $result = $this->insertIntoCart($params);
                if ($result) {
                    // Reload Page
                    header("Location: " . $_SERVER['PHP_SELF']);

                }
            }
        }

        public function addToCart2($userid, $itemid)
        {
            if (isset($userid) && isset($itemid)) {
                $params = array(
                    "user_id" => $userid,
                    "item_id" => $itemid
                );

                // insert data into cart
                $result = $this->insertIntoCart($params);
                if ($result) {
                    // Reload Page
                    header("Location: " . 'cart.php');

                }
            }
        }
        // delete cart item using cart item id
        public function deleteCart($item_id = null, $table = 'cart')
        {
            if ($item_id != null) {
                $result = $this->db->con->query("DELETE FROM {$table} WHERE item_id={$item_id}");
                if ($result) {
                    header("Location:" . $_SERVER['PHP_SELF']);
                }
                return $result;
            }
        }

        // Update cart item quantity
        public function updateCart($cart_id, $quantity)
        {
            $cart_id = intval($cart_id);
            $quantity = intval($quantity);

            // Validate cart_id and quantity if needed

            // Perform the update query
            $query = "UPDATE cart SET quantity = ? WHERE cart_id = ?";
            $stmt = $this->db->con->prepare($query);

            if ($stmt) {
                $stmt->bind_param("ii", $quantity, $cart_id);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    // Update successful
                    $stmt->close();
                    return true;
                } else {
                    // Update did not affect any rows
                    $stmt->close();
                    return false;
                }
            } else {
                // Error in preparing the statement
                echo "Error: " . $this->db->con->error;
                return false;
            }
        }




        // calculate sub total
        public function getSum($arr)
        {
            if (isset($arr)) {
                $sum = 0;
                foreach ($arr as $item) {
                    $sum += floatval($item[0]);
                }
                return sprintf('%.2f', $sum);
            }
        }

        // get item_it of shopping cart list
        public function getCartId($cartArray = null, $key = "item_id")
        {
            if ($cartArray != null) {
                $cart_id = array_map(function ($value) use ($key) {
                    return $value[$key];
                }, $cartArray);
                return $cart_id;
            }
        }

        public function saveForLater($item_id = null, $saveTable = "wishlist", $fromTable = "cart")
        {
            if ($item_id != null) {
                // Explicitly specify columns in the SELECT query
                $query = "INSERT INTO {$saveTable} (user_id, item_id) SELECT user_id, item_id FROM {$fromTable} WHERE item_id={$item_id};";

                // execute multiple query
                $result = $this->db->con->multi_query($query);

                if ($result) {
                    header("Location: " . 'wish.php');
                    exit(); // Stop script execution after redirect
                }
                return $result;
            }
        }
        // Check if an item is in the wishlist
        public function isInWishlist($item_id)
        {
            if ($this->db->con != null) {
                $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

                if ($user_id !== null) {
                    $query = "SELECT * FROM wishlist WHERE user_id = '$user_id' AND item_id = '$item_id'";
                    $result = $this->db->con->query($query);

                    return $result->num_rows > 0;
                }
            }

            return false;
        }


    }
} ?>