<?php
session_start();
require("../../includes/header.php");

echo '<body class="dark-blue-background min-vh-100 d-flex flex-column">';

require("../../includes/navbar.php");

// Generate a CSRF (Cross-Site Request Forgery) token if one does not exist...
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate a secure random token
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require('../../../../connect_budget_database.php');
    $errors = array();

    // Check if the CSRF token is set and matches the one stored in the session...
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        // CSRF token validation failed...
        header('Location: ../error_pages/form_verification_error.php');
        exit;
    }

    // Regenerate the CSRF token to prevent reuse...
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Securely regenerate the token

    // Honeypot field: If invisible field is filled in by a bot, do not submit the form...
    if (!empty($_POST['honeypot'])) {
        // Bot detected. Form submission aborted...
        header('Location: ../error_pages/form_verification_error.php');
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
        require("send_password_reset_email.php");
        exit();
    }
}

?>

<main class="d-flex flex-column p-3 mt-5 container col col-md-6 col-xl-4 align-items-center flex-fill">
    <form action="forgot_password.php" method="POST" class="fs-5 p-4 p-sm-5 my-3 bg-light rounded dark-blue">
        <h1 class="pb-3">We will send a password reset code to your inbox.</h1>
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
            <input type="submit" value="Confirm" class="btn btn-primary ms-0 m-2 ms-0 w-100 p-2 fs-5 rounded-pill">
        </div>
    </form>
</main>

<?php include("../../includes/footer.php"); ?>

<script src="../../js/preserve_scroll_position_onload.js"></script>
</body>

</html>