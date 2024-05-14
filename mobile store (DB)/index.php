<?php
    ob_start();
    if (isset($_SESSION["user_id"])) {
    
        $mysqli = require __DIR__ . "/database.php";
        
        $sql = "SELECT * FROM user
                WHERE id = {$_SESSION["user_id"]}";
                
        $result = $mysqli->query($sql);
        
        $user = $result->fetch_assoc();
    }
    
?>

<?php
// include header.php file
    include ('header.php');
    /*  include banner area  */
        include ('Template/_banner-area.php');
    /*  include banner area  */

    /*  include top sale section */
        include ('Template/_top-sale.php');
    /*  include top sale section */

    /*  include special price section  */
         include ('Template/_special-price.php');
    /*  include special price section  */


    /*  include new phones section  */
        include ('Template/_new-phones.php');
    /*  include new phones section  */

    

?>

<?php
// include footer.php file
include ('footer.php');
?>