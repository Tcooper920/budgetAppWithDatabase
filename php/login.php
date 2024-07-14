<?php

require ("includes/header.html");

if (isset($errors) && !empty($errors)) {
    echo '<p id="err_msg">Oops! There was a problem:<br>';
    foreach ($errors as $msg) {
        echo "- $msg<br>";
    }
    echo 'Please try again or
         <a href="register.php">Register</a></p>';
}

?>

<body class="dark-blue-background white">
    <main class="p-3 mt-5 container col-lg-5">
        <h1>Login</h1>
        <form action="login_action.php" method="POST" class="fs-5 p-4 my-3 bg-light rounded dark-blue">
            <label class="w-100">
                Email Address: <input type="text" name="email" class="form-control m-2 ms-0">
            </label>
            <label class="w-100">
                Password: <input type="password" name="pass" class="form-control m-2 ms-0">
            </label>
            <div class="mt-3">
                <input type="submit" value="Login" class="btn btn-primary rounded-pill ms-0 m-2 ms-0 w-100">
            </div>
            <div>
                <input type="button" value="Sign up" class="btn rounded-pill border-primary-color border-2 ms-0 m-2 ms-0 w-100">
            </div>
        </form>
    </main>
</body>

<?php include ("includes/footer.html"); ?>