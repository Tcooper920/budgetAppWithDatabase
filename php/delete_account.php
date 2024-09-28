<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    require ('login_tools.php');
    load();
}

$login_or_logout = "logout";
require ("includes/navbar.html");
require ("includes/header.html");

?>

<body class="dark-blue-background">
    <main class="p-3 mt-5 container col col-md-8 col-lg-6 col-xl-5 align-items-center">
        <div class="container fs-5 d-flex flex-column p-4 p-sm-5 my-5 bg-light rounded darkest">
            <h1 class="pb-3">Are you sure you want to delete your account?</h1>
            <p>All your budgeting data will be permanently deleted. You can always create a new account.</p>
            <div class="mt-3">
                <a href="delete_account.php" title="Delete my account">
                    <input type="button" value="Delete my account" class="btn btn-primary ms-0 m-2 ms-0 w-100 p-2 fs-5 rounded-pill">
                </a>
                <a href="index.php" title="Back to homepage">
                    <input type="button" value="Back" class="btn border-primary-color border-2 ms-0 m-2 ms-0 w-100 p-2 fs-5 rounded-pill">
                </a>
            </div>
        </div>
    </main>
</body>

<?php include ("includes/footer.html"); ?>