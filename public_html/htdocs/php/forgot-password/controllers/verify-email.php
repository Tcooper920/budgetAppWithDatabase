<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once('../../../../../config/connect-budget-database.php');
    $errors = array();

    // Check if the CSRF token is set and matches the one stored in the session...
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        // CSRF token validation failed...
        header('Location: ../../error-pages/views/form-verification-error.php');
        exit;
    }

    // Regenerate the CSRF token to prevent reuse...
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Securely regenerate the token

    // Honeypot field: If invisible field is filled in by a bot, do not submit the form...
    if (!empty($_POST['honeypot'])) {
        // Bot detected. Form submission aborted...
        header('Location: ../../error-pages/views/form-verification-error.php');
        exit();
    }

    # Function to verify emails...
    function isValidEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    # Make sure email is filled in...
    if (empty($_POST['email'])) {
        $errors["emailAddressError"] = "Enter your email address.";
    } else {
        $email = $_POST['email'];
        if (isValidEmail($email)) {
            $e = mysqli_real_escape_string($budget_database, trim($_POST['email']));

            # Check if user's email exists in the system...
            $query_to_get_user_by_email = $budget_database->prepare("SELECT user_id FROM users WHERE email=?");
            $query_to_get_user_by_email->bind_param("s", $e);
            $query_to_get_user_by_email->execute();
            $get_user_by_email = $query_to_get_user_by_email->get_result();

            # If user's email exists...
            if (mysqli_num_rows($get_user_by_email) != 0) {
                # Store user email in session...
                $_SESSION['email'] = $e;

                # Save session variable showing user is resetting password...
                $_SESSION['user_resetting_password'] = true;
            } else {
                $errors["emailAddressError"] = "This email has not been registered and does not have an associated account.";
            }
        } else {
            $errors["emailAddressError"] = "This email address is not valid.";
        }
    }

    # If no errors, send confirmation code via email...
    if (empty($errors)) {
        require("send-password-reset-email.php");
        exit();
    }
}
