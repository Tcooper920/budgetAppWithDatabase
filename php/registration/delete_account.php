<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    require('../login/login_tools.php');
    load();
}

// Generate a CSRF (Cross-Site Request Forgery) token if one does not exist...
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate a secure random token
}

require("../../includes/header.php");

echo '<body class="dark-blue-background min-vh-100 d-flex flex-column">';

require("../../includes/navbar.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Honeypot field: If invisible field is filled in by a bot, do not submit the form...
    if (!empty($_POST['honeypot'])) {
        exit("Bot detected. Form submission aborted.");
    }

    // Check if the CSRF token is set and matches the one stored in the session...
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        exit('CSRF token validation failed.');
    }

    // Regenerate the CSRF token to prevent reuse...
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Securely regenerate the token

    $delete_account_input_field_confirmation = htmlspecialchars($_POST['delete_account_field'], ENT_QUOTES, 'UTF-8');

    // Check if user typed "delete my account" in the input field...
    if ($delete_account_input_field_confirmation === "delete my account") {

        require('../../../../connect_budget_database.php');

        $userid = $_SESSION['user_id'];

        # Delete stored user from "users" SQL table... 
        $query_to_delete_user = $budget_database->prepare("DELETE FROM users WHERE user_id = ?");
        $query_to_delete_user->bind_param("i", $userid);
        $query_to_delete_user->execute();
        $query_to_delete_user->close();

        # Delete stored user expenses from "user_expenses" SQL table...
        $query_to_delete_user_expenses = $budget_database->prepare("DELETE FROM user_expenses WHERE user_id = ?");
        $query_to_delete_user_expenses->bind_param("i", $userid);
        $query_to_delete_user_expenses->execute();
        $query_to_delete_user_expenses->close();

        # Delete stored user income from "user_income" SQL table...
        $query_to_delete_user_income = $budget_database->prepare("DELETE FROM user_income WHERE user_id = ?");
        $query_to_delete_user_income->bind_param("i", $userid);
        $query_to_delete_user_income->execute();
        $query_to_delete_user_income->close();

        # Make sure the user, user expenses, and user income have been deleted from all three tables...

        # Make sure user no longer exists... 
        $query_to_get_user_by_user_id = $budget_database->prepare("SELECT user_id FROM users WHERE user_id = ?");
        $query_to_get_user_by_user_id->bind_param("i", $userid);
        $query_to_get_user_by_user_id->execute();
        $get_user_by_user_id = $query_to_get_user_by_user_id->get_result();
        $query_to_get_user_by_user_id->close();

        # Make sure user's expenses no longer exist...
        $query_to_get_expenses_by_user_id = $budget_database->prepare("SELECT user_id FROM user_expenses WHERE user_id = ?");
        $query_to_get_expenses_by_user_id->bind_param("i", $userid);
        $query_to_get_expenses_by_user_id->execute();
        $get_expenses_by_user_id = $query_to_get_expenses_by_user_id->get_result();
        $query_to_get_expenses_by_user_id->close();

        # Make sure user's income no longer exists...
        $query_to_get_income_by_user_id = $budget_database->prepare("SELECT user_id FROM user_income WHERE user_id = ?");
        $query_to_get_income_by_user_id->bind_param("i", $userid);
        $query_to_get_income_by_user_id->execute();
        $get_income_by_user_id = $query_to_get_income_by_user_id->get_result();
        $query_to_get_income_by_user_id->close();

        # Check the tables to make sure no rows exist with the user_id (make sure everything was deleted successfully)...
        if ($get_user_by_user_id->num_rows > 0 || $get_expenses_by_user_id->num_rows > 0 || $get_income_by_user_id->num_rows > 0) {
            # Display error message if failed to delete user info...
            echo '
            <main class="d-flex flex-column p-3 mt-5 container col col-md-6 col-xl-4 align-items-center flex-fill">
                <form action="delete_account.php" method="POST" class="container fs-5 d-flex flex-column p-4 p-sm-5 my-5 bg-light rounded darkest">
                    <h1 class="pb-3 text-danger">An error occurred.</h1>
                    <p class="error-msg text-danger">Your account was not deleted. Please try again.</p>
                    <label class="w-100 text-danger">
                        Type "delete my account" to confirm account deletion. Then click the "Delete my account" button.
                        <input type="text" placeholder="delete my account" name="delete_account_field" value="" class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill">
                    </label>
                    <!-- Honeypot field -->
                    <input type="text" name="honeypot" value=""
                        class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill d-none">
                    <!-- CSRF token field -->
                    <input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '"
                        class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill d-none">
                    <div class="mt-3">
                        <input type="submit" value="Delete my account" class="btn warning ms-0 m-2 ms-0 w-100 p-2 fs-5 rounded-pill">
                        <a href="../dashboard/dashboard.php" title="Back to homepage">
                            <input type="button" value="Back" class="btn border-primary-color border-2 ms-0 m-2 ms-0 w-100 p-2 fs-5 rounded-pill">
                        </a>
                    </div>
                </form>
            </main>';

            include("../../includes/footer.php");

            echo '
            <script src="../js/preserve_scroll_position_onload.js"></script>
            </body>
            </html>';

            exit();
        } else {
            # Display success message and delete user session...
            $_SESSION = array();
            session_destroy();

            echo '
            <main class="d-flex flex-column p-3 mt-5 container col col-md-6 col-xl-4 align-items-center flex-fill">
                <form action="delete_account.php" method="POST" class="container fs-5 d-flex flex-column p-4 p-sm-5 my-5 bg-light rounded darkest">
                    <h1 class="pb-3">Your account has been successfully deleted.</h1>
                    <p>We hope you come back!</p>
                    <div class="mt-3">
                        <a href="../dashboard/dashboard.php" title="Return to login">
                            <input type="button" value="Return to login" class="btn btn-primary ms-0 m-2 ms-0 w-100 p-2 fs-5 rounded-pill">
                        </a>
                    </div>
                </form>
            </main>';

            include("../../includes/footer.php");

            echo '
            <script src="../../js/preserve_scroll_position_onload.js"></script>
            </body>
            </html>';

            # Free up memory after the result set is no longer needed...
            $get_user_by_user_id->free();
            $get_expenses_by_user_id->free();
            $get_income_by_user_id->free();

            exit();

        }
    }
}

?>

<main class="d-flex flex-column p-3 mt-5 container col col-md-6 col-xl-4 align-items-center flex-fill">
    <form action="delete_account.php" method="POST"
        class="container fs-5 d-flex flex-column p-4 p-sm-5 my-5 bg-light rounded darkest">
        <h1 class="pb-3">Are you sure you want to delete your account?</h1>
        <label class="w-100 text-danger">
            Type 'delete my account' to confirm account deletion. Then click the 'Delete my account' button.
            <input type="text" placeholder="delete my account" name="delete_account_field" value=""
                class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill">
        </label>
        <!-- Honeypot field -->
        <input type="text" name="honeypot" value=""
            class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill d-none">
        <!-- CSRF token field -->
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>"
            class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill d-none">
        <div class="mt-3">
            <input type="submit" value="Delete my account"
                class="btn warning ms-0 m-2 ms-0 w-100 p-2 fs-5 rounded-pill">
            <a href="../dashboard/dashboard.php" title="Back to homepage">
                <input type="button" value="Back"
                    class="btn border-primary-color border-2 ms-0 m-2 ms-0 w-100 p-2 fs-5 rounded-pill">
            </a>
        </div>
    </form>
</main>

<?php include("../../includes/footer.php"); ?>
</body>

</html>