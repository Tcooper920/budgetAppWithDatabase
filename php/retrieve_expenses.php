<?php

$fixed = 0;
$variable = 0;
$debt = 0;
$savings = 0;
$fun = 0;
$other = 0;

# Retrieve all expenses for current user and add their values to the bar graph below...
$query_to_retrieve_expenses = "SELECT * FROM user_expenses WHERE user_id='{$_SESSION['user_id']}'";
$retrieve_expenses = mysqli_query($budget_database, $query_to_retrieve_expenses);
$resultset = array();
if ($retrieve_expenses) {
    while ($row = mysqli_fetch_array($retrieve_expenses, MYSQLI_NUM)) {
        $resultset[] = $row;
    }
}

foreach ($resultset as $result) {
    if ($result[4] == "Fixed") {
        $fixed += $result[3];
    }
    
    if ($result[4] == "Variable") {
        $variable += $result[3];
    }
    
    if ($result[4] == "Debt") {
        $debt += $result[3];
    }
    
    if ($result[4] == "Savings") {
        $savings += $result[3];
    }
    
    if ($result[4] == "Fun") {
        $fun += $result[3];
    }
    
    if ($result[4] == "Other") {
        $other += $result[3];
    }
}

// Calculate percentages (expense type/income amount * 100) and round to 2 decimal places...
if ($current_income != 0) {
    $fixed_percentage = round((($fixed/$current_income)*100), 2);
    $variable_percentage = round((($variable/$current_income)*100), 2);
    $debt_percentage = round((($debt/$current_income)*100), 2);
    $savings_percentage = round((($savings/$current_income)*100), 2);
    $fun_percentage = round((($fun/$current_income)*100), 2);
    $other_percentage = round((($other/$current_income)*100), 2);
}

// Calculate total amount left to budget with...
$total_amount_spent = $fixed + $variable + $debt + $savings + $fun + $other;
$total_amount_left_to_budget_with = round(($current_income - $total_amount_spent), 2);