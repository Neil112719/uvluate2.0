<?php
session_start();
header("Access-Control-Allow-Origin: http://localhost:3000"); // Ensure correct frontend origin
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");
require 'database_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] != 1) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

try {
    $query = $pdo->query("SELECT id_number, fname, middlename, lname, suffix, email, usertype, department, section FROM users");
    $users = $query->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['status' => 'success', 'users' => $users]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to fetch users: ' . $e->getMessage()]);
}
?>
