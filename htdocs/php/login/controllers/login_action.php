<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once('../../../../config/connect_budget_database.php');
    require('login_tools.php');
    require('../../../includes/recaptcha_verification.php');

    // Check if the CSRF token is set and matches the one stored in the session...
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        // CSRF token validation failed...
        header('Location: ../../error_pages/views/form_verification_error.php');
        exit;
    }

    // Regenerate the CSRF token to prevent reuse...
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Securely regenerate the token

    list($check, $data) = validate($budget_database, $_POST['email'], $_POST['pass']);

    if ($check) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['user_id'] = $data['user_id'];
        $_SESSION['first_name'] = $data['first_name'];
        $_SESSION['last_name'] = $data['last_name'];

        header('Location: ../../dashboard/views/dashboard.php');
    } else {
        $errors = $data;
    }
    mysqli_close($budget_database);
}

include('../views/login.php');