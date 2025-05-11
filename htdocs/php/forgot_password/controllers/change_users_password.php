<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once('../../../../config/connect_budget_database.php');
    $errors = array();

    // Check if the CSRF token is set and matches the one stored in the session...
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        // CSRF token validation failed...
        header('Location: ../../error_pages/views/form_verification_error.php');
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
                    <a href="../../login/views/login.php" title="Login">
                        <input type="button" value="Login" class="btn btn-primary ms-0 m-2 ms-0 w-100 p-2 fs-5 rounded-pill">
                    </a>
                </div>
            </div>
          </main>
          <script src="../../../js/preserve_scroll_position_onload.js"></script>
          </body>';
        } else {
            error_log("Error updating password: " . $query_to_update_password->error);
            echo 'An error occurred.';
        }

        // Honeypot field: If invisible field is filled in by a bot, do not submit the form...
        if (!empty($_POST['honeypot'])) {
            // Bot detected. Form submission aborted...
            header('Location: ../../error_pages/views/form_verification_error.php');
            exit();
        }

        mysqli_close($budget_database);
        session_destroy();
        include("../../../includes/footer.php");
        exit();
    }
}