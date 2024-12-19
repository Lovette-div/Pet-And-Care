<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Manage Adoption Requests</title>
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
                    <button class="btn btn-success btn-sm approve-button" data-application-id="<?= $application['application_id']; ?>">Approve</button>
                    <button class="btn btn-danger btn-sm reject-button" data-application-id="<?= $application['application_id']; ?>">Reject</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function updateApplicationStatus(applicationId, status) {
    //an AJAX request to update the application status
    fetch('../actions/adoption_backend.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ application_id: applicationId, status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            // Hide the row dynamically
            const row = document.querySelector(`tr[data-application-id="${applicationId}"]`);
            if (row) {
                row.style.transition = 'opacity 0.3s ease'; // Add a fade-out effect
                row.style.opacity = '0'; // Set opacity to 0 to fade out
                setTimeout(() => row.remove(), 300); // Remove row after fade-out
            } else {
                console.error("Row not found for application ID:", applicationId);
            }
        } else {
            alert("Failed to update status: " + data.message);
        }
    })
    .catch(error => {
        console.error("Error updating application status:", error);
        alert("An error occurred. Please try again.");
    });
}

// Attach event listeners to Approve and Reject buttons
document.querySelectorAll('.approve-button').forEach(button => {
    button.addEventListener('click', function () {
        const applicationId = this.dataset.applicationId;
        updateApplicationStatus(applicationId, 'approved');
    });
});

document.querySelectorAll('.reject-button').forEach(button => {
    button.addEventListener('click', function () {
        const applicationId = this.dataset.applicationId;
        updateApplicationStatus(applicationId, 'rejected');
    });
});

</script>
</body>
</html>
