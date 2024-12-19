<?php
include '../db/config.php'; // Include database connection

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decode the JSON data from the request body
    $data = json_decode(file_get_contents('php://input'), true);
    $application_id = $data['application_id'] ?? null;
    $status = $data['status'] ?? null;

    // Validate the input
    if (!$application_id || !$status || !in_array($status, ['approved', 'rejected'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid application ID or status.']);
        exit();
    }

    // Update the application status in the database
    $stmt = $conn->prepare('UPDATE adoption_applications SET application_status = ? WHERE application_id = ?');
    $stmt->bind_param('si', $status, $application_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Application status updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update application status.']);
    }

    $stmt->close();
    $conn->close();
    exit();
}

// Return an error for invalid request methods
echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
?>
