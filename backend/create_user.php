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
$password = password_hash($data->password, PASSWORD_BCRYPT);
$fname = $data->fname;
$middlename = $data->middlename;
$lname = $data->lname;
$suffix = $data->suffix;
$email = $data->email;
$usertype = $data->usertype;
$department = $data->department;
$section = $data->section;

try {
    $insertQuery = $pdo->prepare("INSERT INTO users (id_number, password, fname, middlename, lname, suffix, email, usertype, department, section) VALUES (:id_number, :password, :fname, :middlename, :lname, :suffix, :email, :usertype, :department, :section)");
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
        'section' => $section
    ]);
    echo json_encode(['status' => 'success', 'message' => 'User created successfully']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to create user: ' . $e->getMessage()]);
}
?>
