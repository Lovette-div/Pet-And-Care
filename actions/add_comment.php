<?php
session_start();
include '../db/config.php';

// Response array
$response = ['success' => false, 'message' => ''];

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'Please log in to comment';
    echo json_encode($response);
    exit();
}

// Validate input
if (!isset($_POST['tip_id']) || !isset($_POST['comment'])) {
    $response['message'] = 'Missing required parameters';
    echo json_encode($response);
    exit();
}

// Sanitize inputs
$user_id = $_SESSION['user_id'];
$tip_id = mysqli_real_escape_string($conn, $_POST['tip_id']);
$comment = mysqli_real_escape_string($conn, $_POST['comment']);

// Prepare and execute insert query
$stmt = $conn->prepare("INSERT INTO comments (user_id, tip_id, content) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $user_id, $tip_id, $comment);

if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'Comment added successfully';
} else {
    $response['message'] = 'Error adding comment: ' . $stmt->error;
}

$stmt->close();
echo json_encode($response);
mysqli_close($conn);
?>