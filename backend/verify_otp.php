<?php
header("Access-Control-Allow-Origin: http://localhost:3000"); // Ensure correct frontend origin
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

include 'database_connection.php';

$data = json_decode(file_get_contents("php://input"));
$id_number = $data->id_number;
$otp = $data->otp;

// Query to verify OTP
$query = $pdo->prepare("SELECT * FROM users WHERE id_number = :id_number AND otp = :otp");
$query->execute(['id_number' => $id_number, 'otp' => $otp]);
$user = $query->fetch();

if ($user) {
    // Reset OTP after successful verification
    $updateQuery = $pdo->prepare("UPDATE users SET otp = NULL WHERE id_number = :id_number");
    $updateQuery->execute(['id_number' => $id_number]);

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid OTP.']);
}
?>
