<?php
session_start();
include 'cors.php';
// Check if the user is logged in and is an admin
if (isset($_SESSION['user_id']) && isset($_SESSION['usertype']) && $_SESSION['usertype'] == 1) {
    echo json_encode(['isAuthenticated' => true, 'message' => 'User is authenticated as admin']);
} else {
    echo json_encode(['isAuthenticated' => false, 'message' => 'User is not authenticated or not an admin']);
}
?>
