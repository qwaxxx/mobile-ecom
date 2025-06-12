<?php
header('Content-Type: application/json');
include("conn.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = "Please enter a valid email address.";
        echo json_encode($response);
        exit;
    }

    // Check if the email exists
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        $response['message'] = "Database error: Unable to prepare statement.";
        echo json_encode($response);
        exit;
    }

    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate reset token
        $token = bin2hex(random_bytes(32));
        $expires = date("U") + 1800; // 30 mins

        // Insert/update token
        $query = "INSERT INTO password_resets (email, token, expires)
                  VALUES (?, ?, ?)
                  ON DUPLICATE KEY UPDATE token = VALUES(token), expires = VALUES(expires)";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            $response['message'] = "Database error: Could not store token.";
            echo json_encode($response);
            exit;
        }

        $stmt->bind_param("sss", $email, $token, $expires);
        $stmt->execute();

        // Secure reset link
        $safeEmail = urlencode($email);
        $resetLink = "http://localhost/mobile-ecom//forgot_password_otp_confirm.html?token=$token&email=$safeEmail";

        // Send email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'remoterouter71@gmail.com';
            $mail->Password = 'dspkvdhaakctmgdu';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('remoterouter71@gmail.com', 'Password Recovery');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "
                <p>Hello,</p>
                <p>You requested a password reset. Click the link below to reset your password:</p>
                <p><a href='$resetLink'>$resetLink</a></p>
                <p>If you did not request this, please ignore this email.</p>
            ";

            $mail->send();
            $response['success'] = true;
            $response['message'] = "A password reset link has been sent to your email.";
        } catch (Exception $e) {
            $response['message'] = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $response['message'] = "No user found with that email address.";
    }
} else {
    $response['message'] = "Invalid request method.";
}

echo json_encode($response);
exit;
