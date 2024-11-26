<?php
session_start();
include 'cors.php';

require 'database_connection.php';

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->id_number) || !isset($data->otp)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing ID number or OTP']);
    exit;
}

$id_number = $data->id_number;
$otp = $data->otp;

try {
    $query = $pdo->prepare("SELECT * FROM users WHERE id_number = :id_number AND otp = :otp");
    $query->execute(['id_number' => $id_number, 'otp' => $otp]);
    $user = $query->fetch();

    if ($user) {
        $otpCreatedAt = new DateTime($user['timestamp']);
        $currentTime = new DateTime();
        $timeDifference = $currentTime->getTimestamp() - $otpCreatedAt->getTimestamp();

        // Check if OTP is expired (1 minute)
        if ($timeDifference > 60) {
            echo json_encode(['status' => 'error', 'message' => 'OTP expired. Please request a new OTP.']);
            exit;
        }

        // Clear the OTP after successful verification
        $clearOtpQuery = $pdo->prepare("UPDATE users SET otp = NULL, timestamp = NULL WHERE id_number = :id_number");
        $clearOtpQuery->execute(['id_number' => $id_number]);

        // Ensure usertype is set and valid
        $usertype = isset($user['usertype']) ? $user['usertype'] : 'unknown';

        if (in_array($usertype, [1, 2, 3, 4])) {
            $_SESSION['user_id'] = $user['id_number'];
            $_SESSION['usertype'] = $usertype;
            echo json_encode(['status' => 'success', 'message' => 'OTP verified successfully', 'usertype' => $usertype]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Unknown user type']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid OTP or user not found']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
