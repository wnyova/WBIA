<!-- Navbar -->
<nav class="main-header navbar navbar-expand-md navbar-primary navbar-dark">
  <!-- Left navbar links -->
  <div class="container">
    <!-- Navbar Brand with Logo -->
    <a class="navbar-brand" href="index.php?page=home">
      <img src="logoplnsc.png" alt="Your Logo" class="brand-image img-square" style="opacity: 1; width: 90px; margin-right: 10px;">
      <span class="brand-text font-weight-light d-md-none text-center">Internship Portal</span>
      <span class="brand-text font-weight-light d-none d-md-inline">Internship Portal</span>
    </a>

    <!-- Toggler/collapsible Button for small screens -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar Links -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <!-- Dropdown Menu for User Account -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" aria-expanded="true" href="javascript:void(0)">
            <span>
              <div class="d-flex badge-pill">
                <span class="fa fa-user mr-2"></span>
                <span><b><?php echo ucwords($_SESSION['login_firstname']) ?></b></span>
                <span class="fa fa-angle-down ml-2"></span>
              </div>
            </span>
          </a>
          <div class="dropdown-menu" aria-labelledby="account_settings" style="left: -2.5em;">
            <a class="dropdown-item" href="index.php?page=absensi"><i class="far fa-clock" aria-hidden="true"></i> Absensi</a>
            <a class="dropdown-item" href="index.php?page=reports" id="manage_my_account"><i class="fa fa-book"></i> Reports</a>
            <a class="dropdown-item" href="index.php?page=permit" id="manage_my_account"><i  class="fa fa-exclamation"></i>&nbsp&nbspPermit</a>
            <a class="dropdown-item" href="index.php?page=permit_status" id="manage_my_account"><i class="fa fa-spinner"></i> Permit Status</a>
            <!-- <a class="dropdown-item" href="signup.php" id="manage_my_account"><i class="fa fa-cog"></i> Manage Account</a> -->
            <a class="dropdown-item" href="admin/ajax.php?action=logout2"><i class="fa fa-power-off"></i> Logout</a>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- /.navbar -->
