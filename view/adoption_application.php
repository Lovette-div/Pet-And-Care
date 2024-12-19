<?php
// Start session
session_start();
include '../db/config.php';
include '../view/navbar.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pet_id = $_POST['pet_id'];
    $message = $_POST['message'];

    // Insert the application into the database
    $insert_query = "INSERT INTO adoption_applications (user_id, pet_id, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    
    if ($stmt) {
        $stmt->bind_param('iis', $user_id, $pet_id, $message); // Bind parameters
        if ($stmt->execute()) {
            echo "Adoption application submitted successfully!";
        } else {
            echo "Error submitting application: " . $stmt->error;
        }
        $stmt->close();
    } else {
        die("Query preparation failed: " . $conn->error);
    }
}

// Fetch pets for the dropdown
$pet_query = "SELECT pet_id, name FROM pets"; // Ensure 'name' matches the column in your table
$pet_result = $conn->query($pet_query);

if (!$pet_result) {
    die("Error fetching pets: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS manually inserted -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Adoption Application</title>
    <style>
        body {
            background-color: var(--bs-primary-bg-subtle);
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Adoption Application</h1>

        <!-- Form -->
        <form method="POST" action="">
            <div class="form-group">
                <label for="pet_id">Choose a pet:</label>
                <select class="form-control" name="pet_id" id="pet_id" required>
                    <?php while ($row = $pet_result->fetch_assoc()): ?>
                        <option value="<?= $row['pet_id']; ?>"><?= htmlspecialchars($row['name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="message">Why do you want to adopt this pet?</label>
                <textarea class="form-control" name="message" id="message" rows="4" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Submit Application</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
