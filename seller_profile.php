<?php
session_start();
include("api/conn.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'seller') {
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
    header("Location: seller_profile.php");
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

 <?php include("seller_slidebar.php") ?>

 <?php include("seller_header.php") ?>

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

  <?php include("seller_footer.php") ?>
   <script>
    let currentSortedColumn = -1;
    let currentSortDirection = "asc";

    function sortTable(n) {
      const table = document.getElementById("ordersTable");
      let rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;

      switching = true;
      dir = (n === currentSortedColumn && currentSortDirection === "asc") ? "desc" : "asc";

      currentSortedColumn = n;
      currentSortDirection = dir;

      while (switching) {
        switching = false;
        rows = table.rows;

        for (i = 1; i < (rows.length - 1); i++) {
          shouldSwitch = false;
          x = rows[i].getElementsByTagName("TD")[n];
          y = rows[i + 1].getElementsByTagName("TD")[n];

          let xContent = x.textContent || x.innerText;
          let yContent = y.textContent || y.innerText;

          let comparison = 0;

          // Check if it's the Date/Time column (usually index 0)
          if (n === 0) {
            let xDate = new Date(xContent);
            let yDate = new Date(yContent);
            comparison = xDate - yDate;
          } else {
            const xNum = parseFloat(xContent);
            const yNum = parseFloat(yContent);
            const isNumeric = !isNaN(xNum) && !isNaN(yNum);

            if (isNumeric) {
              comparison = xNum - yNum;
            } else {
              comparison = xContent.toLowerCase().localeCompare(yContent.toLowerCase());
            }
          }

          if ((dir === "asc" && comparison > 0) || (dir === "desc" && comparison < 0)) {
            shouldSwitch = true;
            break;
          }
        }

        if (shouldSwitch) {
          rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
          switching = true;
          switchcount++;
        } else {
          if (switchcount == 0 && dir === "asc") {
            dir = "desc";
            switching = true;
          }
        }
      }

      // Update sort indicators
      const ths = table.querySelectorAll("th");
      ths.forEach((th, index) => {
        let span = th.querySelector(".sort-indicator");
        if (!span) {
          span = document.createElement("span");
          span.className = "sort-indicator";
          th.appendChild(span);
        }
        if (index === n) {
          span.textContent = dir === "asc" ? " ▲" : " ▼";
        } else {
          span.textContent = "";
        }
      });
    }
  </script>


  <script>
    let ordersData = [];
    const rowsPerPage = 12;
    let currentPage = 1;

    // Fetch data from server
    fetch('seller_fetch_orders.php')
      .then(res => res.json())
      .then(data => {
        ordersData = data;
        displayTable(currentPage);
        setupPagination();
      });

    // Display paginated table
    function displayTable(page) {
      const tbody = document.getElementById('ordersBody');
      tbody.innerHTML = '';

      const start = (page - 1) * rowsPerPage;
      const end = start + rowsPerPage;
      const paginatedItems = ordersData.slice(start, end);

      if (paginatedItems.length === 0) {
        tbody.innerHTML = `<tr>
      <td colspan="12" class="text-center text-muted py-4">
        No transactions found.
      </td>
    </tr>`;
        return;
      }

      paginatedItems.forEach(row => {
        // only show actions when status is exactly "pending"
        const actionCell = row.addcart_status.toLowerCase() === 'pending' ?
          `
        <button class="btn btn-success btn-sm"
                onclick="updateStatus(${row.order_id}, 'accepted')">
          Accept
        </button>
        <button class="btn btn-danger btn-sm"
                onclick="updateStatus(${row.order_id}, 'rejected')">
          Reject
        </button>` :
          'No actions needed'; // empty if not pending

        tbody.innerHTML += `
      <tr>
        <td>${row.order_date}</td>
        <td>${row.addcart_id}</td>
        <td>${row.billing_lname}, ${row.billing_fname}</td>
        <td>${row.billing_street_village_purok}</td>
        <td>${row.billing_baranggay}</td>
        <td>${row.billing_city}</td>
        <td>${row.billing_province}</td>
        <td>${row.billing_country}</td>
        <td>${row.total_quantity}</td>
        <td>${parseFloat(row.total_amount)
                     .toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                     })}</td>
        <td>${row.addcart_status}</td>
         <td>
            <button class="btn btn-primary btn-sm"
                    onclick='downloadReceipt(${JSON.stringify(row)})'>
              Download
            </button>
          </td>
        <td>${actionCell}</td>
      </tr>`;
      });
    }


    function downloadReceipt(row) {
  const receiptContainer = document.createElement('div');
  receiptContainer.innerHTML = `
    <div style="font-family: Arial, sans-serif; padding: 20px; max-width: 600px; margin: auto; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
      <h2 style="text-align: center;">Order Receipt</h2>
      <table style="width: 100%; margin-top: 20px; border-collapse: collapse;">
        <tr><td style="font-weight:bold; background:#f9f9f9; border:1px solid #ddd;">Order ID</td><td style="border:1px solid #ddd;">${row.addcart_id}</td></tr>
        <tr><td style="font-weight:bold; background:#f9f9f9; border:1px solid #ddd;">Order Date</td><td style="border:1px solid #ddd;">${row.order_date}</td></tr>
        <tr><td style="font-weight:bold; background:#f9f9f9; border:1px solid #ddd;">Customer Name</td><td style="border:1px solid #ddd;">${row.billing_lname}, ${row.billing_fname}</td></tr>
        <tr><td style="font-weight:bold; background:#f9f9f9; border:1px solid #ddd;">Street / Purok</td><td style="border:1px solid #ddd;">${row.billing_street_village_purok}</td></tr>
        <tr><td style="font-weight:bold; background:#f9f9f9; border:1px solid #ddd;">Barangay</td><td style="border:1px solid #ddd;">${row.billing_baranggay}</td></tr>
        <tr><td style="font-weight:bold; background:#f9f9f9; border:1px solid #ddd;">City</td><td style="border:1px solid #ddd;">${row.billing_city}</td></tr>
        <tr><td style="font-weight:bold; background:#f9f9f9; border:1px solid #ddd;">Province</td><td style="border:1px solid #ddd;">${row.billing_province}</td></tr>
        <tr><td style="font-weight:bold; background:#f9f9f9; border:1px solid #ddd;">Country</td><td style="border:1px solid #ddd;">${row.billing_country}</td></tr>
        <tr><td style="font-weight:bold; background:#f9f9f9; border:1px solid #ddd;">Total Items</td><td style="border:1px solid #ddd;">${row.total_quantity}</td></tr>
        <tr><td style="font-weight:bold; background:#f9f9f9; border:1px solid #ddd;">Total Amount</td><td style="border:1px solid #ddd;">₱${parseFloat(row.total_amount).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td></tr>
        <tr><td style="font-weight:bold; background:#f9f9f9; border:1px solid #ddd;">Status</td><td style="border:1px solid #ddd;">${row.addcart_status}</td></tr>
      </table>
      <p style="text-align: center; margin-top: 30px;">Thank you for your purchase!</p>
    </div>
  `;

  // Generate and save PDF
  html2pdf()
    .from(receiptContainer)
    .set({
      margin: 10,
      filename: `receipt-${row.addcart_id}.pdf`,
      html2canvas: { scale: 2 },
      jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
    })
    .save();
}


    function searchTable() {
      const input = document.getElementById("searchInput");
      const filter = input.value.toLowerCase();
      const tbody = document.getElementById("ordersBody");
      const rows = tbody.getElementsByTagName("tr");

      for (let i = 0; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName("td");
        let rowContainsKeyword = false;

        for (let j = 0; j < cells.length - 1; j++) { // Exclude the last "Action" column
          if (cells[j].textContent.toLowerCase().includes(filter)) {
            rowContainsKeyword = true;
            break;
          }
        }

        rows[i].style.display = rowContainsKeyword ? "" : "none";
      }
    }


    function updateStatus(orderId, status) {
      fetch('seller_update_status.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            order_id: orderId,
            status: status
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Status updated successfully!');
            // Refresh the table or update the status in the table directly
            fetch('seller_fetch_orders.php')
              .then(res => res.json())
              .then(data => {
                ordersData = data;
                displayTable(currentPage);
                setupPagination();
              });
          } else {
            alert('Failed to update status.');
          }
        })
        .catch(error => console.error('Error:', error));
    }
    // Setup pagination buttons
    function setupPagination() {
      const pagination = document.getElementById('pagination');
      pagination.innerHTML = '';

      const pageCount = Math.ceil(ordersData.length / rowsPerPage);

      for (let i = 1; i <= pageCount; i++) {
        const li = document.createElement('li');
        li.classList.add('page-item');
        if (i === currentPage) li.classList.add('active');

        const a = document.createElement('a');
        a.classList.add('page-link');
        a.href = "#";
        a.innerText = i;

        a.addEventListener('click', function(e) {
          e.preventDefault();
          currentPage = i;
          displayTable(currentPage);
          setupPagination();
        });

        li.appendChild(a);
        pagination.appendChild(li);
      }
    }
  </script>
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
             href="#"
             onclick="handleNotificationClick(${n.id}, ${n.addcart_id})">
            ${n.message}
          </a><span style="font-size: 9px; display: inline-block; text-align: right; width: 100%; margin-right: 5px;">${n.created_at}</span>`;
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

    // Example of your existing click‑handler
    function handleNotificationClick(notifId, orderId) {
      fetch('mark_notification_read.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            id: notifId
          })
        })
        .then(r => r.json())
        .then(resp => {
          if (resp.success) {
            document.getElementById("searchInput").value = orderId;
            searchTable();
            loadNotifications(); // refresh both badge & list
          } else {
            alert("Failed to mark as read");
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