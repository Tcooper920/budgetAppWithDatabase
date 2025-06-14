<?php
// Define the base URL relative to the site's root to create dynamic links...
$base_url = "/$root_folder/public_html/htdocs/";
$current_page = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$is_logged_in = isset($_SESSION['user_id']);

// Helper function to print nav items to the page...
function print_nav_item(
  bool $is_logged_in,
  bool $add_button_styling,
  string $base_url,
  string $page_file_path,
  string $title_attribute,
  string $current_page,
  string $page_file_name,
  string $nav_item_name
) {
  if (!$is_logged_in) {
    return;
  }

  $link_classes = 'nav-link fs-5'; // Link classes

  if ($current_page === $page_file_name) {
    $link_classes .= ' active-nav-link'; // Active link class
  }

  if ($add_button_styling) {
    $link_classes = 'fs-5 px-5 my-3 my-lg-0 btn btn-dark rounded-pill'; // Button link classes
  }

  echo "
        <li class='nav-item ps-3 pe-3 pe-sm-0'>
          <a href='{$base_url}{$page_file_path}' title='{$title_attribute}' 
          class='{$link_classes}'>{$nav_item_name}</a>
        </li>";
}
?>

<nav class="navbar navbar-light navbar-expand-lg mb-4 px-0 px-sm-4 shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?php echo $base_url ?>index.php">
      <img src="<?php echo $base_url ?>/images/buildabudge-logo-horizontal.svg" width="230" height="65"
        alt="BuildaBudge logo" title="BuildaBudge">
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
      data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
      aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto mt-3 mt-lg-0 mb-xl-0">
        <?php
        print_nav_item($is_logged_in, false, $base_url, "php/dashboard/views/dashboard.php", "My budget dashboard", $current_page, "dashboard.php", "My budget");
        print_nav_item(true, false, $base_url, "php/how-to-use/views/how-to-use.php", "How to use", $current_page, "how-to-use.php", "How to use");
        print_nav_item(true, false, $base_url, "php/privacy-policy/views/privacy-policy.php", "Privacy policy", $current_page, "privacy-policy.php", "Privacy policy");
        print_nav_item($is_logged_in, false, $base_url, "php/registration/views/delete-account.php", "Delete account", $current_page, "delete-account.php", "Delete account");
        print_nav_item(!$is_logged_in, false, $base_url, "php/registration/views/register.php", "Register", $current_page, "register.php", "Register");
        print_nav_item(!$is_logged_in, true, $base_url, "php/login/views/login.php", "Login", $current_page, "login.php", "Login");
        print_nav_item($is_logged_in, false, $base_url, "php/login/views/logout.php", "Logout", $current_page, "logout.php", "Logout");
        ?>
      </ul>
    </div>
  </div>
</nav>