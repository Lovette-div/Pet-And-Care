<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Admin Dashboard</title>
</head>
<body>
    <?php
    session_start();
    include '../view/navbar.php';
    include '../db/config.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
        header("Location: ../view/login.php");
        exit();
    }

    // Fetch metrics
    $total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users"))['count'];
    $total_pets = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM pets"))['count'];
    $total_applications = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM adoption_applications"))['count'];
    $pending_applications = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM adoption_applications WHERE application_status='pending'"))['count'];
    ?>
    
    <div class="container mt-5">
        <h2>Admin Dashboard</h2>
        <div class="row">
            <div class="col-md-3 .offset-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <p class="card-text"><?php echo $total_users; ?></p>
                    </div>
                </div>
            </div>
            <br>
            <div class="col-md-3 .offset-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Total Pets</h5>
                        <p class="card-text"><?php echo $total_pets; ?></p>
                    </div>
                </div>
            </div>
            <br>
            <div class="col-md-3 .offset-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Adoption Requests</h5>
                        <p class="card-text"><?php echo $total_applications; ?></p>
                    </div>
                </div>
            </div>
            <br>
            <div class="col-md-3 .offset-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Pending Applications</h5>
                        <p class="card-text"><?php echo $pending_applications; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
