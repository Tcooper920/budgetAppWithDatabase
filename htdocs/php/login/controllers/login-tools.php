<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function to convert seconds into minutes and seconds...
function convertSeconds($seconds)
{
    $minutes = floor($seconds / 60);
    $remainingSeconds = $seconds % 60;
    return sprintf("%d minute(s) and %d second(s)", $minutes, $remainingSeconds);
}

// Define the maximum allowed login attempts and lockout duration (in seconds)...
const MAX_LOGIN_ATTEMPTS = 6; // 6 Login attempts
const LOCKOUT_TIME = 900; // 15 minutes = 900 seconds

// Function to track login attempts...
function trackLoginAttempts()
{
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

// Ensure that email and password fields have been filled...
function validate($budget_database, $email = '', $pwd = '')
{
    $errors = array();

    // Check if user is locked out...
    if (isset($_SESSION['lockout_time']) && time() < $_SESSION['lockout_time']) {
        $remaining_lockout_time = $_SESSION['lockout_time'] - time();
        $errors["youAreLockedOutError"] = "You are locked out after " . MAX_LOGIN_ATTEMPTS . " login attempts. Please try again in " . convertSeconds($remaining_lockout_time) . ".";

        // Don't reset login attempts yet, return early
        return array(false, $errors);
    }

    // Check that email has been filled...
    $email =  strtolower(trim($email));
    if (empty($email)) {
        $errors["emailError"] = "Enter your email address.";
    }

    // Check that password has been filled...
    $pass = trim($pwd);
    if (empty($pass)) {
        $errors["passwordError"] = "Enter your password.";
    }

    // Honeypot field: If invisible field is filled in by a bot, do not submit the form...
    if (!empty($_POST['honeypot'])) {
        // Bot detected. Form submission aborted...
        header('Location: ../../error-pages/views/form-verification-error.php');
        exit;
    }

    // If form field entries contain no errors...
    if (empty($errors)) {
        $query_to_get_user = $budget_database->prepare("SELECT user_id, first_name, last_name, pass FROM users WHERE email = ?");
        $query_to_get_user->bind_param("s", $email);
        $query_to_get_user->execute();
        $get_user = $query_to_get_user->get_result();

        // If user found in database...
        if ($get_user->num_rows > 0) {
            $user = $get_user->fetch_assoc();
            $stored_hash = $user['pass'];  // The hashed password stored in the database

            // Verify the entered password against the stored hash using password_verify
            if (password_verify($pass, $stored_hash)) {
                // Reset login attempts on successful login...
                unset($_SESSION['login_attempts']);
                unset($_SESSION['lockout_time']);

                // Return user data on successful login...
                return array(true, $user);
            } else {
                $errors["emailAndPasswordNotFoundError"] = 'Email or password may be incorrect.';
                trackLoginAttempts();
            }
        } else {
            $errors["emailAndPasswordNotFoundError"] = 'Email or password may be incorrect.';
            trackLoginAttempts();
        }
    }

    return array(false, $errors);
}
