<?php require ("includes/header.html"); ?>

<body class="dark-blue-background white">

    <?php require ("form_submit_action.php") ?>

    <main class="p-3">
        <?php echo "<div class='container'>
                <p class='mb-0 mt-3 fs-4' data-user-id='{$_SESSION['user_id']}'>Welcome, {$_SESSION['first_name']} {$_SESSION['last_name']}</p>
                <hr class='mb-5'>
            </div>"; ?>
        <div class="container">
            <h1 class="mt-4">Build a Budget:</h1>
        </div>

        <form id="income-expense-form">
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
                    <button id="submit-form" type="submit"
                        class="add-entry btn btn-primary ms-0 ms-md-2 m-2 me-0 w-100">+ Add expense</button>
                </div>
            </div>
        </form>
        <div class="container p-4 mt-3 mb-4 rounded navy-blue-background">
            <div id="graph" class="progress container p-0">
                <div id="fixed-spending-progress-bar" class="progress-bar light-blue-background" role="progressbar"
                    aria-valuenow="" aria-valuemin="0" aria-valuemax="100">
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
                    style="<?php echo "width:$fun_percentage%" ?>" aria-valuenow="<?php echo "$fun_percentage%" ?>"
                    aria-valuemin="0" aria-valuemax="100"></div>
                <div id="other-spending-progress-bar" class="progress-bar light-gray-background" role="progressbar"
                    style="<?php echo "width:$other_percentage%" ?>" aria-valuenow="<?php echo "$other_percentage%" ?>"
                    aria-valuemin="0" aria-valuemax="100">
                </div>
            </div>
            <div class="container fs-5 text mt-2 no-print" id="total-budget-amount-left"></div>
        </div>
        <div class="container px-3">
            <div class="row p-0">
                <p class="container col-6 col-sm-4 col-lg-2 px-2 border-left-fixed"><span id="fixed-spending-total"></span></p>
                <p class="container col-6 col-sm-4 col-lg-2 px-2 border-left-variable"><span id="variable-spending-total"></span></p>
                <p class="container col-6 col-sm-4 col-lg-2 px-2 border-left-debt"><span id="debt-spending-total"></span></p>
                <p class="container col-6 col-sm-4 col-lg-2 px-2 border-left-savings"><span id="savings-spending-total"></span></p>
                <p class="container col-6 col-sm-4 col-lg-2 px-2 border-left-fun"><span id="fun-spending-total"></span></p>
                <p class="container col-6 col-sm-4 col-lg-2 px-2 border-left-other"><span id="other-spending-total"></span></p>
            </div>
        </div>
        <div class="container">
            <h2 class="mt-4 pb-3">Expenses:</h2>
        </div>
        <div id="expense-list-container"></div>
    </main>

    <script src="../js/ajax_post_to_form_submit_action.js"></script>

    <?php mysqli_close($budget_database); ?>

</body>

<?php include ("includes/footer.html"); ?>
