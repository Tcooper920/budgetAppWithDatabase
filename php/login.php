<?php

require ("includes/header.html");

if (isset($errors) && !empty($errors)) {
    echo '<div class="container col-lg-5">
              <div class="container toast w-100 border-0 mt-5 p-0 white-background fade show" role="alert" aria-live="assertive" aria-atomic="true">
                  <div class="d-flex flex-column">
                      <button type="button" class="align-self-end btn-close p-3" data-bs-dismiss="toast" aria-label="Close"></button>
                          <div class="toast-body fs-6 p-4 text-danger border-top border-1 border-dark-subtle">
                              <p class="m-0">';
    foreach ($errors as $msg) {
        echo "$msg<br>";
    }
    echo 'Please try again or
          <a href="register.php">Register</a>
          </p>
          </div>
          </div>
          </div>
          </div>';
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
                <input type="submit" value="Login" class="btn btn-primary rounded-pill ms-0 m-2 ms-0 w-100 p-3 fs-5">
            </div>
            <div>
                <input type="button" value="Sign up" class="btn rounded-pill border-primary-color border-2 ms-0 m-2 ms-0 w-100 p-3 fs-5">
            </div>
        </form>
    </main>
</body>

<?php include ("includes/footer.html"); ?>