<?php
session_start();
require("includes/header.html");

echo '<body class="dark-blue-background">';

$login_or_logout = "login";
require("includes/navbar.html");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require('../../../connect_budget_database.php');

    if (!isset($_SESSION['confirmation_code'])) {
        echo "Confirmation code is not set.";
    }

    if (isset($_SESSION['confirmation_code'])) {
        $entered_code = $_POST['confirmation_code'];
        
        if ($entered_code == $_SESSION['confirmation_code']) {

            # Add the user to the database...
            $user_data = $_SESSION['user_data'];
            $query_to_create_new_user = $budget_database->prepare("INSERT INTO users (first_name, last_name, email, pass, reg_date) VALUES (?, ?, ?, SHA2(?, 256), NOW())");
            $query_to_create_new_user->bind_param("ssss", $user_data['first_name'], $user_data['last_name'], $user_data['email'], $user_data['password']);
            $query_to_create_new_user->execute();

            if ($query_to_create_new_user) {
                echo '<main class="p-3 mt-5 container col col-md-8 col-lg-6 col-xl-5 align-items-center">
                        <div class="container fs-5 d-flex flex-column p-4 p-sm-5 my-5 bg-light rounded darkest">
                            <h1 class="pb-3">Registered!</h1>
                            <p>You are now registered.</p>
                            <div class="mt-3">
                                <a href="login.php" title="Login">
                                    <input type="button" value="Login" class="btn btn-primary ms-0 m-2 ms-0 w-100 p-2 fs-5 rounded-pill">
                                </a>
                            </div>
                        </div>
                      </main>';
            }
            mysqli_close($budget_database);
            session_destroy();
            exit();
        } else {
            echo '<form action="confirm_code.php" method="POST" class="p-3 mt-5 container col col-md-8 col-lg-6 col-xl-5 align-items-center">
                    <div class="container fs-5 d-flex flex-column p-4 p-sm-5 my-5 bg-light rounded darkest">
                        <h1 class="pb-3">Validate your account</h1>
                        <label for="confirmation_code">Enter the confirmation code sent to your email:</label>
                        <div class="mt-3">
                            <p class="error-msg text-danger mb-0">Incorrect verification code.</p>
                            <input type="text" name="confirmation_code" class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill" required>
                        </div>
                        <div class="mt-3">
                            <input type="submit" value="Confirm code" class="btn btn-primary ms-0 m-2 ms-0 w-100 p-2 fs-5 rounded-pill">
                        </div>
                    </div>
                </form>';            
        }
    } 
}

echo '<script src="../js/preserve_scroll_position_onload.js"></script>
      </body>';

include("includes/footer.html");
