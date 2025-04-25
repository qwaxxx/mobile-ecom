<!-- [ Sidebar Menu ] start -->
<nav class="pc-sidebar">
  <div class="navbar-wrapper">
    <div class="m-header">
      <a href="index.php" class="b-brand text-primary">
        <h5>E-commerce</h5>
      </a>
    </div>
    <div class="navbar-content">
      <ul class="pc-navbar">

        <li class="pc-item">
          <a href="customer_dashboard.php" class="pc-link">
            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
            <span class="pc-mtext">Dashboard</span>
          </a>
        </li>

        <li class="pc-item pc-caption">
          <label>Settings</label>
          <i class="ti ti-news"></i>
        </li>
        <li class="pc-item">
          <a href="customer_profile.php" class="pc-link">
            <span class="pc-micon"><i class="ti ti-user"></i></span>
            <span class="pc-mtext">Profile</span>
          </a>
        </li>
        <li class="pc-item">
          <a href="#" id="logoutBtn" class="pc-link">
            <span class="pc-micon"><i class="ti ti-logout"></i></span>
            <span class="pc-mtext">Logout</span>
          </a>
        </li>

        <!-- <li class="pc-item">
          <a href="" class="pc-link">
            <span class="pc-micon"><i class="ti ti-brand-chrome"></i></span>
            <span class="pc-mtext">Help</span>
          </a>
        </li> -->
      </ul>
      <!-- <div class="card text-center">
        <div class="card-body">
          <img src="../assets/images/img-navbar-card.png" alt="images" class="img-fluid mb-2">
          <h5>Upgrade To Pro</h5>
          <p>To get more features and components</p>
          <a href="https://codedthemes.com/item/berry-bootstrap-5-admin-template/" target="_blank"
          class="btn btn-success">Buy Now</a>
        </div>
      </div> -->
    </div>
  </div>
</nav>
<!-- [ Sidebar Menu ] end --> <!-- [ Header Topbar ] start -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  document.getElementById('logoutBtn').addEventListener('click', function(e) {
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