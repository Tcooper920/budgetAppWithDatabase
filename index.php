<?php

session_start();
require("includes/header.php");

?>

<body class="white min-vh-100 d-flex flex-column gradient-background">
    <?php require("includes/navbar.php"); ?>
    <main class="p-3 pt-sm-5 mt-5 container align-items-center flex-fill">
        <div class="row mb-2">
            <div class="col-md-6">
                <h1 class="pb-3 fw-bold">Welcome to BudgetBuilder!</h1>
                <p class="fs-5 mb-4">BudgetBuilder is a free, easy-to-use budgeting app that helps you manage your everyday
                    expenses. Just create an account, set your budget limit, and add your expenses. Your spending
                    breakdown will be displayed in a graph above your detailed expense list.</p>
                    <p class="fs-5 mb-4">BudgetBuilder is designed to be simple, but stay tuned—more features may be added in the future.</p>
                <p class="fs-5 mb-4">Log in to get started!</p>
                <div class="row pt-2">
                    <div class="col-sm-5">
                        <a href="php/login/login.php" class="btn btn-primary ms-0 m-2 ms-0 w-100 p-2 fs-5 rounded-pill"
                            title="Go to login screen">Login</a>
                    </div>
                    <div class="col-sm-5"><a href="php/registration/register.php"
                            class="btn border-white text-white border-2 ms-0 m-2 ms-0 w-100 p-2 fs-5 rounded-pill"
                            title="Create an account">Register</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <p class="fs-5"></p>
            </div>
        </div>
    </main>
    <script src="js/preserve_scroll_position_onload.js"></script>
</body>

<?php include("includes/footer.php"); ?>