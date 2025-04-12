<?php
// Define the base URL relative to the site's root to create dynamic links...
$base_url = "/budget_app_with_database/htdocs/";
$currentPage = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$is_logged_in = isset($_SESSION['user_id']);
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
        if ($is_logged_in) {
          echo '
            <li class="nav-item ps-3">
              <a href="' . $base_url . 'php/dashboard/dashboard.php" title="My budget dashboard" 
              class="nav-link fs-5 ' . ($currentPage == 'dashboard.php' ? 'active-nav-link' : '') . '">My budget</a>
            </li>';
        }
        echo '
          <li class="nav-item ps-3">
            <a href="' . $base_url . 'php/privacy_policy/privacy_policy.php" title="Privacy policy" 
            class="nav-link fs-5 ' . ($currentPage == 'privacy_policy.php' ? 'active-nav-link' : '') . '">Privacy policy</a>
          </li>';
        if ($is_logged_in) {
          echo '
            <li class="nav-item ps-3">
              <a href="' . $base_url . 'php/registration/delete_account.php" title="Delete account" 
              class="nav-link fs-5 ' . ($currentPage == 'delete_account.php' ? 'active-nav-link' : '') . '">Delete account</a>
            </li>';
        }
        if (!$is_logged_in) {
          echo '
            <li class="nav-item ps-3">
              <a href="' . $base_url . 'php/registration/register.php" title="Register" 
              class="nav-link fs-5 ' . ($currentPage == 'register.php' ? 'active-nav-link' : '') . '">Register</a>
            </li>
            <li class="nav-item ps-3">
              <a href="' . $base_url . 'php/login/login.php" title="Login" 
              class="nav-link fs-5 ' . ($currentPage == 'login.php' ? 'active-nav-link' : '') . '">Login</a>
            </li>';
        }
        if ($is_logged_in) {
          echo '
            <li class="nav-item ps-3">
              <a href="' . $base_url . 'php/login/logout.php" title="Logout" 
              class="nav-link fs-5 ' . ($currentPage == 'logout.php' ? 'active-nav-link' : '') . '">Logout</a>
            </li>';
        }
        ?>
      </ul>
    </div>
  </div>
</nav>