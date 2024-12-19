<?php
session_start();
include '../db/config.php';

// Response array
$response = ['success' => false, 'message' => ''];

// Check if user is admin
if ($_SESSION['role'] !== 'admin') {
    $response['message'] = 'Unauthorized access';
    echo json_encode($response);
    exit();
}

// Validate input
if (!isset($_POST['tip_id']) || !isset($_POST['title']) || !isset($_POST['content'])) {
    $response['message'] = 'Missing required parameters';
    echo json_encode($response);
    exit();
}

// Sanitize inputs
$tip_id = mysqli_real_escape_string($conn, $_POST['tip_id']);
$title = mysqli_real_escape_string($conn, $_POST['title']);
$content = mysqli_real_escape_string($conn, $_POST['content']);

// Prepare and execute update query
$query = "UPDATE care_tips 
          SET title = '$title', 
              content = '$content', 
              updated_at = NOW() 
          WHERE tip_id = '$tip_id'";

if (mysqli_query($conn, $query)) {
    $response['success'] = true;
    $response['message'] = 'Tip updated successfully';
} else {
    $response['message'] = 'Error updating tip: ' . mysqli_error($conn);
}

// Send JSON response
echo json_encode($response);
mysqli_close($conn);
?>