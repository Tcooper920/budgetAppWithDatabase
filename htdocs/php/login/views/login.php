<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require('../../../includes/csrf.php'); // Generate CSRF token

if (isset($_SESSION['user_id'])) {
    header("Location: ../../dashboard/views/dashboard.php");
    exit();
}

require("../../../includes/header.php");

?>

<body class="dark-blue-background white min-vh-100 d-flex flex-column">
    <?php require("../../../includes/navbar.php"); ?>

    <main class="d-flex flex-column p-3 mt-5 container col col-md-6 col-xl-4 align-items-center flex-fill">
        <img src="../../../images/buildabudge-logo-stacked.svg" alt="BuildaBudge logo" title="BuildaBudge"
            width="240" height="240" class="d-none d-sm-block mb-3">
        <form id="form" action="/budget_app_with_database/htdocs/php/login/controllers/login-action.php" method="POST"
            class="fs-5 p-4 p-sm-5 my-3 bg-light rounded dark-blue mw-27rem">

            <!-- Error message container in form header -->
            <div id="error-container">
                <?php

                // Show error if user is locked out...
                if (isset($errors['youAreLockedOutError'])) {
                    echo '<p class="error-msg text-danger mb-3">' . $errors['youAreLockedOutError'] . '</p>';
                }
                // Show error if email or password not found...
                if (isset($errors['emailAndPasswordNotFoundError'])) {
                    echo '<p class="error-msg text-danger mb-3">' . $errors['emailAndPasswordNotFoundError'] . '</p>';
                    $errors['emailError'] = " "; // Triggers CSS to make field outline red
                    $errors['passwordError'] = " "; // Triggers CSS to make field outline red
                }

                ?>
            </div>
            <h1 class="pb-3 fw-bold">Login</h1>
            <label class="w-100">
                Email Address:
                <?php if (isset($errors['emailError']))
                    echo '<br><p class="error-msg text-danger mb-0">' . $errors['emailError'] . '</p>' ?>
                    <input type="text" name="email" autocomplete="on" value="<?php if (isset($_POST['email']))
                    echo htmlspecialchars($_POST["email"]); ?>"
                    class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill">
            </label>
            <label class="w-100 mt-2">
                Password:
                <?php if (isset($errors['passwordError']))
                    echo '<br><p class="error-msg text-danger mb-0">' . $errors['passwordError'] . '</p>' ?>
                    <div class="d-flex"><input id="password" type="password" name="pass" autocomplete="off" value="<?php if (isset($_POST['pass']))
                    echo htmlspecialchars($_POST["pass"]); ?>"
                        class="form-control m-2 mx-0 p-2 ps-4 fs-5 rounded-pill border-end-0 no-rounded-right input">
                    <button class="btn bg-white my-2 rounded-pill no-rounded-left input form-field-border-style"
                        type="button" id="togglePassword">
                        <i class="bi bi-eye"></i> <!-- Bootstrap Icons for eye icon -->
                    </button>
                </div>
            </label>
            <!-- Honeypot field -->
            <input type="text" name="honeypot" value="" class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill d-none">
            <!-- CSRF token field -->
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>"
                class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill d-none">
            <!-- reCAPTCHA -->
            <div class="mt-4">
                <div class="g-recaptcha" data-sitekey="6LftRuAqAAAAAIFxD5R1sB5ZnFchGxZMv4OKj_qz" data-theme="light"
                    data-size="normal">
                </div>
            </div>
            <div class="mt-4">
                <input type="submit" value="Log in" class="btn btn-primary ms-0 m-2 ms-0 w-100 p-2 fs-5 rounded-pill"
                    title="Log in">
            </div>
            <div class="mt-1">
                <a href="../../registration/views/register.php" title="Sign up">
                    <input type="button" value="Sign up"
                        class="btn border-primary-color border-2 ms-0 m-2 ms-0 w-100 p-2 fs-5 rounded-pill">
                </a>
                <a href="../../forgot-password/views/forgot-password.php" title="Forgot password?"
                    class="link-underline link-underline-opacity-0 d-block mt-3">Forgot
                    password?</a>
            </div>
        </form>
    </main>

    <?php include("../../../includes/footer.php"); ?>
    <script src="../../../js/preserve-scroll-position-onload.js"></script>
    <script src="../../../js/show-hide-password.js"></script>
    <script src="../../../js/verify-recaptcha-response.js"></script>
</body>

</html>