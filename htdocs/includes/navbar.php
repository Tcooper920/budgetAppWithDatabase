<?php
// Define the base URL relative to the site's root to create dynamic links...
$base_url = "/budget_app_with_database/htdocs/";
$current_page = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$is_logged_in = isset($_SESSION['user_id']);

// Helper function to print nav items to the page...
function print_nav_item($is_logged_in, $base_url, $page_file_path, $title_attribute, $current_page, $page_file_name, $nav_item_name)
{
  if ($is_logged_in) {
    $is_active = ($current_page == $page_file_name ? 'active-nav-link' : '');

    echo "
            <li class='nav-item ps-3'>
              <a href='{$base_url}{$page_file_path}' title='{$title_attribute}' 
              class='nav-link fs-5 {$is_active}'>{$nav_item_name}</a>
            </li>";
  }
}
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
        print_nav_item($is_logged_in, $base_url, "php/dashboard/dashboard.php", "My budget dashboard", $current_page, "dashboard.php", "My budget");
        print_nav_item($is_logged_in, $base_url, "php/how_to_use/how_to_use.php", "How to use", $current_page, "how_to_use.php", "How to use");
        print_nav_item($is_logged_in, $base_url, "php/privacy_policy/privacy_policy.php", "Privacy policy", $current_page, "privacy_policy.php", "Privacy policy");
        print_nav_item($is_logged_in, $base_url, "php/registration/delete_account.php", "Delete account", $current_page, "delete_account.php", "Delete account");
        print_nav_item(!$is_logged_in, $base_url, "php/registration/register.php", "Register", $current_page, "register.php", "Register");
        print_nav_item(!$is_logged_in, $base_url, "php/login/login.php", "Login", $current_page, "login.php", "Login");
        print_nav_item($is_logged_in, $base_url, "php/login/logout.php", "Logout", $current_page, "logout.php", "Logout");
        ?>
      </ul>
    </div>
  </div>
</nav>