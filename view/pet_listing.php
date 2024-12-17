<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Pet Listings</title>
</head>
<body>
    <?php
    session_start();
    include '../view/navbar.php'; 
    ?>

    <div class="container mt-5">
        <h2>Available Pets for Adoption</h2>

        <div class="row">
            <!-- Filter and Search -->
            <div class="col-md-3">
                <form action="" method="GET">
                    <h5>Filters</h5>
                    <div class="mb-3">
                        <label>Species:</label>
                        <select class="form-select" name="species">
                            <option value="">All</option>
                            <option value="Dog">Dog</option>
                            <option value="Cat">Cat</option>
                            <option value="Parrot">Parrot</option>
                            <option value="Ferret">Ferret</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </form>
            </div>

            <!-- Pets List -->
            <div class="col-md-9">
                <div class="row">
                    <?php
                    include '../db/config.php';

                    $species = isset($_GET['species']) ? $_GET['species'] : '';

                    $query = "SELECT * FROM pets WHERE adoption_status = 'available'";
                    if ($species) {
                        $query .= " AND species = '$species'";
                    }

                    $result = mysqli_query($conn, $query);
                    while ($pet = mysqli_fetch_assoc($result)) {
                        echo '
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="' . $pet['image_url'] . '" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title">' . $pet['name'] . '</h5>
                                    <p class="card-text">' . $pet['description'] . '</p>
                                    <a href="../view/pet_profile.php?id=' . $pet['pet_id'] . '" class="btn btn-primary">View Details</a>
                                </div>
                            </div>
                        </div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
