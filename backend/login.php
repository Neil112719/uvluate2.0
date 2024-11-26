<?php
session_start();
include 'cors.php';
require 'database_connection.php';
require 'vendor/autoload.php'; // Load PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$data = json_decode(file_get_contents("php://input"));
$id_number = $data->id_number ?? '';
$password = $data->password ?? '';

// Check for missing ID or password
if (empty($id_number) || empty($password)) {
    echo json_encode(['status' => 'error', 'message' => 'ID number and password are required.']);
    exit;
}

// Query to check user credentials
$query = $pdo->prepare("SELECT * FROM users WHERE id_number = :id_number");
$query->execute(['id_number' => $id_number]);
$user = $query->fetch();

if ($user && password_verify($password, $user['password'])) {
    $currentTime = new DateTime();

    // Check if OTP was generated within the last 1 minute
    if ($user['timestamp']) {
        $otpCreatedAt = new DateTime($user['timestamp']);
        $timeDifference = $currentTime->getTimestamp() - $otpCreatedAt->getTimestamp();

        if ($timeDifference < 60) { // 60 seconds = 1 minute
            echo json_encode(['status' => 'otp_required', 'message' => 'An OTP was already sent. Please check your email.']);
            exit;
        }
    }

    // Generate a new OTP and update timestamp
    $otp = rand(100000, 999999);
    $updateQuery = $pdo->prepare("UPDATE users SET otp = :otp, timestamp = :timestamp WHERE id_number = :id_number");
    $updateQuery->execute([
        'otp' => $otp,
        'timestamp' => $currentTime->format('Y-m-d H:i:s'),
        'id_number' => $id_number
    ]);

    // Initialize PHPMailer and configure SMTP
    $mail = new PHPMailer(true);
    try {
        // Enable verbose debug output for troubleshooting
        $mail->SMTPDebug = 2; // Set to 0 for production, 2 or 3 for debugging
        $mail->Debugoutput = 'html'; // Output format for debugging

        // SMTP server configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'phantomantonneil@gmail.com'; // Gmail address
        $mail->Password = 'kqcm juio hkke txye'; // Use an app-specific password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Use SSL encryption
        $mail->Port = 465;

        // Set the sender and recipient details
        $mail->setFrom('phantomantonneil@gmail.com', 'UVluate');
        $mail->addAddress($user['email'], $user['fname']);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body = "Your OTP is: <b>$otp</b>";

        // Send the email and confirm success
        $mail->send();
        echo json_encode(['status' => 'otp_required', 'message' => 'OTP sent successfully.']);
    } catch (Exception $e) {
        // Output error message for debugging
        echo json_encode(['status' => 'error', 'message' => 'OTP email could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid ID number or password.']);
}
?>
