<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once('../../../../config/connect-budget-database.php');
    require('../../../includes/recaptcha-verification.php');
    $errors = array();

    // Check if the CSRF token is set and matches the one stored in the session...
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        // CSRF token validation failed...
        header('Location: ../../error-pages/views/form-verification-error.php');
        exit;
    }

    // Regenerate the CSRF token to prevent reuse...
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Securely regenerate the token

    # Function to verify emails...
    function isValidEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    # Make sure first name is filled in...
    if (empty($_POST['first_name'])) {
        $errors["firstNameError"] = "Enter your first name.";
    } else {
        $fn = mysqli_real_escape_string($budget_database, trim($_POST['first_name']));
    }

    # Make sure last name is filled in...
    if (empty($_POST['last_name'])) {
        $errors["lastNameError"] = "Enter your last name.";
    } else {
        $ln = mysqli_real_escape_string($budget_database, trim($_POST['last_name']));
    }

    # Make sure email is filled in...
    if (empty($_POST['email'])) {
        $errors["emailAddressError"] = "Enter your email address.";
    } else {
        $email = $_POST['email'];
        if (isValidEmail($email)) {
            $e = mysqli_real_escape_string($budget_database, trim($_POST['email']));
        } else {
            $errors["emailAddressError"] = "This email address is not valid.";
        }
    }

    # Make sure password fields are filled in...
    if (empty(trim($_POST['pass1'])) || empty(trim($_POST['pass2']))) {
        $errors["pass1Error"] = "Enter your password into both fields.";
    }

    # If pass1 and pass2 are filled in...
    if (!empty(trim($_POST['pass1'])) && !empty(trim($_POST['pass2']))) {
        # Check if password 1 and password 2 match...
        if (trim($_POST['pass1']) !== trim($_POST['pass2'])) {
            $errors["pass2Error"] = "Passwords do not match.";
        } else {
            # Make sure password is long enough (for security)...
            if (strlen(trim($_POST['pass1'])) < 9) {
                $errors['pass1Error'] = 'Password must be at least 9 characters long.';
            } else {
                # Hash password...
                $p = password_hash(trim($_POST['pass1']), PASSWORD_DEFAULT);
            }
        }
    }

    # Verify birth date for Children’s Online Privacy Protection Act (COPPA)...
    if (isset($_POST['birthdate']) && !empty($_POST['birthdate'])) {
        $birthDateInput = $_POST['birthdate'];
        $birthDate = new DateTime($birthDateInput);
        $todaysDate = new DateTime();

        $age = $birthDate->diff($todaysDate)->y;

        if ($birthDate > $todaysDate) {
            $errors['birthDateError'] = "Birth date cannot be in the future.";
        } else if ($age < 13) {
            $errors['birthDateError'] = "You must be at least 13 years old to use this app.";
        }
    } else {
        $errors['birthDateError'] = "Please enter your birth date.";
    }

    # Check if user email already exists during registration...
    if (empty($errors)) {
        $query_to_get_user_by_email = $budget_database->prepare("SELECT user_id FROM users WHERE email=?");
        $query_to_get_user_by_email->bind_param("s", $e);
        $query_to_get_user_by_email->execute();
        $get_user_by_email = $query_to_get_user_by_email->get_result();

        if (mysqli_num_rows($get_user_by_email) != 0) {
            $errors["userEmail"] = "Email address already registered. <a href='../../login/views/login.php' class='link-underline link-underline-opacity-0'>Login here</a>";
        }
    }

    # Pop error message if email already exists...
    if (isset($errors['userEmail'])) {
        echo '<div class="container col-lg-5 px-3">
              <div class="toast position-fixed top-2 white-background fade show" role="alert" aria-live="assertive" aria-atomic="true">
                  <div class="d-flex flex-column">
                      <button type="button" class="align-self-end btn-close p-3" data-bs-dismiss="toast" aria-label="Close"></button>
                          <div class="toast-body fs-6 p-4 text-danger border-top border-1 border-dark-subtle">
                              <p class="mb-0">';
        echo $errors['userEmail'] . '<br>';
        echo '</p>
          </div>
          </div>
          </div>
          </div>';
    }

    // Honeypot field: If invisible field is filled in by a bot, do not submit the form...
    if (!empty($_POST['honeypot'])) {
        // Bot detected. Form submission aborted...
        header('Location: ../../error-pages/views/form-verification-error.php');
        exit;
    }

    # Create a new user in the database...
    if (empty($errors)) {
        # Store user data in session...
        $_SESSION['user_data'] = array('first_name' => $fn, 'last_name' => $ln, 'email' => $e, 'password' => $p);
        # Generate confirmation code, send the code via email, and show a form for the user to enter the code...
        require("send-confirmation-email.php");
        exit();
    }
}
