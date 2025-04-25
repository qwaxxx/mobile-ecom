
<?php
session_start();
include("api/conn.php");

// Redirect if not logged in or not an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login_page.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$update_success = false;
$delete_success = false;

// Handle update
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id']) && !isset($_POST['delete_user'])) {
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $user_type = trim($_POST['user_type']);
    $password = trim($_POST['password']);
    $profile_image = '';

    $has_image = isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0;
    $has_password = !empty($password);

    $query = "UPDATE users SET name=?, email=?, contact=?, user_type=?";
    $params = [$name, $email, $contact, $user_type];
    $types = "ssss";

    if ($has_password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query .= ", password=?";
        $params[] = $hashed_password;
        $types .= "s";
    }

    if ($has_image) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['profile_image']['type'], $allowed_types)) {
            $target_dir = "img/";
            $profile_image = uniqid() . "_" . basename($_FILES["profile_image"]["name"]);
            $target_file = $target_dir . $profile_image;

            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $query .= ", profile_image=?";
                $params[] = $profile_image;
                $types .= "s";
            }
        }
    }

    $query .= " WHERE id=?";
    $params[] = $id;
    $types .= "i";

    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param($types, ...$params);
        if ($stmt->execute()) {
            $update_success = true;
        }
        $stmt->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_user'])) {
  $delete_id = intval($_POST['delete_user']);
  $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
  $stmt->bind_param("i", $delete_id);
  if ($stmt->execute()) {
      $_SESSION['delete_success'] = true;
  } else {
      $_SESSION['delete_failed'] = true;
  }
  $stmt->close();
  header("Location: " . $_SERVER['PHP_SELF']);
  exit;
}


// Dashboard Stats
$total_users = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0];
$total_orders = $conn->query("SELECT COUNT(*) FROM billing_orders")->fetch_row()[0];
$total_products = $conn->query("SELECT COUNT(*) FROM products")->fetch_row()[0];

// Fetch current user info
$query = "SELECT name, email, profile_image FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $profile_image);
$stmt->fetch();
$stmt->close();

$image_src = $profile_image ? 'img/' . $profile_image : 'https://via.placeholder.com/150';
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Users</title>
  <!-- [Meta] -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="Mantis is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
  <meta name="keywords" content="Mantis, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template">
  <meta name="author" content="CodedThemes">

  <link rel="icon" href="asset/images/favicon.svg" type="image/x-icon"> 
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" id="main-font-link">
<link rel="stylesheet" href="asset/fonts/tabler-icons.min.css" >
<link rel="stylesheet" href="asset/fonts/feather.css" >
<link rel="stylesheet" href="asset/fonts/fontawesome.css" >
<link rel="stylesheet" href="asset/fonts/material.css" >
<link rel="stylesheet" href="asset/css/style.css" id="main-style-link" >
<link rel="stylesheet" href="asset/css/style-preset.css" >
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<!-- DataTables Responsive CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

<!-- DataTables Responsive JS -->
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>



