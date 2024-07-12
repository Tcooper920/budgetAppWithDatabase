<?php

require ('../../../connect_budget_database.php');
# Get last saved income amount...
$query_to_get_income = "SELECT total_income FROM user_income WHERE income_id=(SELECT max(income_id) FROM user_income WHERE user_id='{$_SESSION['user_id']}')";

$get_income = mysqli_query($budget_database, $query_to_get_income);
$last_saved_income = mysqli_fetch_array($get_income);

# If a previous income wasn't saved, make it "0", else display the saved income amount...
$current_income = is_null($last_saved_income) ? 0.00 : "$last_saved_income[0]";