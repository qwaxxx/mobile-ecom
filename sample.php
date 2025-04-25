<?php
session_start();
include("api/conn.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
  header("Location: login_page.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
  $user_id = (int)$_SESSION['user_id'];
  $name = $_POST['prod_name'];
  $price = (float)$_POST['prod_price'];
  $stock = (int)$_POST['prod_stock'];
  $description = $_POST['prod_description'];

  $image_name = $_FILES['prod_picture']['name'];
  $image_tmp = $_FILES['prod_picture']['tmp_name'];
  $target_dir = "uploads/";
  $target_file = $target_dir . basename($image_name);

  if (move_uploaded_file($image_tmp, $target_file)) {
    $stmt = $conn->prepare("INSERT INTO products (prod_name, prod_stock, prod_description, prod_price, prod_picture, user_id) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
      die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    $stmt->bind_param("sisdsi", $name, $stock, $description, $price, $target_file, $user_id);

    if ($stmt->execute()) {
      header("Location: seller_dashboard.php?success=1");
      exit;
    } else {
      $upload_error = "Error: " . $stmt->error;
    }

    $stmt->close();
  } else {
    $upload_error = "Image upload failed.";
  }
}

$user_id = (int)$_SESSION['user_id'];
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
  <title>Dashboard</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="Mantis is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
  <meta name="keywords" content="Mantis, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template">
  <meta name="author" content="CodedThemes">

  <link rel="icon" href="asset/images/favicon.svg" type="image/x-icon">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" id="main-font-link">
  <link rel="stylesheet" href="asset/fonts/tabler-icons.min.css">
  <link rel="stylesheet" href="asset/fonts/feather.css">
  <link rel="stylesheet" href="asset/fonts/fontawesome.css">
  <link rel="stylesheet" href="asset/fonts/material.css">
  <link rel="stylesheet" href="asset/css/style.css" id="main-style-link">
  <link rel="stylesheet" href="asset/css/style-preset.css">

</head>

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
  <!-- [ Pre-loader ] start -->
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>
  <!-- [ Pre-loader ] End -->

  <?php include("seller_slidebar.php") ?>

  <?php include("seller_header.php") ?>

  <!-- [ Main Content ] start -->
  <div class="pc-container">
    <div class="pc-content">

      <!-- [ Main Content ] start -->
      <div class="row">
        <!-- [ sample-page ] start -->
        <?php
        // Assuming you already have a $conn variable for the database connection
        $totalUsers = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
        $totalAddcarts = $conn->query("SELECT COUNT(*) as count FROM addcarts")->fetch_assoc()['count'];
        $totalProducts = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
        $totalNotifications = $conn->query("SELECT COUNT(*) as count FROM notifications")->fetch_assoc()['count'];
        ?>

        <!-- Users -->
        <div class="col-md-6 col-xl-3">
          <div class="card text-white bg-info">
            <div class="card-body d-flex align-items-center">
              <div class="me-3">
                <i class="fas fa-users fa-2x"></i>
              </div>
              <div>
                <h6 class="mb-1 text-white-50">Total Users</h6>
                <h4 class="mb-0"><?= number_format($totalUsers) ?></h4>
              </div>
            </div>
          </div>
        </div>

        <!-- Add to Carts -->
        <div class="col-md-6 col-xl-3">
          <div class="card text-white bg-warning">
            <div class="card-body d-flex align-items-center">
              <div class="me-3">
                <i class="fas fa-shopping-cart fa-2x"></i>
              </div>
              <div>
                <h6 class="mb-1 text-white-50">Total AddCarts</h6>
                <h4 class="mb-0"><?= number_format($totalAddcarts) ?></h4>
              </div>
            </div>
          </div>
        </div>

        <!-- Products -->
        <div class="col-md-6 col-xl-3">
          <div class="card text-white bg-danger">
            <div class="card-body d-flex align-items-center">
              <div class="me-3">
                <i class="fas fa-box-open fa-2x"></i>
              </div>
              <div>
                <h6 class="mb-1 text-white-50">Total Products</h6>
                <h4 class="mb-0"><?= number_format($totalProducts) ?></h4>
              </div>
            </div>
          </div>
        </div>

        <!-- Notifications -->
        <div class="col-md-6 col-xl-3">
          <div class="card text-white bg-secondary">
            <div class="card-body d-flex align-items-center">
              <div class="me-3">
                <i class="fas fa-bell fa-2x"></i>
              </div>
              <div>
                <h6 class="mb-1 text-white-50">Total Notifications</h6>
                <h4 class="mb-0"><?= number_format($totalNotifications) ?></h4>
              </div>
            </div>
          </div>
        </div>
        <!-- [ sample-page ] end -->

        <!-- Header Section: Add Product + Search -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
        <style>
          .btn:focus {
            box-shadow: none !important;
            outline: none !important;
          }

          .fixed-btn {
            height: 38px;
            min-width: 150px;
          }
        </style>
        <button type="button"
                class="btn btn-primary mb-2 mb-md-0 fixed-btn"
                data-bs-toggle="modal"
                data-bs-target="#addproduct">
          + Add product
        </button>

          <form class="d-flex" role="search">
            <input class="form-control form-control-sm" type="search" placeholder="Search" id="search" aria-label="Search">
          </form>
        </div>

        <!-- Card Section -->
        <div class="card">
          <div class="card-body p-0">

            <!-- Navbar for Filters -->
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary px-3">
              <span class="navbar-brand text-white">Categories:</span>

              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#filterNavbar"
                aria-controls="filterNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>

              <div class="collapse navbar-collapse" id="filterNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                  <!-- <li class="nav-item">
                    <a class="nav-link active" id="showAll" href="#">All</a>
                  </li> -->
                  <li class="nav-item">
                    <select class="form-select ms-2" id="price_range" name="price_range" style="width: auto;">
                      <option value="">Select price range</option>
                      <option value="0-10000">₱0 - ₱10,000</option>
                      <option value="10001-20000">₱10,001 - ₱20,000</option>
                      <option value="20001-30000">₱20,001 - ₱30,000</option>
                      <option value="30001-130000">₱30,001 - ₱130,000</option>
                    </select>
                  </li>
                </ul>
              </div>
            </nav>

            <!-- Product Container -->
            <div class="table-responsive" style="height: 700px; overflow-y: auto;">
              <div id="productContainer" class="p-3">
                <!-- Products will be shown here -->
                <!-- Example product card (you can duplicate and populate dynamically) -->
                <!--
                <div class="card mb-2 product-item">
                  <div class="card-body">
                    Product Name - ₱Price
                  </div>
                </div>
                -->
              </div>
            </div>

          </div>

          <!-- Pagination -->
          <div class="d-flex justify-content-center mt-3 mb-3">
            <nav>
              <ul class="pagination" id="pagination"></ul>
            </nav>
          </div>
        </div>
        
      </div>
    </div>
  </div>
  <!-- [ Main Content ] end -->

  <!-- Modal -->
  <?php
  // Query to fetch products
  $sql = "SELECT * FROM products";
  $result = $conn->query($sql);

  if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
      <?php $modalId = "productModal" . $row['prod_id'];
      ?>
      <div class="modal fade" id="<?= $modalId; ?>" tabindex="1" role="dialog" aria-labelledby="updateProductLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <form method="POST" action="seller_update_product.php">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="updateProductLabel">Update Product</h5>
                <!-- <button type="button" class="close" data-mdb-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button> -->
              </div>
              <div class="modal-body">
                <input type="hidden" name="prod_id" value="<?= $row['prod_id']; ?>">
                <div class="form-group">
                  <label>Product Name</label>
                  <input type="text" name="prod_name" class="form-control" value="<?= $row['prod_name']; ?>" required>
                </div>
                <div class="form-group">
                  <label>Description</label>
                  <textarea name="prod_description" class="form-control" required><?= $row['prod_description']; ?></textarea>
                </div>
                <div class="form-group">
                  <label>Stock</label>
                  <input type="number" name="prod_stock" class="form-control" value="<?= $row['prod_stock']; ?>" required>
                </div>
                <div class="form-group">
                  <label>Price</label>
                  <input type="text" name="prod_price" class="form-control" value="<?= $row['prod_price']; ?>" required>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary mb-2 mb-md-0 fixed-btn">Update Product</button>
                <button type="button" class="btn btn-secondary mb-2 mb-md-0 fixed-btn" data-mdb-dismiss="modal">Cancel</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p class="text-center">No products available.</p>
  <?php endif;
  $conn->close();

  ?>
  <div class="modal fade" id="addproduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Items to Sell</h5>
          <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <form action="seller_uploadprod.php" method="POST" enctype="multipart/form-data" class="p-4 border rounded shadow-sm">
            <div class="form-group">
              <label for="prod_name">Product Name</label>
              <input type="text" name="prod_name" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="prod_price">Price</label>
              <input type="number" name="prod_price" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="prod_stock">Stock</label>
              <input type="number" name="prod_stock" class="form-control" required>
            </div>

            <div class="form-group">
              <label for="prod_description">Description</label>
              <textarea name="prod_description" class="form-control" rows="3" required></textarea>
            </div>

            <div class="form-group">
              <label for="prod_picture">Product Image</label>
              <input type="file" name="prod_picture" class="form-control-file" accept="image/*" required>
            </div>

          </form>

        </div>
        <div class="modal-footer">
          <button type="submit" name="submit" class="btn btn-primary mb-2 mb-md-0 fixed-btn">Add Product</button>
          <a href="" class="btn btn-secondary mb-2 mb-md-0 fixed-btn" >Close</a>
        </div>
      </div>
    </div>
  </div>
  <!-- JQuery -->
  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>

  <script>
    $(document).ready(function() {
      loadProducts();

      $('#search').on('keyup', function() {
        loadProducts($('#search').val(), $('#price_range').val(), 1);
      });

      $('#price_range').on('change', function() {
        loadProducts($('#search').val(), $('#price_range').val(), 1);
      });

      $('#showAll').on('click', function(e) {
        e.preventDefault();
        $('#search').val('');
        $('#price_range').val('');
        loadProducts();
      });

      // Handle pagination click (delegate since it’s loaded via AJAX)
      $(document).on('click', '.pagination-link', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        loadProducts($('#search').val(), $('#price_range').val(), page);
      });

      function loadProducts(search = '', price = '', page = 1) {
        $.ajax({
          url: 'seller_fetch_products.php',
          method: 'POST',
          data: {
            search: search,
            price: price,
            page: page
          },
          success: function(data) {
            $('#productContainer').html(data);
          }
        });
      }
    });
  </script>

  <?php include("seller_footer.php") ?>

  <script>
    function loadNotifications(all = false) {
      const url = all ?
        'fetch_notification.php?all=1' :
        'fetch_notification.php';

      fetch(url)
        .then(res => res.json())
        .then(data => {
          const badge = document.getElementById("notificationBadge");
          const dd = document.getElementById("notificationList");

          // 1) Update badge with unread_count
          badge.textContent = data.unread_count;
          badge.style.display = data.unread_count ?
            'inline-block' :
            'none';

          // 2) Build dropdown list of all notifications
          dd.innerHTML = '';
          if (data.notifications.length === 0) {
            dd.innerHTML = '<li><a class="dropdown-item text-muted" href="#">No notifications</a></li>';
            return;
          }

          data.notifications.forEach(n => {
            const readClass = n.status === 'unread' ? 'fw-bold' : '';
            const li = document.createElement('li');
            li.innerHTML = `
          <a class="dropdown-item ${readClass}"
             href="seller_transaction.php?searchInput=${n.addcart_id}"
             onclick="handleNotificationClick(${n.id}, ${n.addcart_id})">
            ${n.message}
          </a>`;
            dd.appendChild(li);
          });

          // 3) “See more” if we hit the 10‑item limit
          if (data.notifications.length > 10 && !all) {
            const more = document.createElement('li');
            more.innerHTML = `
          <a class="dropdown-item text-primary fw-bold"
             href="#"
             onclick="loadNotifications(true)">
            See more
          </a>`;
            dd.appendChild(more);
          }
        });
    }
    document.addEventListener("DOMContentLoaded", () => loadNotifications());
  </script>

  <script src="asset/js/plugins/apexcharts.min.js"></script>
  <script src="asset/js/pages/dashboard-default.js"></script>
  <script src="asset/js/plugins/popper.min.js"></script>
  <script src="asset/js/plugins/simplebar.min.js"></script>
  <script src="asset/js/plugins/bootstrap.min.js"></script>
  <script src="asset/js/fonts/custom-font.js"></script>
  <script src="asset/js/pcoded.js"></script>
  <script src="asset/js/plugins/feather.min.js"></script>
  <script>
    layout_change('light');
  </script>
  <script>
    change_box_container('false');
  </script>
  <script>
    layout_rtl_change('false');
  </script>
  <script>
    preset_change("preset-1");
  </script>
  <script>
    font_change("Public-Sans");
  </script>

</body>

</html>