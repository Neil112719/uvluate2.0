<?php
session_start();
header("Access-Control-Allow-Origin: http://localhost:3000"); // Ensure correct frontend origin
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] != 1) {
    echo json_encode(['status' => 'error', 'message' => 'Access denied. Admins only.']);
    exit;
}

// Content for admin-only access
echo json_encode(['status' => 'success', 'message' => 'Welcome to the admin page.']);
?>
