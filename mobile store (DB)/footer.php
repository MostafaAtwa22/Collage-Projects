<?php

require_once 'database/DBController.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $message = $_POST["message"];

    // Create an instance of the DBController class
    $db = new DBController();

    // Check if the connection was successful
    if ($db->con) {
        // Sanitize user input to prevent SQL injection
        $name = $db->con->real_escape_string($name);
        $email = $db->con->real_escape_string($email);
        $message = $db->con->real_escape_string($message);

        // Insert data into the contact_messages table
        $sql = "INSERT INTO contact_messages (name, email, message) VALUES ('$name', '$email', '$message')";

        if ($db->con->query($sql) === TRUE) {
            echo "Message saved successfully";
            
            // Redirect to a different page to avoid form resubmission
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $db->con->error;
        }
    } else {
        echo "Database connection failed";
    }
}

?>

</main>
<!-- !start #main-site -->

<!-- start #footer -->
<footer id="footer" class="bg-dark text-light py-4">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h4>Contact Us</h4>
          <form method="post"> <!-- Use POST method and specify the action -->
            <div class="form-group">
              <label for="name">Name</label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
              <label for="message">Message</label>
              <textarea class="form-control" id="message" name="message" rows="3" placeholder="Type your message" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
    <div class="copyright text-center bg-dark text-white py-2">
    <p class="font-rale font-size-14">
      &copy; Copyrights 2023. Design By <a href="#" class="color-second"> Kaleha 3la Allah </a>
      <span class="social-icons">
        <a href="#" class="color-second"><i class="fab fa-facebook fa-lg"></i></a>
        <a href="#" class="color-second"><i class="fab fa-instagram fa-lg"></i></a>
        <a href="#" class="color-second"><i class="fab fa-linkedin fa-lg"></i></a>
      </span>
    </p>
  </div>
</footer>
<!-- !start #footer -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

<!-- Owl Carousel Js file -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha256-pTxD+DSzIwmwhOqTFN+DB+nHjO4iAsbgfyFq5K5bcE0=" crossorigin="anonymous"></script>

<!--  isotope plugin cdn  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js" integrity="sha256-CBrpuqrMhXwcLLUd5tvQ4euBHCdh7wGlDfNz8vbu/iI=" crossorigin="anonymous"></script>

<!-- Custom Javascript -->
<script src="index.js"></script>
</body>
</html>
