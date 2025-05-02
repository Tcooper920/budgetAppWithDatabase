<?php
session_start();
/* Check if the user manually visited this page. If so, redirect them to the beginning of the 
process to reset their password. (This prevents them from skipping any steps.) */
if (!isset($_SESSION['user_resetting_password']) || !$_SESSION['user_resetting_password'] == true) {
    header('Location: forgot_password.php');
    exit();
}
require("../../includes/header.php");

echo '<body class="dark-blue-background min-vh-100 d-flex flex-column">';

require("../../includes/navbar.php");

// Generate a CSRF (Cross-Site Request Forgery) token if one does not exist...
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate a secure random token
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once('../../../config/connect_budget_database.php');
    $errors = array();

    // Check if the CSRF token is set and matches the one stored in the session...
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        // CSRF token validation failed...
        header('Location: ../error_pages/form_verification_error.php');
        exit();
    }

    // Regenerate the CSRF token to prevent reuse...
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Securely regenerate the token

    # Make sure password 1 is filled in...
    if (empty($_POST['pass1'])) {
        $errors["pass1Error"] = "Enter your password.";
    }

    # Make sure password 2 is filled in (for password confirmation)...
    if (empty($_POST['pass2'])) {
        $errors["pass2Error"] = "Confirm your password.";
    }

    # Make sure password is long enough (for security)...
    if (strlen(trim($_POST['pass1'])) < 9) {
        $errors['pass1Error'] = 'Password must be at least 9 characters long.';
    }

    # If pass1 and pass2 are filled in...
    if (!empty($_POST['pass1']) && !empty($_POST['pass2'])) {
        # Check if password 1 and password 2 match...
        if (trim($_POST['pass1']) !== trim($_POST['pass2'])) {
            $errors["pass2Error"] = "Passwords do not match.";
        } else {
            # Hash password...
            $p = password_hash(trim($_POST['pass1']), PASSWORD_DEFAULT);
        }
    }

    # Update user's password...
    if (empty($errors)) {
        $query_to_update_password = $budget_database->prepare("UPDATE users SET pass = ? WHERE email = ?");
        $query_to_update_password->bind_param("ss", $p, $_SESSION['email']);
        $query_to_update_password->execute();

        if ($query_to_update_password->affected_rows > 0) {
            echo '<main class="d-flex flex-column p-3 mt-5 container col col-md-6 col-xl-4 align-items-center flex-fill">
            <div class="container fs-5 d-flex flex-column p-4 p-sm-5 my-5 bg-light rounded darkest mw-27rem">
                <h1 class="pb-3 fw-bold">Password updated!</h1>
                <p>Your password has been updated successfully.</p>
                <div class="mt-3">
                    <a href="../login/login.php" title="Login">
                        <input type="button" value="Login" class="btn btn-primary ms-0 m-2 ms-0 w-100 p-2 fs-5 rounded-pill">
                    </a>
                </div>
            </div>
          </main>
          <script src="../../js/preserve_scroll_position_onload.js"></script>
          </body>';
        } else {
            error_log("Error updating password: " . $query_to_update_password->error);
            echo 'An error occurred.';
        }

        // Honeypot field: If invisible field is filled in by a bot, do not submit the form...
        if (!empty($_POST['honeypot'])) {
            // Bot detected. Form submission aborted...
            header('Location: ../error_pages/form_verification_error.php');
            exit();
        }

        mysqli_close($budget_database);
        session_destroy();
        include("../../includes/footer.php");
        exit();
    }
}

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

<?php include("../../includes/footer.php"); ?>

<script src="../../js/preserve_scroll_position_onload.js"></script>
<script src="../../js/show_hide_password.js"></script>
</body>

</html>