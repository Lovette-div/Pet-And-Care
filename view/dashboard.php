<!-- 
include('../db/con.php'); // Include database connection
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'Customer' && $_SESSION['role'] != 'Administrator')) {
    header("Location: login.php"); // Redirect if unauthorized
    exit();
} -->

<?php
session_start();
include '../view/navbar.php';
include '../db/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$user_query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();


// Fetch user's adoption applications
$application_query = "SELECT * FROM adoption_applications 
                      JOIN pets ON adoption_applications.pet_id = pets.pet_id 
                      WHERE user_id = ?";
$stmt = $conn->prepare($application_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$applications_result = $stmt->get_result();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>User Dashboard</title>
    <style>
        body {
            background-color: var(--bs-primary-bg-subtle);
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Welcome, <?php echo htmlspecialchars($user['fname']); ?>!</h2>
        <hr>

        <h3>Your Adoption Applications</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Pet name</th>
                    <th>Application status</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody>
            <tr>
                    <td>Shiba</td>
                    <td>Not yet</td>
                    <td>A brown dog to be precise.</td>
                </tr>
                <?php while ($application = $applications_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($application['name']); ?></td>
                    <td><?php echo htmlspecialchars($application['application_status']); ?></td>
                    <td><?php echo htmlspecialchars($application['message']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <br>
        <h3>Update Your Information</h3>
        <form action="update_customer.php" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">First name</label>
                <input type="text" class="form-control" id="fname" name="fname" value="<?php echo htmlspecialchars($user['fname']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Last name</label>
                <input type="text" class="form-control" id="lname" name="lname" value="<?php echo htmlspecialchars($user['lname']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password">
            </div>
            <button type="submit" class="btn btn-primary">Update Information</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
