<?php // Define the base URL relative to the site's root to create dynamic links...
$base_url = "/budget_app_with_database/"; ?>

<div>
  <footer>
    <div class="d-flex flex-wrap justify-content-between align-items-center py-4 px-3 px-sm-5 border-top mt-5">
      <p class="col-md-4 mb-0 text-light">© BudgetBuilder</p>

      <a href="<?php echo $base_url ?>index.php"
        class="col-md-4 d-flex align-items-center justify-content-center me-md-auto link-body-emphasis text-decoration-none">
        <img src="<?php echo $base_url; ?>/images/budget-builder-logo.svg" class="mx-3" width="40" height="40"
          alt="Budget Builder logo" title="Budget Builder">
      </a>

      <ul class="nav col-md-4 justify-content-start justify-content-sm-end mt-3 mt-sm-0 gap-3">
        <?php
        if (isset($_SESSION['user_id'])) {
          echo '
            <li class="nav-item">
              <a class="nav-link px-0 py-0 text-light" href="' . $base_url . 'php/dashboard/dashboard.php" title="My budget dashboard">My budget</a>
            </li>
            <li class="nav-item">
              <a class="nav-link px-0 py-0 text-light" href="' . $base_url . 'php/registration/delete_account.php" title="Delete account">Delete account</a>
            </li>';
        }
        echo '
            <li class="nav-item">
              <a class="nav-link px-0 py-0 text-light" href="' . $base_url . 'php/registration/register.php" title="Register">Register</a>
            </li>';
        if (!isset($_SESSION['user_id'])) {
          echo '
            <li class="nav-item">
              <a class="nav-link px-0 py-0 text-light" href="' . $base_url . 'php/login/login.php" title="Login">Login</a>
            </li>';
        }
        if (isset($_SESSION['user_id'])) {
          echo '
            <li class="nav-item">
              <a class="nav-link px-0 py-0 text-light" href="' . $base_url . 'php/login/logout.php" title="Logout">Logout</a>
            </li>';
        } else {
          echo '';
        }
        ?>
      </ul>
    </div>
    <div class="d-flex justify-content-center py-4 footer-author-container">
      <p class="mb-0 fs-6 white">Developed by Thomas Cooper</p>
    </div>
  </footer>
</div>