<?php
session_start();
include("api/conn.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if (isset($_POST['submit'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Please enter a valid email address.";
        header("Location: forgotpassword.php");
        exit;
    }

    // Check if the email exists
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        $_SESSION['error_message'] = "Database error: Unable to prepare statement.";
        header("Location: forgotpassword.php");
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
            $_SESSION['error_message'] = "Database error: Could not store token.";
            header("Location: forgotpassword.php");
            exit;
        }

        $stmt->bind_param("sss", $email, $token, $expires);
        $stmt->execute();

        // Secure reset link
        $safeEmail = urlencode($email);
        $resetLink = "https://localhost/e-commerce_jelai/forgot_password_otp_confirm.php?token=$token&email=$safeEmail";

        // Send email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'remoterouter71@gmail.com';
            $mail->Password = 'dspkvdhaakctmgdu'; // 🔒 Consider using an app password
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
            $_SESSION['success_message'] = "A password reset link has been sent to your email.";
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $_SESSION['error_message'] = "No user found with that email address.";
    }

    header("Location: forgotpassword.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Forgot Password</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="asset/css/style.css">
</head>
<body>

<div class="auth-main">
  <div class="auth-wrapper v3">
    <div class="auth-form">
      <div class="card my-5">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-end mb-4">
            <h3 class="mb-0"><b>Forgot Password</b></h3>
            <a href="login_page.php" class="link-primary">Back to Login</a>
          </div>

          <?php if (isset($_SESSION['error_message'])): ?>
            <script>
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?= $_SESSION['error_message'] ?>',
                confirmButtonColor: '#d33'
              });
            </script>
            <?php unset($_SESSION['error_message']); ?>
          <?php endif; ?>

          <?php if (isset($_SESSION['success_message'])): ?>
            <script>
              Swal.fire({
                icon: 'success',
                title: 'Success!',
                html: '<?= $_SESSION['success_message'] ?>',
                confirmButtonColor: '#3085d6'
              });
            </script>
            <?php unset($_SESSION['success_message']); ?>
          <?php endif; ?>

          <form method="POST" action="forgotpassword.php">
            <div class="form-group mb-3">
              <label class="form-label">Email Address</label>
              <input type="email" name="email" class="form-control" required placeholder="Enter your email">
            </div>
            <div class="d-grid mt-4">
              <button type="submit" name="submit" class="btn btn-primary">Send Password Reset Email</button>
            </div>
          </form>
        </div>
      </div>

      <div class="auth-footer row">
        <div class="col my-1">
          <p class="m-0">Copyright © 2025</p>
        </div>
        <div class="col-auto my-1">
          <ul class="list-inline footer-link mb-0">
            <li class="list-inline-item"><a href="#">E-commerce</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="asset/js/plugins/popper.min.js"></script>
<script src="asset/js/plugins/bootstrap.min.js"></script>
</body>
</html>