</head>
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
    
      <!-- [ Main Content ] start -->
      <div class="row">
  
        <div class="card">
          <div class="card-body">
            <div class="table-responsive"> <!-- Added wrapper -->
              <table id="ordersTable" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Type</th>
                    <th>Image</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $users = $conn->query("SELECT id, name, email, contact, profile_image, user_type, password FROM users");
                  while ($row = $users->fetch_assoc()):
                      $img = $row['profile_image'] ? "img/" . $row['profile_image'] : "https://via.placeholder.com/50";
                  ?>
                  <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['contact']) ?></td>
                    <td><?= htmlspecialchars($row['user_type']) ?></td>
                    <td><img src="<?= $img ?>" height="40" class="rounded-circle"></td>
                    <td>
                      <!-- Edit Button -->
                      <button class="btn btn-warning btn-sm editBtn"
                        data-id="<?= $row['id'] ?>"
                        data-name="<?= htmlspecialchars($row['name']) ?>"
                        data-email="<?= htmlspecialchars($row['email']) ?>"
                        data-contact="<?= htmlspecialchars($row['contact']) ?>"
                        data-user_type="<?= htmlspecialchars($row['user_type']) ?>"
                        data-password="<?= htmlspecialchars($row['password']) ?>"
                        data-profile="<?= $img ?>">
                        <i class="fas fa-edit me-1"></i>
                      </button>
                      <!-- Delete Button -->
                      <form method="POST" class="d-inline deleteForm">
                        <input type="hidden" name="delete_user" value="<?= $row['id'] ?>">
                        <button type="button" class="btn btn-danger btn-sm deleteBtn">
                          <i class="fas fa-trash-alt me-1"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div> <!-- End table-responsive -->
          </div>
        </div>

    </div>
  </div>

  <!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" enctype="multipart/form-data">
      <div class="modal-header">
        <h5 class="modal-title">EDIT USER</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="modal_id">
        <div class="mb-3">
          <label>Name</label>
          <input type="text" class="form-control" name="name" id="modal_name" required>
        </div>
        <div class="mb-3">
          <label>Email</label>
          <input type="email" class="form-control" name="email" id="modal_email" required>
        </div>
        <div class="mb-3">
          <label>Contact</label>
          <input type="text" class="form-control" name="contact" id="modal_contact" required>
        </div>
        <div class="mb-3">
          <label class="form-label" for="modal_user_type">User Type</label>
          <select class="form-control" id="modal_user_type" name="user_type" required>
              <option disabled value="">Choose...</option>
              <option value="customer">Customer</option>
              <option value="seller">Seller</option>
              <option value="admin">Admin</option>
          </select>
        </div>
        <div class="mb-3">
          <label>Current Image</label><br>
          <img src="" id="modal_profile_img" height="70" class="rounded-circle mb-2"><br>
          <label>Change Image</label>
          <input type="file" class="form-control" name="profile_image">
        </div>
        <div class="mb-3">
          <label>Password</label>
          <input type="password" class="form-control" name="password" id="modal_password" placeholder="Leave blank to keep current password">
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Update</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

  <script>
function searchTable() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const rows = document.querySelectorAll("#ordersTable tbody tr");
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(input) ? "" : "none";
    });
}
</script>


  <script>

    $(document).ready(function() {
        $('#ordersTable').DataTable({
            responsive: true
        });
    });

    $(document).ready(function () {
        $('.editBtn').click(function () {
            $('#modal_id').val($(this).data('id'));
            $('#modal_name').val($(this).data('name'));
            $('#modal_email').val($(this).data('email'));
            $('#modal_contact').val($(this).data('contact'));
            $('#modal_user_type').val($(this).data('user_type'));
            $('#modal_password').val('');
            $('#modal_profile_img').attr('src', $(this).data('profile'));
            $('#editModal').modal('show');
        });

        $('.deleteBtn').click(function () {
            const form = $(this).closest('form');
            Swal.fire({
                title: 'Are you sure?',
                text: "This will delete the user permanently!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Yes, delete!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        <?php if ($update_success): ?>
        Swal.fire({
            icon: 'success',
            title: 'Updated!',
            text: 'User info has been successfully updated.',
            confirmButtonColor: '#3085d6'
        });
        <?php endif; ?>

        <?php if (isset($_SESSION['delete_success'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Deleted!',
                text: 'The user has been deleted.',
                confirmButtonColor: '#3085d6'
            });
            <?php unset($_SESSION['delete_success']); ?>
        <?php elseif (isset($_SESSION['delete_failed'])): ?>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Failed to delete the user!',
                confirmButtonColor: '#d33'
            });
            <?php unset($_SESSION['delete_failed']); ?>
        <?php endif; ?>

    });
</script>

  <!-- jQuery (if not already included) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

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