<?php
session_start();
include '../db/config.php';  
include '../view/navbar.php';

// Check if the user is an admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../view/login.php");
    exit();
}

// Fetch care tips from the database
$applications = mysqli_query($conn, "SELECT care_tips.*, users.fname AS user_name 
                                     FROM care_tips 
                                     JOIN users ON care_tips.user_id = users.user_id");

if (!$applications) {
    die("Query failed: " . mysqli_error($conn));
}

// Delete tip functionality
if (isset($_GET['delete_tip'])) {
    $tip_id = $_GET['delete_tip'];
    $delete_query = "DELETE FROM care_tips WHERE tip_id = ?";
    
    if ($stmt = mysqli_prepare($conn, $delete_query)) {
        mysqli_stmt_bind_param($stmt, "i", $tip_id);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Tip deleted successfully');</script>";
            echo "<script>window.location = 'manage_tips.php';</script>"; // Redirect to the same page to refresh the table
        } else {
            echo "<script>alert('Failed to delete tip');</script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Error preparing statement');</script>";
    }
}

// Edit tip functionality (POST request)
if (isset($_POST['edit_tip'])) {
    $tip_id = $_POST['tip_id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    
    $edit_query = "UPDATE care_tips SET title = ?, content = ? WHERE tip_id = ?";
    
    if ($stmt = mysqli_prepare($conn, $edit_query)) {
        mysqli_stmt_bind_param($stmt, "ssi", $title, $content, $tip_id);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Tip updated successfully');</script>";
            echo "<script>window.location = 'manage_tips.php';</script>"; // Redirect to the same page to refresh the table
        } else {
            echo "<script>alert('Failed to update tip');</script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Error preparing statement');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tips</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: var(--bs-primary-bg-subtle);
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Manage Care Tips</h2>

        <?php if (mysqli_num_rows($applications) > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Author</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($applications)): ?>
                        <tr>
                            <td><?php echo $row['tip_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['content']); ?></td>
                            <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                            <td><?php echo $row['created_at']; ?></td>
                            <td>
                                <!-- Edit Button (Opens the form with prefilled data) -->
                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editTipModal<?php echo $row['tip_id']; ?>">Edit</button>

                                <!-- Delete Button (AJAX Trigger) -->
                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteTip(<?php echo $row['tip_id']; ?>)">Delete</button>
                            </td>
                        </tr>

                        <!-- Edit Tip Modal -->
                        <div class="modal fade" id="editTipModal<?php echo $row['tip_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editTipModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editTipModalLabel">Edit Care Tip</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form id="editTipForm<?php echo $row['tip_id']; ?>" action="manage_tips.php" method="POST">
                                        <div class="modal-body">
                                            <input type="hidden" name="tip_id" value="<?php echo $row['tip_id']; ?>">
                                            <div class="form-group">
                                                <label for="title">Title</label>
                                                <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($row['title']); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="content">Content</label>
                                                <textarea class="form-control" name="content" rows="4" required><?php echo htmlspecialchars($row['content']); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="edit_tip" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No care tips found.</p>
        <?php endif; ?>
    </div>

    <!-- Add Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- JavaScript for Delete Tip via AJAX -->
    <script>
        function deleteTip(tipId) {
            if (confirm('Are you sure you want to delete this tip?')) {
                window.location.href = 'manage_tips.php?delete_tip=' + tipId;
            }
        }
    </script>
</body>
</html>
