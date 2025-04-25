<?php
session_start();
include("api/conn.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if (isset($_POST['submit'])) {
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    $name = trim(mysqli_real_escape_string($conn, $_POST['name']));
    $email = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $contact = trim(mysqli_real_escape_string($conn, $_POST['contact']));
    $user_type = trim(mysqli_real_escape_string($conn, $_POST['user_type']));
    $password = $_POST['password'];
    $imageFileName = null;

    // ✅ Handle image upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $target_dir = "img/";
        $file_tmp = $_FILES["profile_image"]["tmp_name"];
        $original_name = basename($_FILES["profile_image"]["name"]);
        $imageFileType = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        $check = getimagesize($file_tmp);
        if ($check !== false && in_array($imageFileType, $allowed_types)) {
            $unique_name = uniqid('IMG_', true) . '.' . $imageFileType;
            $target_file = $target_dir . $unique_name;

            if (move_uploaded_file($file_tmp, $target_file)) {
                $imageFileName = $unique_name;
            } else {
                $_SESSION['error'] = "Sorry, there was an error uploading your image.";
                header("Location: register.php");
                exit;
            }
        } else {
            $_SESSION['error'] = "Invalid image type. Allowed: jpg, jpeg, png, gif, webp.";
            header("Location: register.php");
            exit;
        }
    }

    // ✅ Check for duplicate user
    $query = "SELECT * FROM users WHERE email = ? OR name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "User already exists!";
        header("Location: register.php");
        exit;
    }

    // ✅ Store user data in session
    $_SESSION['contact'] = $contact;
    $_SESSION['name'] = $name;
    $_SESSION['email'] = $email;
    $_SESSION['user_type'] = $user_type;
    $_SESSION['profile_image'] = $imageFileName;
    $_SESSION['hashed_password'] = password_hash($password, PASSWORD_DEFAULT);

    // ✅ Generate OTP
    $otp = rand(1000, 9999);
    $_SESSION['otp'] = $otp;

    // ✅ Send email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'remoterouter71@gmail.com';
        $mail->Password = 'dspkvdhaakctmgdu';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('remoterouter71@gmail.com', 'E-commerce');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Verification Code';
        $mail->Body = "<p>Hello <strong>$name</strong>,<br>Your verification code is <strong>" . implode(' ', str_split($otp)) . "</strong></p>";

        $mail->send();
        header("Location: register_otp_confirm.php");
        exit;
    } catch (Exception $e) {
        $_SESSION['error'] = "Mailer Error: {$mail->ErrorInfo}";
        header("Location: register.php");
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Register</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="Mantis is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
  <meta name="keywords" content="Mantis, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template">
  <meta name="author" content="CodedThemes">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="icon" href="asset/images/favicon.svg" type="image/x-icon"> 
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" id="main-font-link">
<link rel="stylesheet" href="asset/fonts/tabler-icons.min.css" >
<link rel="stylesheet" href="asset/fonts/feather.css" >
<link rel="stylesheet" href="asset/fonts/fontawesome.css" >
<link rel="stylesheet" href="asset/fonts/material.css" >
<link rel="stylesheet" href="asset/css/style.css" id="main-style-link" >
<link rel="stylesheet" href="asset/css/style-preset.css" >
</head>

<body>
  <!-- [ Pre-loader ] start -->
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>
  <!-- [ Pre-loader ] End -->

  <div class="auth-main">
    <div class="auth-wrapper v3">
      <div class="auth-form">
      
        <div class="card my-5">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-end mb-4">
              <h3 class="mb-0"><b>Register</b></h3>
              <a href="login_page.php" class="link-primary">Already have an account?</a>
            </div>
            <?php if (isset($_SESSION['error'])): ?>
              <script>
              Swal.fire({
                  icon: 'error',
                  title: 'Login Failed',
                  text: '<?= $_SESSION['error'] ?>',
                  confirmButtonColor: '#d33'
              });
              </script>
              <?php unset($_SESSION['error']); endif; ?>

              <?php if (isset($_SESSION['success'])): ?>
              <script>
              Swal.fire({
                  icon: 'success',
                  title: 'Success',
                  text: '<?= $_SESSION['success'] ?>',
                  confirmButtonColor: '#3085d6'
              });
              </script>
            <?php unset($_SESSION['success']); endif; ?>

            <form method="post" enctype="multipart/form-data">
              <div class="form-group mb-3">
                  <label class="form-label">Name</label>
                  <input required type="text" name="name" class="form-control" placeholder="Enter Name" required>
              </div>
              <div class="form-group mb-3">
                  <label class="form-label">Email</label>
                  <input required type="email" name="email" class="form-control" placeholder="Enter Email" required>
              </div>
              <div class="form-group mb-3">
                  <label class="form-label">Contact</label>
                  <input required type="text" name="contact" class="form-control" placeholder="Enter Contact" required>
              </div>
              <div class="form-group mb-3">
                  <label class="form-label">Picture</label>
                  <input required type="file" name="profile_image" class="form-control">
              </div>
              <div class="form-group mb-3">
                  <label class="form-label">User Type</label>
                  <select required name="user_type" class="form-control" required>
                      <option value="" disabled selected>Select type</option>
                      <option value="customer">Customer</option>
                      <option value="seller">Seller</option>
                  </select>
              </div>
              <div class="form-group mb-3">
                  <label class="form-label">Password</label>
                  <input required type="password" name="password" class="form-control" placeholder="Enter Password" required>
              </div>
              <div class="d-grid mt-4">
                  <button type="submit" name="submit" class="btn btn-primary">Register</button>
              </div>
          </form>

            </div>
        </div>
        
      </div>
    
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script src="asset/js/plugins/popper.min.js"></script>
  <script src="asset/js/plugins/simplebar.min.js"></script>
  <script src="asset/js/plugins/bootstrap.min.js"></script>
  <script src="asset/js/fonts/custom-font.js"></script>
  <script src="asset/js/pcoded.js"></script>
  <script src="asset/js/plugins/feather.min.js"></script>
  <script>layout_change('light');</script>
  <script>change_box_container('false');</script>
  <script>layout_rtl_change('false');</script>
  <script>preset_change("preset-1");</script>
  <script>font_change("Public-Sans");</script>
</body>
</html>