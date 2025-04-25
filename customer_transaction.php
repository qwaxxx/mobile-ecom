<?php
session_start();
include("api/conn.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: login_page.php");
    exit;
}
$user_id = $_SESSION['user_id'];

// Fetch current user info
$query = "SELECT name, email, contact, profile_image FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $contact, $profile_image);
$stmt->fetch();
$stmt->close();

$image_src = $profile_image ? 'img/' . $profile_image : 'https://via.placeholder.com/150';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Transaction</title>
  <!-- [Meta] -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="Mantis is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
  <meta name="keywords" content="Mantis, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template">
  <meta name="author" content="CodedThemes">

  <!-- [Favicon] icon -->
  <link rel="icon" href="asset/images/favicon.svg" type="image/x-icon"> <!-- [Google Font] Family -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" id="main-font-link">
<!-- [Tabler Icons] https://tablericons.com -->
<link rel="stylesheet" href="asset/fonts/tabler-icons.min.css" >
<!-- [Feather Icons] https://feathericons.com -->
<link rel="stylesheet" href="asset/fonts/feather.css" >
<!-- [Font Awesome Icons] https://fontawesome.com/icons -->
<link rel="stylesheet" href="asset/fonts/fontawesome.css" >
<!-- [Material Icons] https://fonts.google.com/icons -->
<link rel="stylesheet" href="asset/fonts/material.css" >
<!-- [Template CSS Files] -->
<link rel="stylesheet" href="asset/css/style.css" id="main-style-link" >
<link rel="stylesheet" href="asset/css/style-preset.css" >

</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
  <!-- [ Pre-loader ] start -->
<div class="loader-bg">
  <div class="loader-track">
    <div class="loader-fill"></div>
  </div>
</div>
<!-- [ Pre-loader ] End -->

 <?php include("customer_slidebar.php") ?>

 <?php include("customer_header.php") ?>

  <!-- [ Main Content ] start -->
  <div class="pc-container">
    <div class="pc-content">
    

    </div>
  </div>
  <!-- [ Main Content ] end -->

  <?php include("customer_footer.php") ?>
  
  <script src="asset/js/plugins/apexcharts.min.js"></script>
  <script src="asset/js/pages/dashboard-default.js"></script>
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