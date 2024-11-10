<?php
session_start();
header("Access-Control-Allow-Origin: http://localhost:3000"); // Ensure correct frontend origin
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");
require 'database_connection.php';

$data = json_decode(file_get_contents("php://input"));

if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] != 1) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$id_number = $data->id_number;

try {
    $query = $pdo->prepare("DELETE FROM users WHERE id_number = :id_number");
    $query->execute(['id_number' => $id_number]);
    echo json_encode(['status' => 'success', 'message' => 'User deleted successfully']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete user: ' . $e->getMessage()]);
}
?>
