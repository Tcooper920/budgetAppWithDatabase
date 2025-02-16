<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    require('login_tools.php');
    load();
}

require("../../includes/header.php");

?>

<body class="dark-blue-background min-vh-100 d-flex flex-column">

    <?php

    require("../../includes/navbar.php");
    $_SESSION = array();
    session_destroy();

    ?>

    <main class="d-flex flex-column p-3 mt-5 container col col-md-6 col-xl-4 align-items-center flex-fill">
        <div class="container fs-5 d-flex flex-column p-4 p-sm-5 my-5 bg-light rounded darkest">
            <h1 class="pb-3">Goodbye!</h1>
            <p>You have logged out of your account.</p>
            <div class="mt-3">
                <a href="login.php" title="Back to login">
                    <input type="button" value="Back to login"
                        class="btn btn-primary ms-0 m-2 ms-0 w-100 p-2 fs-5 rounded-pill">
                </a>
            </div>
        </div>
    </main>

    <?php include("../../includes/footer.php"); ?>
</body>

</html>