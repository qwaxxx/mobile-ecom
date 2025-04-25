<?php
session_start();
include("api/conn.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login_page.php");
    exit;
}
$user_id = $_SESSION['user_id'];

// Handle form submission
if (isset($_POST['update'])) {
    $name = trim($_POST['name']);
    $contact = trim($_POST['contact']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $imageFileName = null;

    // Handle image upload
    if (!empty($_FILES['profile_image']['tmp_name'])) {
        $target_dir = "img/";
        $originalName = basename($_FILES["profile_image"]["name"]);
        $uniqueName = time() . '_' . $originalName;
        $target_file = $target_dir . $uniqueName;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $imageFileName = $uniqueName;
            } else {
                $_SESSION['error_message'] = "Sorry, there was an error uploading your file.";
            }
        } else {
            $_SESSION['error_message'] = "File is not a valid image.";
        }
    }

    // Build query based on what was provided
    $params = [$name, $email, $contact];
    $types = "sss";
    $query = "UPDATE users SET name = ?, email = ?, contact = ?";

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query .= ", password = ?";
        $params[] = $hashed_password;
        $types .= "s";
    }

    if ($imageFileName) {
        $query .= ", profile_image = ?";
        $params[] = $imageFileName;
        $types .= "s";
    }

    $query .= " WHERE id = ?";
    $params[] = $user_id;
    $types .= "i";

    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "✅ Profile updated successfully!";
    } else {
        $_SESSION['error_message'] = "❌ Error updating profile. Please try again.";
    }

    $stmt->close();
    header("Location: admin_profile.php");
    exit;
}

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
  <title>Profile</title>
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

 <?php include("admin_slidebar.php") ?>

 <?php include("admin_header.php") ?>

  <!-- [ Main Content ] start -->
  <div class="pc-container">
    <div class="pc-content">
    
    <div class="container pt-4">

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <img src="<?= $image_src ?>" alt="Profile" class="rounded-circle mb-3" width="120" height="120">
            <input type="file" name="profile_image" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($name) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($email) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Contact</label>
            <input type="text" name="contact" class="form-control" required value="<?= htmlspecialchars($contact) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">New Password <small class="text-muted">(Leave blank to keep current)</small></label>
            <input type="password" name="password" class="form-control">
        </div>

        <button type="submit" name="update" class="btn btn-primary">Update Profile</button>
    </form>
</div>

<!-- SweetAlert feedback -->
<?php if (isset($_SESSION['success_message'])): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Success',
    text: '<?= $_SESSION['success_message'] ?>',
    timer: 2000,
    showConfirmButton: false
});
</script>
<?php unset($_SESSION['success_message']); endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: '<?= $_SESSION['error_message'] ?>',
    timer: 2000,
    showConfirmButton: false
});
</script>
<?php unset($_SESSION['error_message']); endif; ?>

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