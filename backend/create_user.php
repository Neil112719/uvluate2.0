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

// Extract user details from the request
$id_number = $data->id_number;
$password = password_hash($data->password, PASSWORD_BCRYPT); // Hash the password
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
    $insertQuery = $pdo->prepare("INSERT INTO users (id_number, password, fname, middlename, lname, suffix, email, usertype, department, course, section, year) VALUES (:id_number, :password, :fname, :middlename, :lname, :suffix, :email, :usertype, :department, :course, :section, :year)");
    $insertQuery->execute([
        'id_number' => $id_number,
        'password' => $password,
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
    echo json_encode(['status' => 'success', 'message' => 'User created successfully']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to create user: ' . $e->getMessage()]);
}
?>
