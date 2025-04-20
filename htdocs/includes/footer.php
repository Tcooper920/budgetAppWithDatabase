<?php // Define the base URL relative to the site's root to create dynamic links...
$base_url = "/budget_app_with_database/htdocs/";
$is_logged_in = isset($_SESSION['user_id']);

// Helper function to print footer nav items to the page...
function printFooterNavItem($is_logged_in, $base_url, $page_file_path, $title_attribute, $nav_item_name)
{
  if ($is_logged_in) {
    echo "
            <li class='nav-item'>
              <a class='nav-link px-0 py-0 text-light' href='{$base_url}{$page_file_path}' title='{$title_attribute}'>{$nav_item_name}</a>
            </li>";
  }
}
?>

<div>
  <footer>
    <div class="d-flex flex-wrap justify-content-between align-items-center py-4 px-3 px-sm-5 border-top mt-5">
      <p class="col-md-4 mb-0 text-light"><strong class="fw-bold">BudgetBuilder</strong></p>

      <a href="<?php echo $base_url ?>index.php"
        class="col-md-4 d-flex align-items-center justify-content-center me-md-auto link-body-emphasis text-decoration-none">
        <img src="<?php echo $base_url; ?>/images/budget-builder-logo.svg" class="mx-3" width="40" height="40"
          alt="Budget Builder logo" title="Budget Builder">
      </a>

      <ul class="nav col-md-4 justify-content-start justify-content-sm-end mt-2 mt-sm-0 gap-3">
        <?php
        printFooterNavItem($is_logged_in, $base_url, "php/dashboard/dashboard.php", "My budget dashboard", "My budget");
        printFooterNavItem($is_logged_in, $base_url, "php/how_to_use/how_to_use.php", "How to use", "How to use");
        printFooterNavItem($is_logged_in, $base_url, "php/privacy_policy/privacy_policy.php", "Privacy policy", "Privacy policy");
        printFooterNavItem($is_logged_in, $base_url, "php/registration/delete_account.php", "Delete account", "Delete account");
        printFooterNavItem(!$is_logged_in, $base_url, "php/registration/register.php", "Register", "Register");
        printFooterNavItem(!$is_logged_in, $base_url, "php/login/login.php", "Login", "Login");
        printFooterNavItem($is_logged_in, $base_url, "php/login/logout.php", "Logout", "Logout");
        ?>
      </ul>
    </div>
    <div class="d-flex justify-content-center py-4 footer-author-container">
      <p class="mb-0 fs-6 white">Developed by Thomas Cooper</p>
    </div>
  </footer>
</div>