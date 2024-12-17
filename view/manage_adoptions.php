<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Manage Adoption Requests</title>
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

    $applications = mysqli_query($conn, "SELECT adoption_applications.*, pets.name AS pet_name, users.fname AS user_name 
                                         FROM adoption_applications 
                                         JOIN pets ON adoption_applications.pet_id = pets.pet_id 
                                         JOIN users ON adoption_applications.user_id = users.user_id");
    ?>
    
    <div class="container mt-5">
        <h2>Manage Adoption Requests</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Pet</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($application = mysqli_fetch_assoc($applications)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($application['user_name']); ?></td>
                    <td><?php echo htmlspecialchars($application['pet_name']); ?></td>
                    <td><?php echo htmlspecialchars($application['message']); ?></td>
                    <td><?php echo htmlspecialchars($application['application_status']); ?></td>
                    <td>
                        <a href="actions/approve_application.php?id=<?php echo $application['application_id']; ?>" class="btn btn-success btn-sm">Approve</a>
                        <a href="actions/reject_application.php?id=<?php echo $application['application_id']; ?>" class="btn btn-danger btn-sm">Reject</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
