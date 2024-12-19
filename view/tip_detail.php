<?php
session_start();
include '../db/config.php';
include '../view/navbar.php';

// Check if user is logged in
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Validate tip ID
if (!isset($_GET['tip_id'])) {
    header("Location: caretips.php");
    exit();
}

$tip_id = mysqli_real_escape_string($conn, $_GET['tip_id']);

// Fetch tip details
$tip_query = "SELECT care_tips.*, users.fname, users.lname 
              FROM care_tips 
              JOIN users ON care_tips.user_id = users.user_id 
              WHERE tip_id = '$tip_id'";
$tip_result = mysqli_query($conn, $tip_query);

if (mysqli_num_rows($tip_result) == 0) {
    header("Location: ../view/care_tips.php");
    exit();
}

$tip = mysqli_fetch_assoc($tip_result);

// Fetch comments for this tip
$comments_query = "SELECT comments.*, users.fname, users.lname 
                   FROM comments 
                   JOIN users ON comments.user_id = users.user_id 
                   WHERE tip_id = '$tip_id' 
                   ORDER BY comments.created_at DESC";
$comments_result = mysqli_query($conn, $comments_query);

// Handle the comment post (AJAX Request)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment']) && isset($_POST['tip_id'])) {
    if ($user_id) {
        $comment = mysqli_real_escape_string($conn, $_POST['comment']);
        $tip_id = (int) $_POST['tip_id'];
        $created_at = date('Y-m-d H:i:s');

        $query = "INSERT INTO comments (tip_id, user_id, content, created_at) 
                  VALUES ('$tip_id', '$user_id', '$comment', '$created_at')";
        
        if (mysqli_query($conn, $query)) {
            // Fetch the user's first name, last name, and created_at timestamp for the response
            $user_query = "SELECT fname, lname FROM users WHERE user_id = '$user_id'";
            $user_result = mysqli_query($conn, $user_query);
            $user = mysqli_fetch_assoc($user_result);

            echo json_encode([
                'success' => true,
                'comment' => htmlspecialchars($comment),
                'fname' => $user['fname'],
                'lname' => $user['lname'],
                'created_at' => date('F j, Y, g:i a', strtotime($created_at))
            ]);
            exit;
        } else {
            echo json_encode(['success' => false]);
            exit;
        }
    }
}

// Handle the comment delete (AJAX Request)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_comment_id']) && $user_id) {
    $comment_id = (int) $_POST['delete_comment_id'];

    // Ensure that the user is the one who posted the comment or an admin
    $query = "SELECT user_id FROM comments WHERE comment_id = '$comment_id'";
    $result = mysqli_query($conn, $query);
    $comment = mysqli_fetch_assoc($result);

    if ($comment && ($comment['user_id'] == $user_id || $_SESSION['is_admin'])) {
        $delete_query = "DELETE FROM comments WHERE comment_id = '$comment_id'";
        if (mysqli_query($conn, $delete_query)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Care Tip Details</title>
    <link href="../assets/css/styles.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <!-- Tip Details -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h2 class="card-title"><?php echo htmlspecialchars($tip['title']); ?></h2>
                        <h6 class="card-subtitle mb-2 text-muted">
                            By <?php echo htmlspecialchars($tip['fname'] . ' ' . $tip['lname']); ?> 
                            on <?php echo date('F j, Y', strtotime($tip['created_at'])); ?>
                        </h6>
                        <p class="card-text"><?php echo htmlspecialchars($tip['content']); ?></p>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Comments</h4>
                    </div>
                    <div class="card-body" id="comments-container">
                        <?php if(mysqli_num_rows($comments_result) > 0): ?>
                            <?php while($comment = mysqli_fetch_assoc($comments_result)): ?>
                                <div class="media mb-3" id="comment-<?php echo $comment['comment_id']; ?>">
                                    <div class="media-body">
                                        <h6 class="mt-0">
                                            <?php echo htmlspecialchars($comment['fname'] . ' ' . $comment['lname']); ?>
                                            <small class="text-muted ml-2"><?php echo date('F j, Y, g:i a', strtotime($comment['created_at'])); ?></small>
                                        </h6>
                                        <?php echo htmlspecialchars($comment['content']); ?>

                                        <!-- Delete Button (Visible only to the comment owner or an admin) -->
                                        <?php if ($user_id == $comment['user_id'] || $_SESSION['is_admin']): ?>
                                            <button class="btn btn-danger btn-sm delete-comment-btn" data-comment-id="<?php echo $comment['comment_id']; ?>">Delete</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="text-muted">No comments yet. Be the first to comment!</p>
                        <?php endif; ?>
                    </div>

                    <!-- Add Comment Form (Only for logged-in users) -->
                    <?php if($user_id): ?>
                    <div class="card-footer">
                        <form id="commentForm">
                            <input type="hidden" name="tip_id" value="<?php echo $tip_id; ?>">
                            <div class="form-group">
                                <textarea class="form-control" name="comment" rows="3" placeholder="Add a comment..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Post Comment</button>
                        </form>
                    </div>
                    <?php else: ?>
                    <div class="card-footer text-muted">
                        Please log in to add a comment.
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Post comment AJAX request
        $(document).ready(function() {
            $('#commentForm').submit(function(e) {
                e.preventDefault(); // Prevent the form from reloading the page
                
                var comment = $('textarea[name="comment"]').val();
                var tip_id = $('input[name="tip_id"]').val();
                
                if (comment) {
                    $.ajax({
                        url: '', 
                        type: 'POST',
                        data: {
                            comment: comment,
                            tip_id: tip_id
                        },
                        success: function(response) {
                            var data = JSON.parse(response);
                            if(data.success) {
                                // Display the new comment
                                $('#comments-container').prepend(`
                                    <div class="media mb-3">
                                        <div class="media-body">
                                            <h6 class="mt-0">${data.fname} ${data.lname} <small class="text-muted ml-2">${data.created_at}</small></h6>
                                            ${data.comment}
                                        </div>
                                    </div>
                                `);
                                $('textarea[name="comment"]').val(''); //cclear the comment input
                            } else {
                                alert('There was an error posting your comment.');
                            }
                        }
                    });
                } else {
                    alert('Please write a comment.');
                }
            });

            // Delete comment AJAX request
            $(document).on('click', '.delete-comment-btn', function() {
                var comment_id = $(this).data('comment-id'); 
                
                if (confirm('Are you sure you want to delete this comment?')) {
                    $.ajax({
                        url: '', 
                        type: 'POST',
                        data: { delete_comment_id: comment_id },
                        success: function(response) {
                            var data = JSON.parse(response);
                            if(data.success) {
                                // Remove the comment from the DOM
                                $('#comment-' + comment_id).remove();
                            } else {
                                alert('There was an error deleting the comment.');
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
