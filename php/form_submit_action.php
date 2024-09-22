<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    require('login_tools.php');
    load();
}
require('../../../connect_budget_database.php');
require('retrieve_income.php');
require('retrieve_expenses.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (
        !empty($_POST['total_income'])
    ) {
        # Post total income to budget with...
        $userid = $_SESSION["user_id"];
        $total_income = $_POST["total_income"];

        $query_to_post_budget_amount = $budget_database->prepare("INSERT INTO user_income (user_id, total_income) VALUES (?, ?)");
        $query_to_post_budget_amount->bind_param("ii", $userid, $total_income);
        $query_to_post_budget_amount->execute();

        if (
            !empty($_POST['expense_name'])
            && !empty($_POST['expense_amount'])
            && !empty($_POST['expense_type'])
        ) {
            # Post expense item (name, amount, type of expense)...
            $expense_name = $_POST["expense_name"];
            $expense_amount = $_POST["expense_amount"];
            $expense_type = $_POST["expense_type"];

            $query_to_post_expense = $budget_database->prepare("INSERT INTO user_expenses (user_id, expense_name, expense_amount, expense_type) VALUES (?, ?, ?, ?)");
            $query_to_post_expense->bind_param("isis", $userid, $expense_name, $expense_amount, $expense_type);
            $query_to_post_expense->execute();
        }
    }
    if (
        !empty($_POST['expense_item'])
    ) {
        # Delete expense...
        $expense_item = $_POST["expense_item"];

        $query_to_delete_expense = $budget_database->prepare("DELETE FROM user_expenses WHERE expense_id = ?");
        $query_to_delete_expense->bind_param("i", $expense_item);
        $query_to_delete_expense->execute();
    }
    require('retrieve_income.php');
    require('retrieve_expenses.php');

    $all_budget_data = array(
        'total_amount_left_to_budget_with' => $total_amount_left_to_budget_with,
        'total_fixed_spending' => $fixed,
        'total_variable_spending' => $variable,
        'total_debt_spending' => $debt,
        'total_savings_spending' => $savings,
        'total_fun_spending' => $fun,
        'total_other_spending' => $other,
        'fixed_percentage' => $fixed_percentage,
        'variable_percentage' => $variable_percentage,
        'debt_percentage' => $debt_percentage,
        'savings_percentage' => $savings_percentage,
        'fun_percentage' => $fun_percentage,
        'other_percentage' => $other_percentage,
        'all_expenses' => $resultset
    );

    echo json_encode($all_budget_data);
}