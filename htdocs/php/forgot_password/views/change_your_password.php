<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* Check if the user manually visited this page. If so, redirect them to the beginning of the 
process to reset their password. (This prevents them from skipping any steps.) */
if (!isset($_SESSION['user_resetting_password']) || !$_SESSION['user_resetting_password'] == true) {
    header('Location: forgot_password.php');
    exit();
}
require("../../../includes/header.php");

echo '<body class="dark-blue-background min-vh-100 d-flex flex-column">';

require("../../../includes/navbar.php");
require('../../../includes/csrf.php'); // Generate CSRF token
require('../controllers/change_users_password.php');
?>

<main class="d-flex flex-column p-3 mt-5 container col col-md-6 col-xl-4 align-items-center flex-fill">
    <form action="change_your_password.php" method="POST"
        class="fs-5 p-4 p-sm-5 my-3 bg-light rounded dark-blue mw-27rem">
        <h1 class="pb-3">Change your password</h1>
        <label class="w-100 mt-2">
            Enter your new Password:
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
        <div class="mt-4">
            <input type="submit" value="Update" class="btn btn-primary ms-0 m-2 ms-0 w-100 p-2 fs-5 rounded-pill">
        </div>
    </form>
</main>

<?php include("../../../includes/footer.php"); ?>

<script src="../../../js/preserve_scroll_position_onload.js"></script>
<script src="../../../js/show_hide_password.js"></script>
</body>

</html>