<?php
// Define the base URL relative to the site's root to create dynamic links...
$base_url = "/budget_app_with_database/"; 
?>
<nav class="navbar navbar-light navbar-expand-lg mb-4 px-4 shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?php echo $base_url ?>index.php">
        <img src="<?php echo $base_url ?>/images/budget-builder-logo-horizontal.svg" width="230" alt="Budget Builder logo" title="Budget Builder">
    </a>
    <button
      class="navbar-toggler"
      type="button"
      data-bs-toggle="collapse"
      data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <?php
          if (isset($show_my_budget)) {
            echo '
            <li class="nav-item ps-3">
              <a class="nav-link fs-5" href="' . $base_url . 'php/dashboard/dashboard.php" title="My budget dashboard">My budget</a>
            </li>';
          }
          if (isset($show_delete_account)) {
            echo '
            <li class="nav-item ps-3">
              <a class="nav-link fs-5" href="' . $base_url . 'php/registration/delete_account.php" title="Delete account">Delete account</a>
            </li>';
          }
          if (isset($show_register_link)) {
            echo '
            <li class="nav-item ps-3">
              <a class="nav-link fs-5" href="' . $base_url . 'php/registration/register.php" title="Register">Register</a>
            </li>';
          }
          if ($login_or_logout == "login") {
            echo '
            <li class="nav-item ps-3">
              <a class="nav-link fs-5" href="' . $base_url . 'php/login/login.php" title="Login">Login</a>
            </li>';
          } else if ($login_or_logout == "logout") {
            echo '
            <li class="nav-item ps-3">
              <a class="nav-link fs-5" href="' . $base_url . 'php/login/logout.php" title="Logout">Logout</a>
            </li>';
          } else {
            echo '';
          }
        ?>
      </ul>
    </div>
  </div>
</nav>
