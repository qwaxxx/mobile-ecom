<?php
session_start();
include("api/conn.php");

// Step 1: Validate token and email
if (isset($_GET['token']) && isset($_GET['email'])) {
    $token = htmlspecialchars($_GET['token']);
    $email = htmlspecialchars($_GET['email']);

    $query = "SELECT * FROM password_resets WHERE email = ? AND token = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Database error: " . $conn->error);
    }
    $stmt->bind_param('ss', $email, $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error'] = "Invalid or expired reset link.";
        header("Location: login_page.php");
        exit;
    } else {
        $row = $result->fetch_assoc();
        if ($row['expires'] < time()) {
            $_SESSION['error'] = "This reset link has expired.";
            header("Location: login_page.php");
            exit;
        }
    }
} else {
    $_SESSION['error'] = "New Password has been now updated.";
    header("Location: login_page.php");
    exit;
}

// Step 2: Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset_password'])) {
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_POST['email'];
    $token = $_POST['token'];

    if ($new_password !== $confirm_password) {
        $_SESSION['error_message'] = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $update_query = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($update_query);
        if (!$stmt) {
            die("Database error: " . $conn->error);
        }
        $stmt->bind_param('ss', $hashed_password, $email);
        $stmt->execute();

        // Delete used token
        $delete_query = "DELETE FROM password_resets WHERE email = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param('s', $email);
        $stmt->execute();

        $_SESSION['success'] = "Password reset successful! You can now log in.";
        header("Location: login_page.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Set New Password</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="asset/css/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="auth-main">
  <div class="auth-wrapper v3">
    <div class="auth-form">
      <div class="card my-5">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-end mb-4">
            <h3 class="mb-0"><b>Set New Password</b></h3>
            <a href="login_page.php" class="link-primary">Back to Login</a>
          </div>

          <form method="post">
            <input type="hidden" name="email" value="<?= htmlspecialchars($_GET['email']) ?>">
            <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token']) ?>">

            <div class="form-group mb-3">
              <label class="form-label">New Password</label>
              <input required type="password" name="password" class="form-control" placeholder="Enter New Password">
            </div>
            <div class="form-group mb-3">
              <label class="form-label">Confirm Password</label>
              <input required type="password" name="confirm_password" class="form-control" placeholder="Confirm Password">
            </div>

            <div class="d-grid mt-4">
              <button type="submit" name="reset_password" class="btn btn-primary">Reset Password</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- SweetAlert for Errors -->
<?php if (isset($_SESSION['error_message'])): ?>
<script>
Swal.fire({
  icon: 'error',
  title: 'Error',
  text: '<?= $_SESSION['error_message'] ?>',
  confirmButtonColor: '#d33'
});
</script>
<?php unset($_SESSION['error_message']); endif; ?>

<!-- SweetAlert for Success -->
<?php if (isset($_SESSION['success_message'])): ?>
<script>
Swal.fire({
  icon: 'success',
  title: 'Success',
  text: '<?= $_SESSION['success'] ?>',
  confirmButtonColor: '#3085d6'
});
</script>
<?php unset($_SESSION['success_message']); endif; ?>

</body>
</html>
