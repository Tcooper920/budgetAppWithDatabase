<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require('../../../includes/csrf.php'); // Generate CSRF token
require('../controllers/verify-email.php');
require_once("../../../includes/header.php");
echo '<body class="dark-blue-background min-vh-100 d-flex flex-column">';
require("../../../includes/navbar.php");
?>

<main class="d-flex flex-column p-3 mt-5 container col col-md-6 col-xl-4 align-items-center flex-fill">
    <form action="forgot-password.php" method="POST" class="fs-5 p-4 p-sm-5 my-3 bg-light rounded dark-blue mw-27rem">
        <h1 class="pb-3 fw-bold">We will send a password reset code to your inbox.</h1>
        <label class="w-100 mt-2">
            Enter the email address associated with your account:
            <?php if (isset($errors['emailAddressError']))
                echo '<br><p class="error-msg text-danger mb-0">' . $errors['emailAddressError'] . '</p>' ?>
            <input type="text" name="email" value="<?php if (isset($_POST['email']))
                                                        echo htmlspecialchars($_POST['email']); ?>" class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill">
        </label>
        <!-- Honeypot field -->
        <input type="text" name="honeypot" value="" class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill d-none">
        <!-- CSRF token field -->
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>"
            class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill d-none">
        <div class="mt-4">
            <input type="submit" value="Send code" class="btn btn-primary ms-0 m-2 ms-0 w-100 p-2 fs-5 rounded-pill">
        </div>
    </form>
</main>

<?php include("../../../includes/footer.php"); ?>

<script src="../../../js/preserve-scroll-position-onload.js"></script>
</body>

</html>