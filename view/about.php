<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php
    session_start();
    include '../view/navbar.php';
    ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center mb-4">About Us</h2>
                <div class="text-center mb-4">
                    <img src="../assets/images/background.jpeg" alt="Logo" class="rounded-circle">
                </div>
                <p class="lead text-center">
                    Welcome to our Pet and Care! We are dedicated to sharing and learning top-notch tips and information to help you care for your beloved pets.
                </p>
                <hr>
                <p>
                    Our mission is to ensure every pet owner has access to reliable and practical advice. From feeding to exercise, we've got you covered with tips from experts. Explore our platform for more helpful resources and a community of pet enthusiasts.
                </p>
                <p class="text-center mt-4">
                    <a href="#" class="btn btn-primary">Contact Us</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
