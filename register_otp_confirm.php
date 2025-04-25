<?php
session_start();
include("api/conn.php");

if (isset($_POST['submit'])) {
    $otp = trim($_POST['otp']);

    if (isset($_SESSION['otp']) && $otp == $_SESSION['otp']) {
        if (
            isset($_SESSION['contact'], $_SESSION['name'], $_SESSION['email'],
            $_SESSION['hashed_password'], $_SESSION['user_type'])
        ) {
            $name           = $_SESSION['name'];
            $email          = $_SESSION['email'];
            $hashed_password= $_SESSION['hashed_password'];
            $contact        = $_SESSION['contact'];
            $user_type      = $_SESSION['user_type'];
            $profile_image  = $_SESSION['profile_image'] ?? null;

            $query = "INSERT INTO users (name, email, password, contact, user_type, profile_image, create_on)
                      VALUES (?, ?, ?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($query);
            if (!$stmt) {
                $_SESSION['error'] = "Database error: " . $conn->error;
                header("Location: register_otp_confirm.php");
                exit;
            }

            $stmt->bind_param("ssssss", $name, $email, $hashed_password, $contact, $user_type, $profile_image);

            if ($stmt->execute()) {
                unset(
                    $_SESSION['otp'],
                    $_SESSION['name'],
                    $_SESSION['email'],
                    $_SESSION['contact'],
                    $_SESSION['user_type'],
                    $_SESSION['hashed_password'],
                    $_SESSION['profile_image']
                );

                $_SESSION['success'] = "🎉 Registration successful! You can now log in.";
                header("Location: login_page.php");
                exit;
            } else {
                $_SESSION['error'] = "❌ Registration failed. Please try again.";
                header("Location: register_otp_confirm.php");
                exit;
            }

        } else {
            $_SESSION['error'] = "Session expired or missing data. Please register again.";
            header("Location: register.php");
            exit;
        }

    } else {
        $_SESSION['error'] = "⚠️ Invalid OTP. Please check your email and try again.";
        header("Location: register_otp_confirm.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Code Verification</title>
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
          <h3 class="mb-3"><b>Enter Verification Code</b></h3>

          <?php if (isset($_SESSION['error'])): ?>
            <script>
              Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: '<?= $_SESSION['error'] ?>',
                  confirmButtonColor: '#d33'
              });
            </script>
            <?php unset($_SESSION['error']); ?>
          <?php endif; ?>

          <?php if (isset($_SESSION['success'])): ?>
            <script>
              Swal.fire({
                  icon: 'success',
                  title: 'Success',
                  text: '<?= $_SESSION['success'] ?>',
                  confirmButtonColor: '#3085d6'
              });
            </script>
            <?php unset($_SESSION['success']); ?>
          <?php endif; ?>

          <!-- ✅ OTP Form -->
          <form method="POST" id="otpForm">
            <div class="row text-center">
              <div class="col"><input type="text" id="otp1" maxlength="1" class="form-control otp-input" required></div>
              <div class="col"><input type="text" id="otp2" maxlength="1" class="form-control otp-input" required></div>
              <div class="col"><input type="text" id="otp3" maxlength="1" class="form-control otp-input" required></div>
              <div class="col"><input type="text" id="otp4" maxlength="1" class="form-control otp-input" required></div>
            </div>
            <input type="hidden" name="otp" id="combinedOtp">
            <div class="d-grid mt-4">
              <button type="submit" name="submit" class="btn btn-primary">Continue</button>
            </div>
          </form>

          <div class="text-center mt-3">
            <p>Didn't receive the code? <a href="#">Resend Code</a></p>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<!-- JS to combine OTP -->
<script>
  document.querySelectorAll('.otp-input').forEach((input, index, inputs) => {
    input.addEventListener('input', () => {
      if (input.value.length === 1 && index < inputs.length - 1) {
        inputs[index + 1].focus();
      }
    });
  });

  document.getElementById('otpForm').addEventListener('submit', function (e) {
    let otp = '';
    for (let i = 1; i <= 4; i++) {
      otp += document.getElementById('otp' + i).value;
    }
    document.getElementById('combinedOtp').value = otp;
  });
</script>

</body>
</html>
