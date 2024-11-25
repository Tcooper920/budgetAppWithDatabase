<?php

session_start();

require("includes/header.php");

# Navbar settings...
$login_or_logout = "login";
$show_register_link = true;
$show_my_budget = true;
require("includes/navbar.php");

?>

<body class="dark-blue-background white h-100 d-flex flex-column">
    <main class="d-flex flex-column p-3 mt-5 container col col-md-6 col-xl-4 align-items-center flex-fill">
        <!-- Homepage content will go here -->
    </main>
    <script src="js/preserve_scroll_position_onload.js"></script>
</body>

<?php include("includes/footer.php"); ?>