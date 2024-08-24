<?php

require ("includes/header.html");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require ('../../../connect_budget_database.php');
    $errors = array();

    if (empty($_POST['first_name'])) {
        $errors[] = 'Enter your first name.';
    } else {
        $fn = mysqli_real_escape_string($budget_database, trim($_POST['first_name']));
    }

    if (empty($_POST['last_name'])) {
        $errors[] = 'Enter your last name.';
    } else {
        $ln = mysqli_real_escape_string($budget_database, trim($_POST['last_name']));
    }

    if (empty($_POST['email'])) {
        $errors[] = 'Enter your email address.';
    } else {
        $e = mysqli_real_escape_string($budget_database, trim($_POST['email']));
    }

    if (!empty($_POST['pass1'])) {
        if ($_POST['pass1'] != $_POST['pass2']) {
            $errors[] = 'Passwords do not match.';
        } else {
            $p = mysqli_real_escape_string($budget_database, trim($_POST['pass1']));
        }
    } else {
        $errors[] = 'Enter your password.';
    }

    if (empty($errors)) {
        $q = "SELECT user_id FROM users WHERE email='$e'";
        $r = mysqli_query($budget_database, $q);
        if (mysqli_num_rows($r) != 0) {
            $errors[] = 'Email address already registered.
                        <a href="login.php">Login</a>';
        }
    }

    if (empty($errors)) {
        $q = "INSERT INTO users
             (first_name, last_name, email, pass, reg_date)
             VALUES ('$fn', '$ln', '$e', SHA2('$p', 256), NOW())";
        $r = mysqli_query($budget_database, $q);

        if ($r) {
            echo '<h1>Registered!</h1>
                 <p>You are now registered.</p>
                 <p><a href="login.php">Login</a></p>';
        }

        mysqli_close($budget_database);
        exit();
    } else {
        echo '<div class="container col-lg-5 px-3">
              <div class="toast position-fixed top-2 white-background fade show" role="alert" aria-live="assertive" aria-atomic="true">
                  <div class="d-flex flex-column">
                      <button type="button" class="align-self-end btn-close p-3" data-bs-dismiss="toast" aria-label="Close"></button>
                          <div class="toast-body fs-6 p-4 text-danger border-top border-1 border-dark-subtle">
                              <p>';
        foreach ($errors as $msg) {
            echo "- $msg<br>";
        }
        echo '</p>
          <p>Please try again.</p>
          </div>
          </div>
          </div>
          </div>';
        mysqli_close($budget_database);
    }
}

?>

<body class="dark-blue-background white">

    <?php 
        $login_logout_link = "login.php";
        $login_logout_link_text = "Login";
        require ("includes/navbar.html"); 
    ?>

    <main class="p-3 mt-5 container col col-md-6 col-xl-4">
        <h1>Register</h1>
        <form action="register.php" method="POST" class="fs-5 p-4 p-sm-5 my-3 bg-light rounded dark-blue">
            <label class="w-100">
                First Name: <input type="text" name="first_name" value="<?php if (isset($_POST['first_name']))
                    echo $_POST['first_name']; ?>" class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill">
            </label>
            <label class="w-100 mt-2">
                Last Name: <input type="text" name="last_name" value="<?php if (isset($_POST['last_name']))
                    echo $_POST['last_name']; ?>" class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill">
            </label>
            <label class="w-100 mt-2">
                Email Address: <input type="text" name="email" value="<?php if (isset($_POST['email']))
                    echo $_POST['email']; ?>" class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill">
            </label>
            <label class="w-100 mt-2">
                Password: <input type="text" name="pass1" value="<?php if (isset($_POST['pass1']))
                    echo $_POST['pass1']; ?>" class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill">
            </label>
            <label class="w-100 mt-2">
                Confirm Password: <input type="text" name="pass2" value="<?php if (isset($_POST['pass2']))
                    echo $_POST['pass2']; ?>" class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill">
            </label>
            <div class="mt-4">
                <input type="submit" value="Register" class="btn btn-primary ms-0 m-2 ms-0 w-100 p-2 fs-5 rounded-pill">
            </div>
        </form>
    </main>
    <script src="../js/preserve_scroll_position_onload.js"></script>
</body>

<?php include ("includes/footer.html"); ?>