<?php

session_start();

function load($page = '../login/login.php') {
    $url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);

    $url = rtrim($url, '/\\');
    $url .= '/' . $page;

    header("Location: $url");
    exit();
}

// Function to convert seconds into minutes and seconds...
function convertSeconds($seconds) {
    $minutes = floor($seconds / 60);
    $remainingSeconds = $seconds % 60;
    return sprintf("%d minute(s) and %d second(s)", $minutes, $remainingSeconds);
}

// Define the maximum allowed login attempts and lockout duration (in seconds)...
const MAX_LOGIN_ATTEMPTS = 6; // 6 Login attempts
const LOCKOUT_TIME = 900; // 15 minutes = 900 seconds

// Ensure that email and password fields have been filled...
function validate($budget_database, $email = '' , $pwd = '') {
    $errors = array();

    // Check if user is locked out...
    if (isset($_SESSION['lockout_time']) && time() < $_SESSION['lockout_time']) {
        $remaining_lockout_time = $_SESSION['lockout_time'] - time();
        $errors["youAreLockedOutError"] = "You are locked out after " . MAX_LOGIN_ATTEMPTS . " login attempts. Please try again in " . convertSeconds($remaining_lockout_time) . ".";
        
        // Reset login attempt counter after lockout...
        $_SESSION['login_attempts'] = 0;
    }

    // Check that email has been filled...
    if (empty($email)) {
        $errors["emailError"] = "Enter your email address.";
    } else {
        $email = mysqli_real_escape_string($budget_database, trim($email));
    }

    // Check that password has been filled...
    if (empty($pwd)) {
        $errors["passwordError"] = "Enter your password.";
    } else {
        $pass = mysqli_real_escape_string($budget_database, trim($pwd));
    }

    // Honeypot field: If invisible field is filled in by a bot, do not submit the form...
    if (!empty($_POST['honeypot'])) {
        exit("Bot detected. Form submission aborted.");
    }

    // If form field entries contain no errors...
    if (empty($errors)) {
        $query_to_get_user = $budget_database->prepare("SELECT user_id, first_name, last_name FROM users WHERE email = ? AND pass = SHA2(?, 256)");
        $query_to_get_user->bind_param("ss", $email, $pass);
        $query_to_get_user->execute();
        $get_user = $query_to_get_user->get_result();

        // Check if user exists in the database by looking for a profile with a matching email and password...
        if (mysqli_num_rows($get_user) == 1) {
            // Reset login attempts on successful login...
            unset($_SESSION['login_attempts']);
            unset($_SESSION['lockout_time']);

            // Get user data...
            $row = mysqli_fetch_array($get_user, MYSQLI_ASSOC);
            return array(true, $row);
        } else {
            // Else if email or password not found in database:

            // Popup message...
            $errors["emailAndPasswordNotFoundError"] = 'An account could not be found.';

            // Email error message...
            $errors["emailError"] = "Email may be incorrect.";

            // Password error message...
            $errors["passwordError"] = "Password may be incorrect.";

            // Track number of login attempts...
            if (!isset($_SESSION['login_attempts'])) {
                $_SESSION['login_attempts'] = 0;
            }

            // Add 1 login attempt...
            $_SESSION['login_attempts']++;

            // If user reached login attempt limit, lock them out for the specified amount of time...
            if ($_SESSION['login_attempts'] >= MAX_LOGIN_ATTEMPTS) {
                $_SESSION['lockout_time'] = time() + LOCKOUT_TIME;
            }
        }
    }
    return array(false, $errors);
}


