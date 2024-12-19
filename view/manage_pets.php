<?php
session_start();
include '../db/config.php';

// Check if the user is an admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../view/login.php");
    exit();
}

// Handle Add Pet
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = $_POST['name'];
    $species = $_POST['species'];
    $adoption_status = $_POST['adoption_status'];

    // Insert the new pet into the database
    $query = "INSERT INTO pets (name, species, adoption_status) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param('sss', $name, $species, $adoption_status);
        
        if ($stmt->execute()) {
            header("Location: manage_pets.php");
            exit();
        } else {
            echo "Error adding pet: " . $stmt->error;
        }
        $stmt->close();
    } else {
        die("Query preparation failed: " . $conn->error);
    }
}

// Handle Delete Pet
if (isset($_GET['delete_id'])) {
    $pet_id = $_GET['delete_id'];

    // Delete the pet from the database
    $query = "DELETE FROM pets WHERE pet_id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param('i', $pet_id);
        
        if ($stmt->execute()) {
            header("Location: manage_pets.php");
            exit();
        } else {
            echo "Error deleting pet: " . $stmt->error;
        }
        $stmt->close();
    } else {
        die("Query preparation failed: " . $conn->error);
    }
}

// Fetch all pets
$pets = mysqli_query($conn, "SELECT * FROM pets");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Manage Pets</title>
    <style>
        body {
            background-color: var(--bs-primary-bg-subtle);
        }
    </style>
</head>
<body>
    <?php include '../view/navbar.php'; ?>
    
    <div class="container mt-5">
        <h2>Manage Pets</h2>
        
        <!-- Button to trigger the modal -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addPetModal">Add New Pet</button>
        
        <!-- Table of pets -->
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
                        <!-- Edit Button -->
                        <a href="#?id=<?php echo $pet['pet_id']; ?>" class="btn btn-warning btn-sm">View</a>
                        
                        <!-- Delete Button -->
                        <a href="?delete_id=<?php echo $pet['pet_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this pet?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for Adding a New Pet -->
    <div class="modal fade" id="addPetModal" tabindex="-1" aria-labelledby="addPetModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPetModalLabel">Add New Pet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Add Pet Form -->
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="add">
                        <div class="mb-3">
                            <label for="name" class="form-label">Pet Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="species" class="form-label">Species</label>
                            <input type="text" class="form-control" id="species" name="species" required>
                        </div>
                        <div class="mb-3">
                            <label for="adoption_status" class="form-label">Adoption Status</label>
                            <select class="form-control" id="adoption_status" name="adoption_status" required>
                                <option value="Available">Available</option>
                                <option value="Adopted">Adopted</option>
                                <option value="Pending">Pending</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Pet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
