<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require("../../includes/header.php");

?>

<body class="white min-vh-100 d-flex flex-column gradient-background">
    <?php require("../../includes/navbar.php"); ?>
    <main class="p-3 pt-sm-5 mt-lg-5 container align-items-center flex-fill">
        <div class="row mb-2">
            <div class="col-lg-6 fade-in">
                <h1 class="pb-3 fw-bold">Privacy policy</h1>
                <p class="fs-5 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                    incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                    ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in
                    voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                    proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                <div class="row pt-2">
                    <div class="col-sm-7">
                        <a href="/budget_app_with_database/htdocs/index.php"
                            class="btn btn-primary ms-0 m-2 ms-0 w-100 p-2 fs-5 rounded-pill"
                            title="Return to homepage">Return to homepage</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-flex">
                <img src="/budget_app_with_database/htdocs/images/budget-builder-logo-3d.png"
                    alt="Calculator graphic with a screen that reads, 'OOPS!'." width="608" height="628"
                    class="col w-100 h-auto mt-5 mt-lg-0 ps-lg-4 ">
            </div>
        </div>
    </main>
</body>

<?php include("../../includes/footer.php"); ?>