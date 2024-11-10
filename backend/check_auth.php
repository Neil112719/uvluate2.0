<?php
session_start();
header("Access-Control-Allow-Origin: http://localhost:3000"); // Ensure correct frontend origin
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");
// Check if the user is logged in and is an admin
if (isset($_SESSION['user_id']) && isset($_SESSION['usertype']) && $_SESSION['usertype'] == 1) {
    echo json_encode(['isAuthenticated' => true, 'message' => 'User is authenticated as admin']);
} else {
    echo json_encode(['isAuthenticated' => false, 'message' => 'User is not authenticated or not an admin']);
}
?>
