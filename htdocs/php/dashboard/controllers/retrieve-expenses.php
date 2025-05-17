<?php

$fixed = 0;
$variable = 0;
$debt = 0;
$savings = 0;
$fun = 0;
$other = 0;
$total_amount_paid_off = 0;

$fixed_percentage = 0;
$variable_percentage = 0;
$debt_percentage = 0;
$savings_percentage = 0;
$fun_percentage = 0;
$other_percentage = 0;

# Retrieve all expenses for current user and add their values to the bar graph below...
$userid = $_SESSION['user_id'];

$query_to_retrieve_expenses = $budget_database->prepare("SELECT * FROM user_expenses WHERE user_id=?");
$query_to_retrieve_expenses->bind_param("i", $userid);
$query_to_retrieve_expenses->execute();
$retrieve_expenses = $query_to_retrieve_expenses->get_result();

$resultset = array();
if ($retrieve_expenses) {
    while ($row = mysqli_fetch_array($retrieve_expenses, MYSQLI_NUM)) {
        $resultset[] = $row;
    }
}

foreach ($resultset as $result) {
    $amount = $result[3];
    $category = $result[4];
    $paid_off = $result[5];

    if ($category == "Fixed") {
        $fixed += $amount;
    }

    if ($category == "Variable") {
        $variable += $amount;
    }

    if ($category == "Debt") {
        $debt += $amount;
    }

    if ($category == "Savings") {
        $savings += $amount;
    }

    if ($category == "Fun") {
        $fun += $amount;
    }

    if ($category == "Other") {
        $other += $amount;
    }

    $total_amount_paid_off += $paid_off;
}

// Calculate percentages (expense type/income amount * 100) and round to 2 decimal places...
if (!empty($current_income) && $current_income != 0) {
    $fixed_percentage = calculatePercentageAndRound($fixed, $current_income);
    $variable_percentage = calculatePercentageAndRound($variable, $current_income);
    $debt_percentage = calculatePercentageAndRound($debt, $current_income);
    $savings_percentage = calculatePercentageAndRound($savings, $current_income);
    $fun_percentage = calculatePercentageAndRound($fun, $current_income);
    $other_percentage = calculatePercentageAndRound($other, $current_income);
}

// Calculate total amount left to budget with...
$total_amount_spent = $fixed + $variable + $debt + $savings + $fun + $other;
$total_amount_left_to_budget_with = round(($current_income - $total_amount_spent), 2);