<?php

require('../../../includes/csrf.php'); // Generate CSRF token

// Store it in a local variable for output
$csrf_token = $_SESSION['csrf_token'];

require_once('../../../../config/connect_budget_database.php');
require_once('functions.php');
require('retrieve_income.php');
require('retrieve_expenses.php');

// Helper function to format numerical user inputs 
function formatAndSanitizeNumericalInput($form_input_name_attribute)
{
    $user_input = filter_input(INPUT_POST, $form_input_name_attribute, FILTER_SANITIZE_NUMBER_INT);
    $user_input = filter_var($user_input, FILTER_VALIDATE_INT);

    if ($user_input === false) {
        return 0;
    }

    return $user_input;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the CSRF token is set and matches the one stored in the session...
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        // CSRF token validation failed...
        header('Location: ../../error_pages/views/form_verification_error.php');
        exit;
    }
    if (
        !empty($_POST['total_income'])
    ) {
        # Post total income to budget with...
        $userid = $_SESSION["user_id"];
        $total_income = formatAndSanitizeNumericalInput('total_income');

        if ($total_income !== false) {
            $query_to_post_budget_amount = $budget_database->prepare("INSERT INTO user_income (user_id, total_income) VALUES (?, ?)");
            $query_to_post_budget_amount->bind_param("ii", $userid, $total_income);
            $query_to_post_budget_amount->execute();
        } else {
            # If $total_income is not an integer, it might be an attempt at a SQL injection. Throw an error...
            header('Location: ../../error_pages/views/form_verification_error.php');
            exit;
        }

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
        !empty($_POST['expense_item_delete'])
    ) {
        # Delete expense...
        $expense_item = $_POST["expense_item"];

        $query_to_delete_expense = $budget_database->prepare("DELETE FROM user_expenses WHERE expense_id = ?");
        $query_to_delete_expense->bind_param("i", $expense_item);
        $query_to_delete_expense->execute();
    }
    # Save "Paid" amount fields...
    if (isset($_POST['amount_paid'])) {
        $amount_paid = formatAndSanitizeNumericalInput('amount_paid');
        $expense_item = $_POST["expense_item"];

        $query_to_post_expense = $budget_database->prepare("UPDATE user_expenses SET amount_paid = ? WHERE expense_id = ?");
        $query_to_post_expense->bind_param("ii", $amount_paid, $expense_item);
        $query_to_post_expense->execute();
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
        'all_expenses' => $resultset,
        'income_amount' => $current_income,
        'total_amount_paid_off' => $total_amount_paid_off
    );

    echo json_encode($all_budget_data);
}