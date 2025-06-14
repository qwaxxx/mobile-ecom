<header class="pc-header">
  <div class="header-wrapper"> <!-- [Mobile Media Block] start -->
    <div class="me-auto pc-mob-drp">
      <ul class="list-unstyled">
        <!-- ======= Menu collapse Icon ===== -->
        <li class="pc-h-item pc-sidebar-collapse">
          <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
            <i class="ti ti-menu-2"></i>
          </a>
        </li>
        <li class="pc-h-item pc-sidebar-popup">
          <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
            <i class="ti ti-menu-2"></i>
          </a>
        </li>
        <li class="dropdown pc-h-item d-inline-flex d-md-none">
          <a
            class="pc-head-link dropdown-toggle arrow-none m-0"
            data-bs-toggle="dropdown"
            href="#"
            role="button"
            aria-haspopup="false"
            aria-expanded="false">
            <i class="ti ti-search"></i>
          </a>
          <div class="dropdown-menu pc-h-dropdown drp-search">
            <form class="px-3">
              <div class="form-group mb-0 d-flex align-items-center">
                <i data-feather="search"></i>
                <input type="search" class="form-control border-0 shadow-none" placeholder="Search here. . .">
              </div>
            </form>
          </div>
        </li>
        <li class="pc-h-item d-none d-md-inline-flex">
          <form class="header-search">
            <i data-feather="search" class="icon-search"></i>
            <input type="search" class="form-control" placeholder="Search here. . .">
          </form>
        </li>
      </ul>
    </div>
    <!-- [Mobile Media Block end] -->
    <div class="ms-auto">
      <ul class="list-unstyled">

        <li class="dropdown pc-h-item">
          <a class="pc-head-link dropdown-toggle arrow-none position-relative" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            <i class="ti ti-bell fs-5"></i>
            <span id="notificationBadge" >0</span>
          </a>

          <div class="dropdown-menu dropdown-menu-end pc-h-dropdown shadow dropdown-notification">
            <div class="dropdown-header d-flex align-items-center justify-content-between px-3 py-2">
              <h6 class="mb-0 fw-semibold text-dark">Notifications</h6>
              <a href="#" class="text-danger" onclick="clearNotifications(event)">
                <i class="ti ti-x fs-6"></i>
              </a>
            </div>
            <div class="dropdown-divider my-0"></div>

            <div class="px-2 py-1 overflow-auto" style="max-height: calc(100vh - 215px);" id="notificationList">
            
            </div>

            <div class="dropdown-divider my-0"></div>
            <div class="text-center py-2">
              <a href="#" class="text-primary fw-semibold small">View All</a>
            </div>
          </div>
        </li>


        <li class="dropdown pc-h-item header-user-profile">
          <a
            class="pc-head-link dropdown-toggle arrow-none me-0"
            data-bs-toggle="dropdown"
            href="#"
            role="button"
            aria-haspopup="false"
            data-bs-auto-close="outside"
            aria-expanded="false">
            <img src="<?= $image_src ?>" alt="user-image" class="user-avtar">
            <span><?= htmlspecialchars($name) ?></span>
          </a>
          <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
            <div class="tab-content" id="mysrpTabContent">
              <div class="tab-pane fade show active" id="drp-tab-1" role="tabpanel" aria-labelledby="drp-t1" tabindex="0">
                <a href="seller_profile.php" class="dropdown-item">
                  <i class="ti ti-user"></i>
                  <span>View Profile</span>
                </a>
                <a href="#" id="logoutBtn1" class="dropdown-item">
                  <i class="ti ti-power"></i>
                  <span>Logout</span>
                </a>
              </div>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</header>
<!-- [ Header ] end -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  document.getElementById('logoutBtn1').addEventListener('click', function(e) {
    e.preventDefault();
    Swal.fire({
      title: 'Are you sure?',
      text: "You will be logged out!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Yes, logout',
      background: '#ffffff',
      color: '#000000'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = 'logout.php'; // Your actual logout script
      }
    });
  });
</script>