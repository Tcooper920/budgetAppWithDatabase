<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require("../../../includes/header.php");

echo '<body class="dark-blue-background min-vh-100 d-flex flex-column">';

require("../../../includes/navbar.php");
require("../../../includes/csrf.php"); // Generate CSRF token
require("../controllers/register_user.php");

?>

<main class="d-flex flex-column p-3 mt-5 container col col-md-6 col-xl-4 align-items-center flex-fill">
    <form id="form" action="register.php" method="POST"
        class="fs-5 p-4 p-sm-5 my-3 bg-light rounded dark-blue mw-27rem">
        <!-- Error message container in form header -->
        <div id="error-container"></div>
        <h1 class="pb-3 fw-bold">Register</h1>
        <label class="w-100">
            First Name:
            <?php if (isset($errors['firstNameError']))
                echo '<br><p class="error-msg text-danger mb-0">' . $errors['firstNameError'] . '</p>' ?>
                <input type="text" name="first_name" value="<?php if (isset($_POST['first_name']))
                echo htmlspecialchars($_POST['first_name']); ?>"
                class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill">
        </label>
        <label class="w-100 mt-2">
            Last Name:
            <?php if (isset($errors['lastNameError']))
                echo '<br><p class="error-msg text-danger mb-0">' . $errors['lastNameError'] . '</p>' ?>
                <input type="text" name="last_name" value="<?php if (isset($_POST['last_name']))
                echo htmlspecialchars($_POST['last_name']); ?>"
                class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill">
        </label>
        <label class="w-100 mt-2">
            Email Address:
            <?php if (isset($errors['emailAddressError']))
                echo '<br><p class="error-msg text-danger mb-0">' . $errors['emailAddressError'] . '</p>' ?>
                <input type="text" name="email" value="<?php if (isset($_POST['email']))
                echo htmlspecialchars($_POST['email']); ?>" class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill">
        </label>
        <label class="w-100 mt-2">
            Password:
            <?php if (isset($errors['pass1Error']))
                echo '<br><p class="error-msg text-danger mb-0">' . $errors['pass1Error'] . '</p>' ?>
                <div class="d-flex">
                    <input id="password" type="password" name="pass1" value="<?php if (isset($_POST['pass1']))
                echo htmlspecialchars($_POST['pass1']); ?>"
                    class="form-control m-2 mx-0 p-2 ps-4 fs-5 rounded-pill border-end-0 no-rounded-right input">
                <button class="btn bg-white my-2 rounded-pill no-rounded-left input form-field-border-style"
                    type="button" id="togglePassword">
                    <i class="bi bi-eye"></i> <!-- Bootstrap Icons for eye icon -->
                </button>
            </div>
        </label>
        <label class="w-100 mt-2">
            Confirm Password:
            <?php if (isset($errors['pass2Error']))
                echo '<br><p class="error-msg text-danger mb-0">' . $errors['pass2Error'] . '</p>' ?>
                <input type="password" name="pass2" value="<?php if (isset($_POST['pass2']))
                echo htmlspecialchars($_POST['pass2']); ?>" class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill">
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
            <input type="submit" value="Register" class="btn btn-primary ms-0 m-2 ms-0 w-100 p-2 fs-5 rounded-pill">
        </div>
    </form>
</main>

<?php include("../../../includes/footer.php"); ?>

<script src="../../../js/show_hide_password.js"></script>
<script src="../../../js//verify_recaptcha_response.js"></script>
</body>

</html>