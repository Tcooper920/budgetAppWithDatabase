<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    require ('login_tools.php');
    load();
}
require ('../../../connect_budget_database.php');
require ('retrieve_income.php');
require ('retrieve_expenses.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (
        !empty($_POST['total_income'])
    ) {
        # Post total income to budget with...
        $query_to_set_income = "INSERT INTO user_income (user_id, total_income)
                VALUES (
                    '{$_SESSION["user_id"]}',
                    '{$_POST["total_income"]}'
                )";
        $set_income = mysqli_query($budget_database, $query_to_set_income);
        if (
            !empty($_POST['expense_name'])
            && !empty($_POST['expense_amount'])
            && !empty($_POST['expense_type'])
        ) {
            # Post expense...
            $query_to_set_expense = "INSERT INTO user_expenses (user_id, expense_name, expense_amount, expense_type)
                    VALUES (
                        '{$_SESSION["user_id"]}',
                        '{$_POST["expense_name"]}',
                        '{$_POST["expense_amount"]}',
                        '{$_POST["expense_type"]}'
                    )";
            $set_expense = mysqli_query($budget_database, $query_to_set_expense);
        }
        require ('retrieve_income.php');
        require ('retrieve_expenses.php');
    } else {
        echo '<p>Oops! Fill in the fields</p>';
    }
}

?>