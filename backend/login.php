<?php
session_start();
include 'cors.php';
require 'database_connection.php';
require 'vendor/autoload.php'; // If using Composer, or specify PHPMailer paths manually

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$data = json_decode(file_get_contents("php://input"));
$id_number = $data->id_number;
$password = $data->password;

// Query to check user credentials
$query = $pdo->prepare("SELECT * FROM users WHERE id_number = :id_number");
$query->execute(['id_number' => $id_number]);
$user = $query->fetch();

if ($user && password_verify($password, $user['password'])) {
    // Start session and store user info
    $_SESSION['user_id'] = $user['id_number'];
    $_SESSION['usertype'] = $user['usertype'];

    // Generate OTP and save it in the database
    $otp = rand(100000, 999999);
    $updateQuery = $pdo->prepare("UPDATE users SET otp = :otp WHERE id_number = :id_number");
    $updateQuery->execute(['otp' => $otp, 'id_number' => $id_number]);

    // Send OTP email with PHPMailer
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'phantomantonneil@gmail.com'; // SMTP username
        $mail->Password   = 'kqcm juio hkke txye';    // SMTP password (consider using an app-specific password for Gmail)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Use SSL encryption
        $mail->Port       = 465; // Port for SSL
    
            // Recipients
        $mail->setFrom('phantomantonneil@gmail.com', 'UVluate');
        $mail->addAddress($user['email'], $user['fname']);
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = "Your OTP is: <b>$otp</b>";
        $mail->send();

        echo json_encode(['status' => 'otp_required', 'usertype' => $user['usertype']]);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'OTP email could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid ID number or password.']);
}
?>
