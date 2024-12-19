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
if (!isset($_POST['tip_id'])) {
    $response['message'] = 'Missing tip ID';
    echo json_encode($response);
    exit();
}

// Sanitize input
$tip_id = mysqli_real_escape_string($conn, $_POST['tip_id']);

// Prepare and execute delete query
$query = "DELETE FROM care_tips WHERE tip_id = '$tip_id'";

if (mysqli_query($conn, $query)) {
    $response['success'] = true;
    $response['message'] = 'Tip deleted successfully';
} else {
    $response['message'] = 'Error deleting tip: ' . mysqli_error($conn);
}

// Send JSON response
echo json_encode($response);
mysqli_close($conn);
?>