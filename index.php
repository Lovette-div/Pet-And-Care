<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet and Care - Professional Pet Services</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            overflow: hidden;
        }
        .video-background {
            position: fixed;
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: -1;
            filter: brightness(0.5);
        }
        .content-overlay {
            position: relative;
            z-index: 10;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .slideshow-container {
            background-color: rgba(255, 255, 255, 0.9);
            justify-content: center;
            border-radius: 15px;
            padding: 30px;
            max-width: 600px;
            width: 100%;
        }
        .carousel-item img {
            max-height: 300px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <!-- Video Background -->
    <video autoplay muted loop class="video-background">
        <source src="../PetAndCare/assets/images/Puppies.mp4" type="video/mp4">
        
    </video>

    <div class="content-overlay">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="slideshow-container">
                        <h1 class="text-center mb-4">Pet and Care Services</h1>
                        
                        <div id="serviceCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="../PetAndCare/assets/images/1.jpg" class="d-block w-100" alt="Pet talking">
                                    <div class="carousel-caption">
                                        <h5>Professional Pet Advice</h5>
                                        <p>Keeping your pets active and healthy</p>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img src="../PetAndCare/assets/images/3.jpeg" class="d-block w-100" alt="Pet Grooming">
                                    <div class="carousel-caption">
                                        <h5>Expert Pet Adoption</h5>
                                        <p>Get yourself ready to be a parent to your furry friend</p>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img src="../PetAndCare/assets/images/13.jpeg" class="d-block w-100" alt="Pet Sitting">
                                    <div class="carousel-caption">
                                        <h5>Caring Pet Sitting</h5>
                                        <p>Trusted care when you're away</p>
                                    </div>
                                </div>
                            </div>
                            
                            <button class="carousel-control-prev" type="button" data-bs-target="#serviceCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#serviceCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>

                        <div class="text-center mt-4">
                            <a href="../PetAndCare/view/login.php" class="btn btn-primary me-2">Get Started</a>
                            <a href="#" class="btn btn-outline-secondary">Learn More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>