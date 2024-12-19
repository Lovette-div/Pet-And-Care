<?php
session_start();
include '../db/config.php';

// Response array
$response = ['success' => false, 'message' => ''];

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'Please log in to create a tip';
    echo json_encode($response);
    exit();
}

// Validate input
if (!isset($_POST['title']) || !isset($_POST['content'])) {
    $response['message'] = 'Missing required parameters';
    echo json_encode($response);
    exit();
}

// Sanitize inputs
$user_id = $_SESSION['user_id'];
$title = mysqli_real_escape_string($conn, $_POST['title']);
$content = mysqli_real_escape_string($conn, $_POST['content']);

// Prepare and execute insert query
$query = "INSERT INTO care_tips (user_id, title, content) VALUES ('$user_id', '$title', '$content')";

if (mysqli_query($conn, $query)) {
    $response['success'] = true;
    $response['message'] = 'Tip created successfully';
} else {
    $response['message'] = 'Error creating tip: ' . mysqli_error($conn);
}

// Send JSON response
echo json_encode($response);
mysqli_close($conn);
?>