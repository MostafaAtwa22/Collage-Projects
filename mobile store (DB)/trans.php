<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Transaction Status</title>
</head>

<body style = 'background-color:rgb(31,31,31)'>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <h2 class="mb-4 text-white">Transaction Status</h2>
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">Transaction Done</h4>
                    <p>The shipment is getting prepared. Thank you for your purchase!</p>
                    <hr>
                    <p class="mb-0">Your order will be shipped soon.</p>
                </div>
                <button class="btn text-dark" onclick="redirectToNewPage()" style = 'background-color: white; border-radius:10px;'>Home Page</button>
            </div>
        </div>
    </div>
    <script>
        function redirectToNewPage() {
            window.location.href = "index.php";
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.8/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>