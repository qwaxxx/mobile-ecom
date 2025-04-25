<?php
session_start();
include("api/conn.php");

// Access control
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
  header("Location: login_page.php");
  exit;
}

// Upload product logic (moved to top of file)
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

// Fetch seller info
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
  <!-- [Meta] -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="Mantis is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
  <meta name="keywords" content="Mantis, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template">
  <meta name="author" content="CodedThemes">

  <!-- [Favicon] icon -->
  <link rel="icon" href="asset/images/favicon.svg" type="image/x-icon">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" id="main-font-link">
  <link rel="stylesheet" href="asset/fonts/tabler-icons.min.css">
  <link rel="stylesheet" href="asset/fonts/feather.css">
  <link rel="stylesheet" href="asset/fonts/fontawesome.css">
  <link rel="stylesheet" href="asset/fonts/material.css">
  <link rel="stylesheet" href="asset/css/style.css" id="main-style-link">
  <link rel="stylesheet" href="asset/css/style-preset.css">
  <link rel="stylesheet" href="asset/css/style.css">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <!-- Material Design Bootstrap -->
  <link href="css/mdb.min.css" rel="stylesheet">
  <!-- Your custom styles (optional) -->
  <link href="css/style.min.css" rel="stylesheet">
  <style>
    .modal1 {
      display: block !important;
      /* just for testing */
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
    }
  </style>
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
        $totalAddcarts = $conn->query("SELECT COUNT(*) as count FROM addcarts where addcart_seller_id = $user_id")->fetch_assoc()['count'];
        $totalProducts = $conn->query("SELECT COUNT(*) as count FROM products where prod_user_id = $user_id")->fetch_assoc()['count'];
        $totalNotifications = $conn->query("SELECT COUNT(*) as count FROM notifications where user_id =$user_id")->fetch_assoc()['count'];
        ?>


        <!-- Add to Carts -->
        <div class="col-md-6 col-xl-4">
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
        <div class="col-md-6 col-xl-4">
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
        <div class="col-md-6 col-xl-4">
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
      </div>

      <!-- [ sample-page ] end -->

      <div class="container">
        <!--Navbar-->
        <nav class="navbar navbar-expand-lg navbar-dark mdb-color lighten-3 mt-3 mb-5">

          <span class="navbar-brand">Categories:</span>

          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#basicExampleNav"
            aria-controls="basicExampleNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="basicExampleNav">

            <ul class="navbar-nav mr-auto">
              <li class="nav-item active">
                <a class="nav-link" id="showAll" href="#">All
                  <span class="sr-only">(current)</span>
                </a>
              </li>

              <li class="nav-item">
                <select class="form-control nav-link lighten-3" name="price_range" id="price_range" style="color:aqua;height:42px;margin-left:2px">
                  <option value="">Select price range</option>
                  <option value="0-10000">₱0 - ₱10000</option>
                  <option value="10001-20000">₱10001 - ₱20000</option>
                  <option value="20001-30000">₱20001 - ₱30000</option>
                  <option value="30001-130000">₱30001 - ₱130000</option>
                </select>
              </li>

            </ul>
            <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addproduct">+ Add product
              <span class="sr-only">+ Add product</span>
            </a>
            <form class="form-inline ml-auto">
              <div class="md-form my-0">
                <input class="form-control form-control-sm" type="text" placeholder="Search" id="search" aria-label="Search">
              </div>
            </form>
          </div>
        </nav>

        <div id="productContainer"></div>
      </div>

      <!-- Pagination -->
      <div class="d-flex justify-content-center mt-3">
        <nav>
          <ul class="pagination" id="pagination"></ul>
        </nav>
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
      <div class="modal fade" id="<?= $modalId; ?>" tabindex="1">
        <div class="modal-dialog" role="document">
          <form method="POST" action="seller_update_product.php">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="updateProductLabel">Update Product</h5>
                <button type="button" class="close btn" data-bs-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
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
                <button type="submit" class="btn btn-primary">Update Product</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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

            <button type="submit" name="submit" class="btn btn-primary">Add Product</button>
          </form>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
    <!-- Product Item -->


  </div>
  <!-- JQuery -->

  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>


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
  <?php include("customer_footer.php") ?>

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

    // Variable to track the last time a notification was shown
    var lastNotificationTime = 0;

    function getNotification() {
      // Check if the browser supports notifications
      if (!("Notification" in window)) {
        $('body').append('<h4 style="color:red">*Browser does not support Web Notification</h4>');
        return;
      }

      // If permission is not granted, request it
      if (Notification.permission !== "granted") {
        Notification.requestPermission();
      } else {
        // Get the current time
        var currentTime = Date.now();

        // Check if 2 minutes have passed since the last notification
        if (currentTime - lastNotificationTime >= 120000) { // 120000 ms = 2 minutes
          $.ajax({
            url: "fetch_notification.php",
            type: "POST",
            success: function(response) {
              if (response.result === true) {
                var notificationDetails = response.notifications;
                for (var i = notificationDetails.length - 1; i >= 0; i--) {
                  var notificationUrl = notificationDetails[i]['url'];
                  var notificationObj = new Notification(notificationDetails[i]['prod_name'], {
                    body: notificationDetails[i]['message'],
                  });

                  // Set up notification click behavior
                  notificationObj.onclick = function() {
                    window.open(notificationUrl);
                    notificationObj.close();
                  };

                  // Close the notification after 5 seconds
                  setTimeout(function() {
                    notificationObj.close();
                  }, 5000);

                  // Update the last notification time
                  lastNotificationTime = currentTime;
                  break; // Stop once we have sent one notification
                }
              }
            },
            error: function(jqXHR, textStatus, errorThrown) {
              console.error("Error fetching notifications", textStatus, errorThrown);
            }
          });
        }
      }
    }
    getNotification();
    setInterval(function() {
      getNotification();
    }, 20000);
    // Call the notification function every 2 minutes


    document.addEventListener("DOMContentLoaded", () => loadNotifications());
  </script>

  <script src="asset/js/plugins/apexcharts.min.js"></script>
  <script src="asset/js/pages/dashboard-default.js"></script>
  <script src="asset/js/plugins/popper.min.js"></script>
  <script src="asset/js/plugins/simplebar.min.js"></script>
  <script src="asset/js/plugins/bootstrap.min.js"></script>
  <script src="asset/js/fonts/custom-font.js"></script>
  <script src="asset/js/pcoded.js"></script>
  <script src="asset/js/component.js"></script>
  <script src="asset/js/plugins/feather.min.js"></script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="js/popper.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="js/mdb.min.js"></script>
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