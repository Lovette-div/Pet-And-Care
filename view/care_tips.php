<?php
session_start();
include '../db/config.php';
include '../view/navbar.php';

// Check if user is logged in
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Handle creating a new care tip
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_tip'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $user_id = $_SESSION['user_id']; 

    // Insert into database
    $insert_query = "INSERT INTO care_tips (title, content, user_id) VALUES ('$title', '$content', '$user_id')";
    if (mysqli_query($conn, $insert_query)) {
        echo "<script>alert('Care Tip created successfully.');</script>";
    } 
}

// Handle editing a care tip
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_tip'])) {
    $tip_id = $_POST['tip_id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    
    $update_query = "UPDATE care_tips SET title = '$title', content = '$content' WHERE tip_id = '$tip_id' AND user_id = '$user_id'";
    if (mysqli_query($conn, $update_query)) {
        header("Location: caretips.php");}
}

// Handle deleting a care tip
if (isset($_GET['delete_tip_id'])) {
    $tip_id = $_GET['delete_tip_id'];
    $delete_query = "DELETE FROM care_tips WHERE tip_id = '$tip_id' AND user_id = '$user_id'";
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>alert('Care Tip deleted successfully.');</script>";     
    } 
}

// Fetch care tips with user information
$tips_query = "SELECT care_tips.*, users.fname, users.lname 
               FROM care_tips 
               JOIN users ON care_tips.user_id = users.user_id 
               ORDER BY care_tips.created_at DESC";
$tips_result = mysqli_query($conn, $tips_query);

function truncateContent($content, $length = 200) {
    return strlen($content) > $length 
        ? substr($content, 0, $length) . '...' 
        : $content;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Care Tips</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .tip-card {
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .tip-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }

        body {
            background-color: rgba(var(--bs-primary-rgb));
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <!-- Create New Tip Button (Only for logged-in users) -->
                <?php if ($user_id): ?>
                <div class="mb-4">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#createTipModal">
                        Create New Care Tip
                    </button>
                </div>
                <?php endif; ?>

                <!-- Care Tips Section -->
                <?php if (mysqli_num_rows($tips_result) > 0): ?>
                    <?php while ($tip = mysqli_fetch_assoc($tips_result)): ?>
                        <div class="card tip-card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($tip['title']); ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted">
                                    By <?php echo htmlspecialchars($tip['fname'] . ' ' . $tip['lname']); ?> 
                                    on <?php echo date('F j, Y', strtotime($tip['created_at'])); ?>
                                </h6>
                                <p class="card-text"><?php echo htmlspecialchars(truncateContent($tip['content'])); ?></p>
                                <div class="tip-actions">
                                    <a href="tip_detail.php?tip_id=<?php echo $tip['tip_id']; ?>" class="btn btn-sm btn-info">
                                        Read More
                                    </a>
                                
                                    <button class="btn btn-sm btn-warning">Edit</button>
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                        
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="alert alert-info">No care tips available yet.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Create Tip Modal -->
    <?php if ($user_id): ?>
    <div class="modal fade" id="createTipModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Care Tip</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="caretips.php" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="form-group">
                            <label>Content</label>
                            <textarea class="form-control" name="content" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="create_tip">Submit Tip</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <?php if (isset($_GET['edit_tip_id'])): ?>
        <?php 
            $tip_id = $_GET['edit_tip_id'];
            $get_tip_query = "SELECT * FROM care_tips WHERE tip_id = '$tip_id' AND user_id = '$user_id'";
            $get_tip_result = mysqli_query($conn, $get_tip_query);
            $tip = mysqli_fetch_assoc($get_tip_result);
        ?>
        <div class="modal fade" id="editTipModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Care Tip</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form action="caretips.php" method="POST">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($tip['title']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Content</label>
                                <textarea class="form-control" name="content" rows="4" required><?php echo htmlspecialchars($tip['content']); ?></textarea>
                            </div>
                            <input type="hidden" name="tip_id" value="<?php echo $tip['tip_id']; ?>">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="edit_tip">Update Tip</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
