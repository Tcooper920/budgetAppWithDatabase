<?php
session_start();
require("../../includes/header.php");

echo '<body class="dark-blue-background min-vh-100 d-flex flex-column">';

require("../../includes/navbar.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require('../../../../connect_budget_database.php');

    // Check if the CSRF token is set and matches the one stored in the session...
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        exit('CSRF token validation failed.');
    }

    // Regenerate the CSRF token to prevent reuse...
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Securely regenerate the token

    // Honeypot field: If invisible field is filled in by a bot, do not submit the form...
    if (!empty($_POST['honeypot'])) {
        exit("Bot detected. Form submission aborted.");
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
        require("send_confirmation_email.php");
    } else if (isset($_SESSION['confirmation_code'])) {
        $entered_code = $_POST['confirmation_code'];
        $storedCode = $_SESSION['confirmation_code'];
        $storedTimestamp = $_SESSION['timestamp'];
        $expirationTime = 420; // 7 minutes to expire (in seconds)
        $currentTime = time();
        $timeDifference = $currentTime - $storedTimestamp;

        # Ensure code is correct and hasn't expired before adding user data to the database...
        if ($entered_code == $storedCode && $timeDifference <= $expirationTime) {

            # Add the user to the database...
            $user_data = $_SESSION['user_data'];
            $query_to_create_new_user = $budget_database->prepare("INSERT INTO users (first_name, last_name, email, pass, reg_date) VALUES (?, ?, ?, SHA2(?, 256), NOW())");
            $query_to_create_new_user->bind_param("ssss", $user_data['first_name'], $user_data['last_name'], $user_data['email'], $user_data['password']);
            $query_to_create_new_user->execute();

            if ($query_to_create_new_user) {
                echo '<main class="d-flex flex-column p-3 mt-5 container col col-md-6 col-xl-4 align-items-center flex-fill">
                        <div class="container fs-5 d-flex flex-column p-4 p-sm-5 my-5 bg-light rounded darkest">
                            <h1 class="pb-3">Registered!</h1>
                            <p>You are now registered.</p>
                            <div class="mt-3">
                                <a href="../login/login.php" title="Login">
                                    <input type="button" value="Login" class="btn btn-primary ms-0 m-2 ms-0 w-100 p-2 fs-5 rounded-pill">
                                </a>
                            </div>
                        </div>
                      </main>
                      <script src="../../js/preserve_scroll_position_onload.js"></script>
                      </body>';
            }
            mysqli_close($budget_database);
            session_destroy();
            include("../../includes/footer.php");
            exit();
        } else {
            # If confirmation code is not entered correctly, display form with error message appended...
            $errors['incorrectOrExpiredVerificationCode'] = "Incorrect or expired verification code...";
            require("confirm_code_form.php");
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
