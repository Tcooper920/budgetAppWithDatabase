<?php

function load($page = 'login.php') {
    $url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);

    $url = rtrim($url, '/\\');
    $url .= '/' . $page;

    header("Location: $url");
    exit();
}

function validate($budget_database, $email = '' , $pwd = '') {
    $errors = array();

    if (empty($email)) {
        $errors[] = 'Enter your email address.';
    } else {
        $e = mysqli_real_escape_string($budget_database, trim($email));
    }

    if (empty($pwd)) {
        $errors[] = 'Enter your password.';
    } else {
        $p = mysqli_real_escape_string($budget_database, trim($pwd));
    }

    if (empty($errors)) {
        $email = "$e";
        $pass = "$p";
        
        $query_to_get_user = $budget_database->prepare("SELECT user_id, first_name, last_name FROM users WHERE email = ? AND pass = SHA2(?, 256)");
        $query_to_get_user->bind_param("ss", $email, $pass);
        $query_to_get_user->execute();
        $get_user = $query_to_get_user->get_result();

        if (mysqli_num_rows($get_user) == 1) {
            $row = mysqli_fetch_array($get_user, MYSQLI_ASSOC);
            return array(true, $row);
        } else {
            $errors[] = 'Email address and password not found.';
        }
    }
    return array(false, $errors);
}