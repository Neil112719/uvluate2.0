<?php
session_start();
include 'cors.php';
require 'database_connection.php';

$data = json_decode(file_get_contents("php://input"));

// Ensure the user is authenticated and is an admin (usertype 1)
if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] != 1) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

// Check if id_number is provided
if (!isset($data->id_number)) {
    echo json_encode(['status' => 'error', 'message' => 'User ID is required']);
    exit;
}

$id_number = $data->id_number;

try {
    // Prepare and execute the delete statement
    $deleteQuery = $pdo->prepare("DELETE FROM users WHERE id_number = :id_number");
    $deleteQuery->execute(['id_number' => $id_number]);

    // Check if a row was deleted
    if ($deleteQuery->rowCount() > 0) {
        echo json_encode(['status' => 'success', 'message' => 'User deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete user: ' . $e->getMessage()]);
}
?>
