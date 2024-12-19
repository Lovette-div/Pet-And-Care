<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Admin Dashboard</title>
    <style>
        body {
            background-color: var(--bs-primary-bg-subtle);
        }
    </style>
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
    $total_tips = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM care_tips"))['count'];

    // Fetch recent care tips
    $recentTips = $conn->query("
    SELECT c.title, c.content, c.created_at, u.fname AS author
    FROM care_tips c
    JOIN users u ON c.user_id = u.user_id
    ORDER BY c.created_at DESC
    LIMIT 3
    ");

    //fetching recent adoption requests
    $recentApplications = $conn->query("
    SELECT a.application_id, u.fname, p.name AS pet_name, a.message, a.created_at
    FROM adoption_applications a
    JOIN users u ON a.user_id = u.user_id
    JOIN pets p ON a.pet_id = p.pet_id
    ORDER BY a.created_at DESC
    LIMIT 3
    ");

    ?>
    
    <div class="container mt-5">
        <h2>Admin Dashboard</h2>
        <div class="row">
            <div class="col order-first">
                <div class="card text-center">
                    <div class="card-body bg-primary border-primary">
                        <h5 class="card-title">Total Users</h5>
                        <p class="card-text"><?php echo $total_users; ?></p>
                    </div>
                </div>
            </div>
            <br>
            <div class="col">
                <div class="card text-center">
                    <div class="card-body bg-danger border-danger">
                        <h5 class="card-title">Total Pets</h5>
                        <p class="card-text"><?php echo $total_pets; ?></p>
                    </div>
                </div>
            </div>
            <br>
            <div class="col">
                <div class="card text-center">
                    <div class="card-body bg-warning border-warning">
                        <h5 class="card-title">Adoption Requests</h5>
                        <p class="card-text"><?php echo $total_applications; ?></p>
                    </div>
                </div>
            </div>
            <br>
            <div class="col">
                <div class="card text-center">
                    <div class="card-body bg-info border-info">
                        <h5 class="card-title">Pending Applications</h5>
                        <p class="card-text"><?php echo $pending_applications; ?></p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center">
                    <div class="card-body bg-success border-success">
                        <h5 class="card-title">Total Care Tips</h5>
                        <p class="card-text"><?php echo $total_tips; ?></p>
                    </div>
                </div>
            </div>

        <!-- Recent Care Tip -->
        <div class="my-5">
            <h3>Recent Care Tips</h3>
            <ul class="list-group">
                <?php while ($tip = $recentTips->fetch_assoc()): ?>
                    <li class="list-group-item">
                        <strong><?php echo htmlspecialchars($tip['title']); ?></strong>
                        <p><?php echo htmlspecialchars($tip['content']); ?></p>
                        <small>Posted by <?php echo htmlspecialchars($tip['author']); ?> on <?php echo htmlspecialchars($tip['created_at']); ?></small>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>

        <!-- Recent Adoption Applications -->
        <div class="my-5">
            <h3>Recent Adoption Applications</h3>
            <ul class="list-group">
                <?php while ($application = $recentApplications->fetch_assoc()): ?>
                    <li class="list-group-item">
                        <strong><?php echo htmlspecialchars($application['fname']); ?></strong> applied to adopt <strong><?php echo htmlspecialchars($application['pet_name']); ?></strong>
                        <br>
                        <small>Message: <?php echo htmlspecialchars($application['message']); ?></small>
                        <br>
                        <small>Submitted on: <?php echo htmlspecialchars($application['created_at']); ?></small>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
