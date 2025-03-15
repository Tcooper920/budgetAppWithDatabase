<?php
// Define the base URL relative to the site's root to create dynamic links...
$base_url = "/budget_app_with_database/htdocs/";
?>
<nav class="navbar navbar-light navbar-expand-lg mb-4 px-0 px-sm-4 shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?php echo $base_url ?>index.php">
      <img src="<?php echo $base_url ?>/images/budget-builder-logo-horizontal.svg" width="230" alt="Budget Builder logo"
        title="Budget Builder">
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
      data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
      aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto mt-3 mt-lg-0 mb-xl-0">
        <?php
        if (isset($_SESSION['user_id'])) {
          echo '
            <li class="nav-item ps-3">
              <a class="nav-link fs-5" href="' . $base_url . 'php/dashboard/dashboard.php" title="My budget dashboard">My budget</a>
            </li>';
        }
        echo '
          <li class="nav-item ps-3">
            <a class="nav-link fs-5" href="' . $base_url . 'php/privacy_policy/privacy_policy.php" title="Privacy policy">Privacy policy</a>
          </li>';
        if (isset($_SESSION['user_id'])) {
          echo '
            <li class="nav-item ps-3">
              <a class="nav-link fs-5" href="' . $base_url . 'php/registration/delete_account.php" title="Delete account">Delete account</a>
            </li>';
        }
        if (!isset($_SESSION['user_id'])) {
          echo '
            <li class="nav-item ps-3">
              <a class="nav-link fs-5" href="' . $base_url . 'php/registration/register.php" title="Register">Register</a>
            </li>
            <li class="nav-item ps-3">
              <a class="nav-link fs-5" href="' . $base_url . 'php/login/login.php" title="Login">Login</a>
            </li>';
        }
        if (isset($_SESSION['user_id'])) {
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