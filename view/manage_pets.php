<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Manage Pets</title>
</head>
<body>
    <?php
    session_start();
    include '../view/navbar.php';
    include '../db/config.php';

    if ($_SESSION['role'] !== 'admin') {
        header("Location: ../view/login.php");
        exit();
    }

    $pets = mysqli_query($conn, "SELECT * FROM pets");
    ?>
    
    <div class="container mt-5">
        <h2>Manage Pets</h2>
        <a href="add_pet.php" class="btn btn-success mb-3">Add New Pet</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Species</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($pet = mysqli_fetch_assoc($pets)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($pet['name']); ?></td>
                    <td><?php echo htmlspecialchars($pet['species']); ?></td>
                    <td><?php echo htmlspecialchars($pet['adoption_status']); ?></td>
                    <td>
                        <a href="edit_pet.php?id=<?php echo $pet['pet_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="actions/delete_pet.php?id=<?php echo $pet['pet_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>
