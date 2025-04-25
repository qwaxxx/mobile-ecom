<?php
session_start();
include("api/conn.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
  header("Location: login_page.php");
  exit;
}

$user_id = $_SESSION['user_id'];

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

  <?php include("customer_slidebar.php") ?>

  <?php include("customer_header.php") ?>

  <div class="pc-container">
    <div class="pc-content">

      <div class="row">
        <?php
        $totalAddcarts = $conn->query("SELECT COUNT(*) as count FROM addcarts where addcart_user_id =$user_id")->fetch_assoc()['count'];
        $totalNotifications = $conn->query("SELECT COUNT(*) as count FROM notifications where user_id = $user_id")->fetch_assoc()['count'];
        ?>


        <!-- Add to Carts -->
        <div class="col-md-6 col-xl-6">
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



        <!-- Notifications -->
        <div class="col-md-6 col-xl-6">
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

        <div class="col-12">
          <div class="card">
            <div class="card-body p-0">

              <div class="d-flex justify-content-end p-3">
                <input type="text" id="searchInput" class="form-control form-control-sm w-auto" placeholder="Search orders..." onkeyup="searchTable()" />
              </div>
              <div class="table-responsive table-responsive-md">

                <table id="ordersTable" class="table table-striped mb-0">
                  <thead>
                    <tr style="cursor: pointer;">
                      <th onclick="sortTable(0)">Date/Time <span class="sort-indicator"></span></th>
                      <th onclick="sortTable(1)">Tracking ID <span class="sort-indicator"></span></th>
                      <th onclick="sortTable(2)">Block/Lot/Street/Village <span class="sort-indicator"></span></th>
                      <th onclick="sortTable(3)">Baranggay <span class="sort-indicator"></span></th>
                      <th onclick="sortTable(4)">City <span class="sort-indicator"></span></th>
                      <th onclick="sortTable(5)">Province <span class="sort-indicator"></span></th>
                      <th onclick="sortTable(6)">Country <span class="sort-indicator"></span></th>
                      <th onclick="sortTable(7)">Parcel Quantity <span class="sort-indicator"></span></th>
                      <th onclick="sortTable(8)">Total Amount <span class="sort-indicator"></span></th>
                      <th onclick="sortTable(9)">Status <span class="sort-indicator"></span></th>

                    </tr>
                  </thead>
                  <tbody id="ordersBody">
                  </tbody>
                </table>

              </div>
            </div>
            <div class="d-flex justify-content-center mt-3">
              <nav>

                <ul class="pagination" id="pagination"></ul>
              </nav>
            </div>
          </div>

        </div>

      </div>
    </div>
  </div>
  <!-- [ Main Content ] end -->

  <?php include("customer_footer.php") ?>
  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>

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
    fetch('customer_fetch_orders.php')
      .then(res => res.json())
      .then(data => {
        console.log(data); //
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
        tbody.innerHTML = `<tr><td colspan="9" class="text-center text-muted py-4">No transactions found.</td></tr>`;
      } else {
        paginatedItems.forEach(row => {
          tbody.innerHTML += `
          <tr>
  <td>${row.order_date}</td>
  <td>${row.addcart_id}</td>
  <td>${row.billing_street_village_purok}</td>
  <td>${row.billing_baranggay}</td>
  <td>${row.billing_city}</td>
  <td>${row.billing_province}</td>
  <td>${row.billing_country}</td>
  <td>${row.total_quantity}</td>
  <td>${row.total_amount}</td>
  <td>${row.addcart_status}</td>
</tr>
        `;
        });
      }
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