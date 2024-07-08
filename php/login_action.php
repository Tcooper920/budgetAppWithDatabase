<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require ('../../../connect_budget_database.php');

    require ('login_tools.php');

    list($check, $data) = validate($budget_database, $_POST['email'], $_POST['pass']);

    if ($check) {
        session_start();

        $_SESSION['user_id'] = $data['user_id'];
        $_SESSION['first_name'] = $data['first_name'];
        $_SESSION['last_name'] = $data['last_name'];

        load('index.php');
    } else {
        $errors = $data;
    }
    mysqli_close($budget_database);
}

include ('login.php');