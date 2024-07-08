<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    require ('login_tools.php');
    load();
}
echo "<p>Welcome {$_SESSION['first_name']} {$_SESSION['last_name']}</p>
<p>User ID: {$_SESSION['user_id']}</p>";

require ('../../../connect_budget_database.php');
require ('process_income_function.php');
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

        require ('process_income_function.php');
        require ('retrieve_expenses.php');

    } else {
        echo '<p>Oops! Fill in the fields</p>';
    }
}

echo "<p>fixed total = $fixed<br>
        variable total = $variable<br>
        debt total = $debt<br>
        savings total = $savings<br>
        fun total = $fun<br>
        other total = $other</p>";

?>

<DOCTYPE! html>
    <html>

    <head>
        <title>Start Building a Budget</title>
        <meta name="description" content="A free application for building a budget and tracking your expenses.">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="../css/index.css">
    </head>

    <body class="dark-blue-background white">

    </body>
    <main class="p-3">
        <div class="container">
            <h1 class="mt-4">Build a Budget:</h1>
        </div>

        <form action="index.php" method="POST">
            <div id="enter-income-form" class="container fs-5 d-flex flex-column p-3 my-3 bg-light rounded dark-blue">
                <label>
                    Enter your income amount:
                    <input id="budget-amount-field" class="form-control m-2 ms-0" type="text" name="total_income"
                        placeholder="0.00" aria-label="Total Budget Amount" value="<?php echo "$current_income" ?>">
                </label>
            </div>
            <div class="container fs-5 d-flex flex-column p-3 my-3 bg-light rounded dark-blue no-print">
                <label for="expense" class="d-flex">Add an expense:</label>
                <div class="d-flex flex-column flex-md-row">
                    <input id="expense" class="form-control m-2 ms-0 expense-name" type="text" name="expense_name"
                        placeholder="Expense Name" aria-label="Expense Name">
                    <input id="expense-amount" class="form-control m-2 ms-0 expense-amount" type="text"
                        name="expense_amount" placeholder="0.00" aria-label="Expense Amount">
                    <select class="form-select m-2 mx-0 expense-type" aria-label="Type of Expense" name="expense_type">
                        <option value="Fixed" selected>Fixed</option>
                        <option value="Variable">Variable</option>
                        <option value="Debt">Debt</option>
                        <option value="Savings">Savings</option>
                        <option value="Fun">Fun</option>
                        <option value="Other">Other</option>
                    </select>
                    <button type="submit" class="add-entry btn btn-primary ms-0 ms-md-2 m-2 me-0 w-100">Add
                        Expense</button>
                </div>
            </div>
        </form>
        <div class="container p-4 mt-3 mb-4 rounded navy-blue-background">
            <div id="graph" class="progress container p-0">
                <div id="fixed-spending-progress-bar" class="progress-bar light-blue-background" role="progressbar"
                    style="<?php echo "width:$fixed_percentage%" ?>"
                    aria-valuenow="<?php echo "$fixed_percentage%" ?>" aria-valuemin="0" aria-valuemax="100">
                </div>
                <div id="variable-spending-progress-bar" class="progress-bar purple-background" role="progressbar"
                    style="<?php echo "width:$variable_percentage%" ?>"
                    aria-valuenow="<?php echo "$variable_percentage%" ?>" aria-valuemin="0" aria-valuemax="100">
                </div>
                <div id="debt-spending-progress-bar" class="progress-bar progress-bar-striped bg-danger"
                    role="progressbar" style="<?php echo "width:$debt_percentage%" ?>"
                    aria-valuenow="<?php echo "$debt_percentage%" ?>" aria-valuemin="0" aria-valuemax="100"></div>
                <div id="savings-spending-progress-bar" class="progress-bar light-green-background" role="progressbar"
                    style="<?php echo "width:$savings_percentage%" ?>"
                    aria-valuenow="<?php echo "$savings_percentage%" ?>" aria-valuemin="0" aria-valuemax="100">
                </div>
                <div id="fun-spending-progress-bar" class="progress-bar light-yellow-background" role="progressbar"
                    style="<?php echo "width:$fun_percentage%" ?>"
                    aria-valuenow="<?php echo "$fun_percentage%" ?>" aria-valuemin="0" aria-valuemax="100"></div>
                <div id="other-spending-progress-bar" class="progress-bar light-gray-background" role="progressbar"
                    style="<?php echo "width:$other_percentage%" ?>"
                    aria-valuenow="<?php echo "$other_percentage%" ?>" aria-valuemin="0" aria-valuemax="100">
                </div>
            </div>
            <div class="container fs-5 text mt-2 no-print" id="total-budget-amount"><?php echo "$$total_amount_left_to_budget_with" ?> Remaining to budget with.</div>
        </div>
    </main>

    </html>

    <?php

    mysqli_close($budget_database);

    ?>