<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Pet Profile</title>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <?php
        include '../db/config.php';
        $pet_id = $_GET['id'];

        $query = "SELECT * FROM pets WHERE pet_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $pet_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $pet = $result->fetch_assoc();
        ?>

        <h2><?php echo $pet['name']; ?></h2>
        <img src="<?php echo $pet['image_url']; ?>" class="img-fluid" alt="Pet Image">
        <p><strong>Species:</strong> <?php echo $pet['species']; ?></p>
        <p><strong>Breed:</strong> <?php echo $pet['breed']; ?></p>
        <p><strong>Age:</strong> <?php echo $pet['age']; ?></p>
        <p><strong>Size:</strong> <?php echo $pet['size']; ?></p>
        <p><?php echo $pet['description']; ?></p>
        <a href="../view/adoption_application.php?pet_id=<?php echo $pet['pet_id']; ?>" class="btn btn-success">Adopt Me</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
