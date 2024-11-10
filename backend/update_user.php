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

// Extract user details from the request
$id_number = $data->id_number;
$fname = $data->fname;
$middlename = $data->middlename ?? null;
$lname = $data->lname;
$suffix = $data->suffix ?? null;
$email = $data->email;
$usertype = $data->usertype;
$department = $data->department ?? null;
$course = $data->course ?? null;
$section = $data->section ?? null;
$year = $data->year ?? null;

try {
    // Prepare and execute the update statement
    $updateQuery = $pdo->prepare("
        UPDATE users
        SET fname = :fname,
            middlename = :middlename,
            lname = :lname,
            suffix = :suffix,
            email = :email,
            usertype = :usertype,
            department = :department,
            course = :course,
            section = :section,
            year = :year
        WHERE id_number = :id_number
    ");
    $updateQuery->execute([
        'id_number' => $id_number,
        'fname' => $fname,
        'middlename' => $middlename,
        'lname' => $lname,
        'suffix' => $suffix,
        'email' => $email,
        'usertype' => $usertype,
        'department' => $department,
        'course' => $course,
        'section' => $section,
        'year' => $year
    ]);

    // Check if the row was actually updated
    if ($updateQuery->rowCount() > 0) {
        echo json_encode(['status' => 'success', 'message' => 'User updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No changes made or user not found']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update user: ' . $e->getMessage()]);
}
?>
