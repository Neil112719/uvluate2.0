<?php
session_start();
include 'cors.php';
require 'database_connection.php';

// Ensure the user is authenticated and is an admin (usertype 1)
if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] != 1) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

try {
    // Query to fetch all users
    $query = $pdo->query("SELECT id_number, fname, middlename, lname, suffix, email, usertype, department, course, section, year FROM users");
    $users = $query->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => 'success', 'users' => $users]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to fetch users: ' . $e->getMessage()]);
}
?>
