<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    header("Location: php/dashboard/views/dashboard.php");
    exit();
}

require("includes/header.php");

?>

<body class="white min-vh-100 d-flex flex-column gradient-background">
    <?php require("includes/navbar.php"); ?>
    <main class="p-3 pt-sm-5 mt-lg-5 container align-items-center flex-fill">
        <div class="row mb-2">
            <div class="col-lg-6 fade-in">
                <h1 class="pb-3 fw-bold">Welcome to BuildaBudge!</h1>
                <p class="fs-5 mb-4">
                    BuildaBudge is a free, easy-to-use budgeting app that helps you manage your everyday
                    expenses. Just create an account, set your budget limit, and add your expenses.
                </p>
                <p class="fs-5 mb-4">
                    Each expense is shown as a percentage of your total spending. Your spending breakdown will
                    be displayed in a graph above your detailed expense list. As you make payments toward
                    each expense, simply enter the amount paid in that expense's <em class="pe-1">Spent</em> field.
                </p>
                <p class="fs-5 mb-4">
                    BuildaBudge is designed to be simple, but stay tuned—more features may be added in the
                    future.
                </p>
                <p class="fs-5 mb-4">Log in or register to get started!</p>
                <div class="row pt-2">
                    <div class="col-sm-5">
                        <a
                            href="php/login/views/login.php"
                            class="btn btn-primary ms-0 m-2 ms-0 w-100 p-2 fs-5 rounded-pill"
                            title="Go to login screen">Login</a>
                    </div>
                    <div class="col-sm-5">
                        <a
                            href="php/registration/views/register.php"
                            class="btn border-white text-white border-2 ms-0 m-2 ms-0 w-100 p-2 fs-5 rounded-pill"
                            title="Create an account">Register</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <img
                    src="images/buildabudge-app-screenshot.png"
                    width="1200"
                    height="1015"
                    class="w-100 h-auto mt-5 mt-lg-0 ps-lg-4 fade-in-and-rise"
                    alt="BuildaBudge app screenshot that shows a graph and itemized list of expenses." />
            </div>
        </div>
    </main>
    <script src="js/preserve-scroll-position-onload.js"></script>
</body>

<?php include("includes/footer.php"); ?>