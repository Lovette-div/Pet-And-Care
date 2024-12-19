<?php
include '../db/config.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle both POST and DELETE requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? null;

    if ($action === 'edit') {
        // Edit a tip
        $tip_id = $data['tip_id'] ?? null;
        $title = $data['title'] ?? null;
        $content = $data['content'] ?? null;

        if (!$tip_id || !$title || !$content) {
            echo json_encode(['success' => false, 'message' => 'Tip ID, title, and content are required.']);
            exit();
        }

        $stmt = $conn->prepare('UPDATE care_tips SET title = ?, content = ? WHERE tip_id = ?');
        $stmt->bind_param('ssi', $title, $content, $tip_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Tip updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update tip.']);
        }

        $stmt->close();
    } elseif ($action === 'delete') {
        // Delete a tip
        $tip_id = $data['tip_id'] ?? null;

        if (!$tip_id) {
            echo json_encode(['success' => false, 'message' => 'Tip ID is required for deletion.']);
            exit();
        }

        $stmt = $conn->prepare('DELETE FROM care_tips WHERE tip_id = ?');
        $stmt->bind_param('i', $tip_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Tip deleted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete tip.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
    }

    $conn->close();
    exit();
}

// Default response for unsupported request methods
echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
?>

