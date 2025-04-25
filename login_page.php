<?php
session_start();
$cart = $_SESSION['cart'] ?? [];

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Login</title>
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
              <h3 class="mb-0"><b>Login</b></h3>
              <a href="register.php" class="link-primary">Don't have an account?</a>
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

            <form  method="POST" action="login_process.php">
                <div class="form-group mb-3">
                <label class="form-label">Email Address</label>
                <input required type="email" name="email" class="form-control" value="<?php echo isset($_COOKIE['email']) ? $_COOKIE['email'] : ''; ?>" placeholder="Email Address">
                </div>
                <div class="form-group mb-3">
                <label class="form-label">Password</label>
                <input required type="password" name="password" class="form-control" value="<?php echo isset($_COOKIE['password']) ? $_COOKIE['password'] : ''; ?>" placeholder="Password">
                </div>
                <div class="d-flex mt-1 justify-content-between">
                <div class="form-check">
                    <input class="form-check-input input-primary"  type="checkbox" name="remember" checked="">
                    <label class="form-check-label text-muted" for="checkbox">Keep me sign in</label>
                </div>
                <a href="forgotpassword.php" class="link-danger">Forgot Password?</a>
                </div>
                <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">Login</button>
                <br>
                <a href="./" class="btn btn-warning">Shop Now</a>

                </div>
            </form>
          </div>
        </div>
        <div class="auth-footer row">
          <!-- <div class=""> -->
            <div class="col my-1">
              <p class="m-0">Copyright © <a href=""> 2025</a></p>
            </div>
            <div class="col-auto my-1">
              <ul class="list-inline footer-link mb-0">
                <li class="list-inline-item"><a href="">E-commerce</a></li>
              </ul>
            </div>
          <!-- </div> -->
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