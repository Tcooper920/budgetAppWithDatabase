<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require("../../../includes/header.php");

?>

<body class="white min-vh-100 d-flex flex-column gradient-background">
    <?php require("../../../includes/navbar.php"); ?>
    <main class="p-3 pt-sm-5 mt-lg-5 container align-items-center flex-fill">
        <div class="row mb-2">
            <div class="col-lg-6 fade-in">
                <h1 class="pb-3 fw-bold">Oops! - Form verification error</h1>
                <p class="fs-5 mb-4">Sorry, there was a form verification error.</p>
                <p class="fs-5 mb-4">Please return to the homepage and try again. We are sorry for the inconvenience.
                </p>
                <div class="row pt-2">
                    <div class="col-sm-7">
                        <a href="../../../index.php" class="btn btn-primary ms-0 m-2 ms-0 w-100 p-2 fs-5 rounded-pill"
                            title="Return to homepage">Return to homepage</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-flex">
                <img src="../../../images/calculator.svg" alt="Calculator graphic with a screen that reads, 'OOPS!'."
                    width="450" height="465" class="col w-100 mt-5 mt-lg-0 ps-lg-4 ">
            </div>
        </div>
    </main>
    <script src="../../../js/preserve-scroll-position-onload.js"></script>
</body>

<?php include("../../../includes/footer.php"); ?>