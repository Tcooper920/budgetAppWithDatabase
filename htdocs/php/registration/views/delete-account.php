<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: /budget_app_with_database/htdocs/php/login/views/login.php');
    exit();
}

require("../../../includes/csrf.php"); // Generate CSRF token
require("../../../includes/header.php");
require("../controllers/delete-user-account.php");

?>

<body class="dark-blue-background min-vh-100 d-flex flex-column">
    <?php require("../../../includes/navbar.php"); ?>
    <main class="d-flex flex-column p-3 mt-5 container col col-md-6 col-xl-4 align-items-center flex-fill">
        <form action="delete-account.php" method="POST"
            class="container fs-5 d-flex flex-column p-4 p-sm-5 my-5 bg-light rounded darkest mw-27rem">
            <h1 class="pb-3 fw-bold">Are you sure you want to delete your account?</h1>
            <label class="w-100">
                Type 'delete my account' to confirm account deletion. Then click the 'Delete my account' button.
                <input type="text" autocomplete="off" placeholder="delete my account" name="delete_account_field"
                    value="" class="form-control m-2 mt-3 ms-0 p-2 ps-4 fs-5 rounded-pill">
            </label>
            <!-- Honeypot field -->
            <input type="text" name="honeypot" value="" class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill d-none">
            <!-- CSRF token field -->
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>"
                class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill d-none">
            <div class="mt-3">
                <input type="submit" value="Delete my account"
                    class="btn warning ms-0 m-2 ms-0 w-100 p-2 fs-5 rounded-pill">
                <a href="../../dashboard/views/dashboard.php" title="Back to homepage">
                    <input type="button" value="Back"
                        class="btn border-primary-color border-2 ms-0 m-2 ms-0 w-100 p-2 fs-5 rounded-pill">
                </a>
            </div>
        </form>
    </main>

    <?php include("../../../includes/footer.php"); ?>
</body>

</html>