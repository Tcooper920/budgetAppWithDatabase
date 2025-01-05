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

    # Make sure password 1 is filled in...
    if (empty($_POST['pass1'])) {
        $errors["pass1Error"] = "Enter your password.";
    }

    if (!empty($_POST['pass1'])) {
        # Check if password 1 and password 2 match...
        if ($_POST['pass1'] !== $_POST['pass2']) {
            $errors["pass2Error"] = "Passwords do not match.";
        } else {
            # Make sure password isn't already used by another user...
            $query_to_get_user_by_password = $budget_database->prepare("SELECT user_id FROM users WHERE pass=SHA2(?, 256)");
            $query_to_get_user_by_password->bind_param("s", $_POST['pass1']);
            $query_to_get_user_by_password->execute();
            $get_user_by_password = $query_to_get_user_by_password->get_result();

            if (mysqli_num_rows($get_user_by_password) != 0) {
                $errors["pass1Error"] = "This password is already taken.";
            } else {
                $p = mysqli_real_escape_string($budget_database, trim($_POST['pass1']));
            }
        }
    }

    # Check if user email already exists during registration...
    if (empty($errors)) {
        $query_to_get_user_by_email = $budget_database->prepare("SELECT user_id FROM users WHERE email=?");
        $query_to_get_user_by_email->bind_param("s", $e);
        $query_to_get_user_by_email->execute();
        $get_user_by_email = $query_to_get_user_by_email->get_result();

        if (mysqli_num_rows($get_user_by_email) != 0) {
            $errors["userEmail"] = "Email address already registered. <a href='../login/login.php'>Login</a>";
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
        header('Location: ../error_pages/form_verification_error.php');
        exit;
    }

    # Create a new user in the database...
    if (empty($errors)) {
        # Store user data in session...
        $_SESSION['user_data'] = array('first_name' => $fn, 'last_name' => $ln, 'email' => $e, 'password' => $p);
        # Generate confirmation code, send the code via email, and show a form for the user to enter the code...
        require("send_confirmation_email.php");
        exit();
    }
}

?>

<main class="d-flex flex-column p-3 mt-5 container col col-md-6 col-xl-4 align-items-center flex-fill">
    <form action="register.php" method="POST" class="fs-5 p-4 p-sm-5 my-3 bg-light rounded dark-blue">
        <h1 class="pb-3">Register</h1>
        <label class="w-100">
            First Name:
            <?php if (isset($errors['firstNameError']))
                echo '<br><p class="error-msg text-danger mb-0">' . $errors['firstNameError'] . '</p>' ?>
                <input type="text" name="first_name" value="<?php if (isset($_POST['first_name']))
                echo htmlspecialchars($_POST['first_name']); ?>"
                class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill">
        </label>
        <label class="w-100 mt-2">
            Last Name:
            <?php if (isset($errors['lastNameError']))
                echo '<br><p class="error-msg text-danger mb-0">' . $errors['lastNameError'] . '</p>' ?>
                <input type="text" name="last_name" value="<?php if (isset($_POST['last_name']))
                echo htmlspecialchars($_POST['last_name']); ?>"
                class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill">
        </label>
        <label class="w-100 mt-2">
            Email Address:
            <?php if (isset($errors['emailAddressError']))
                echo '<br><p class="error-msg text-danger mb-0">' . $errors['emailAddressError'] . '</p>' ?>
                <input type="text" name="email" value="<?php if (isset($_POST['email']))
                echo htmlspecialchars($_POST['email']); ?>" class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill">
        </label>
        <label class="w-100 mt-2">
            Password:
            <?php if (isset($errors['pass1Error']))
                echo '<br><p class="error-msg text-danger mb-0">' . $errors['pass1Error'] . '</p>' ?>
                <div class="d-flex">
                    <input id="password" type="password" name="pass1" value="<?php if (isset($_POST['pass1']))
                    echo htmlspecialchars($_POST['pass1']); ?>" class="form-control m-2 mx-0 p-2 ps-4 fs-5 rounded-pill border-end-0 no-rounded-right input">
                    <button class="btn bg-white my-2 rounded-pill no-rounded-left input form-field-border-style" type="button" id="togglePassword">
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
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill d-none">
        <div class="mt-4">
            <input type="submit" value="Register" class="btn btn-primary ms-0 m-2 ms-0 w-100 p-2 fs-5 rounded-pill">
        </div>
    </form>
</main>

<?php include("../../includes/footer.php"); ?>

<script src="../../js/preserve_scroll_position_onload.js"></script>
<script src="../../js/show_hide_password.js"></script>
</body>
</html>