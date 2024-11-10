<?php
session_start();
include 'cors.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] != 1) {
    echo json_encode(['status' => 'error', 'message' => 'Access denied. Admins only.']);
    exit;
}

// Content for admin-only access
echo json_encode(['status' => 'success', 'message' => 'Welcome to the admin page.']);
?>
