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

// Function to convert seconds into minutes and seconds...
function convertSeconds($seconds)
{
    $minutes = floor($seconds / 60);
    $remainingSeconds = $seconds % 60;
    return sprintf("%d minute(s) and %d second(s)", $minutes, $remainingSeconds);
}

// Define the maximum allowed code verification attempts and lockout duration (in seconds)...
const MAX_CODE_VERIFICATION_ATTEMPTS = 6; // 6 Login attempts
const CODE_VERIFICATION_LOCKOUT_TIME = 900; // 15 minutes = 900 seconds

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = array();

    // Check if user is locked out...
    if (isset($_SESSION['code_verification_lockout_time']) && time() < $_SESSION['code_verification_lockout_time']) {
        $remaining_code_verification_lockout_time = $_SESSION['code_verification_lockout_time'] - time();
        $errors['codeVerificationAttemptsExceeded'] = "You are locked out after " . MAX_CODE_VERIFICATION_ATTEMPTS . " verification attempts. Please try again in " . convertSeconds($remaining_code_verification_lockout_time) . ".";

        // Reset code verification attempt counter after lockout...
        $_SESSION['code_verification_attempts'] = 0;
    }

    require('../../../config/connect_budget_database.php');

    // Check if the CSRF token is set and matches the one stored in the session...
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        // CSRF token validation failed...
        header('Location: ../error_pages/form_verification_error.php');
        exit();
    }

    // Regenerate the CSRF token to prevent reuse...
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Securely regenerate the token

    // Honeypot field: If invisible field is filled in by a bot, do not submit the form...
    if (!empty($_POST['honeypot'])) {
        // "Bot detected. Form submission aborted...
        header('Location: ../error_pages/form_verification_error.php');
        exit();
    }

    // Check if user is resending a confirmation code...
    if (isset($_POST['resend_code']) && $_POST['resend_code'] == '1') {
        // Resend confirmation code email...
        echo '<div class="container col-lg-5 px-3">
                <div class="toast position-fixed top-2 white-background fade show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex flex-column">
                        <button type="button" class="align-self-end btn-close p-3" data-bs-dismiss="toast" aria-label="Close"></button>
                        <div class="toast-body fs-6 p-4 border-top border-1 border-dark-subtle">
                            <p class="mb-0">
                                Another confirmation code has been sent to your email.
                            </p>
                        </div>
                    </div>
                </div>
              </div>';
        require("send_password_reset_email.php");
    } else if (isset($_SESSION['confirmation_code'])) {
        $entered_code = $_POST['confirmation_code'];
        $storedCode = $_SESSION['confirmation_code'];
        $storedTimestamp = $_SESSION['timestamp'];
        $expirationTime = 420; // 7 minutes to expire (in seconds)
        $currentTime = time();
        $timeDifference = $currentTime - $storedTimestamp;

        # Ensure code is correct and hasn't expired before adding user data to the database...
        if ($entered_code == $storedCode && $timeDifference <= $expirationTime && empty($errors)) {
            unset($_SESSION['code_verification_attempts']);
            unset($_SESSION['code_verification_lockout_time']);

            # Navigate to page for user to reset their password...
            header('Location: change_your_password.php');
            exit();
        } else {
            # If confirmation code is not entered correctly, display form with error message appended...
            $errors['incorrectOrExpiredVerificationCode'] = "Incorrect or expired verification code... ";

            // Track number of login attempts...
            if (!isset($_SESSION['code_verification_attempts'])) {
                $_SESSION['code_verification_attempts'] = 0;
            }

            // Add 1 login attempt...
            $_SESSION['code_verification_attempts']++;

            // If user reached login attempt limit, lock them out for the specified amount of time...
            if ($_SESSION['code_verification_attempts'] >= MAX_CODE_VERIFICATION_ATTEMPTS) {
                $_SESSION['code_verification_lockout_time'] = time() + CODE_VERIFICATION_LOCKOUT_TIME;
            }

            require("confirm_password_reset_code_form.php");
            include("../../includes/footer.php");

            echo '<script src="../../js/preserve_scroll_position_onload.js"></script>
                  <script src="../../js/resend_registration_confirmation_code.js"></script>
                  </body>
                  </html>';
        }
    } else {
        header("Location: ../login/login.php");
    }
}
