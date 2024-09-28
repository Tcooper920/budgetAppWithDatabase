<?php 
    require ("form_submit_action.php"); 
    require ("includes/header.html");
?>

<body class="dark-blue-background white">

    <?php 
        $login_or_logout = "logout";
        require ("includes/navbar.html"); 
    ?>

    <main class="p-3">
        <?php echo "<div class='container px-0'>
                <p class='mb-0 mt-3 fs-4' data-user-id='{$_SESSION['user_id']}'>Welcome, {$_SESSION['first_name']} {$_SESSION['last_name']}</p>
                <hr class='mb-5'>
            </div>"; ?>
        <div class="container px-0">
            <h1 class="mt-4">Build a Budget:</h1>
        </div>

        <form id="income-expense-form">
            <div id="enter-income-form" class="container fs-5 d-flex flex-column p-4 my-3 bg-light rounded dark-blue">
                <label>
                    Enter your income amount:
                    <input id="budget-amount-field" class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill" type="number" step="1" name="total_income"
                        placeholder="$0" aria-label="Total Budget Amount" value="<?php echo "$current_income" ?>">
                </label>
            </div>
            <div class="container fs-5 d-flex flex-column p-4 my-3 bg-light rounded darkest">
                <label for="expense" class="d-flex">Add an expense:</label>
                <div class="d-flex flex-column flex-md-row">
                    <input id="expense" class="form-control m-2 ms-0 expense-name p-2 ps-4 fs-5 rounded-pill" type="text" name="expense_name"
                        placeholder="Expense Name" aria-label="Expense Name">
                    <input id="expense-amount" class="form-control m-2 ms-0 expense-amount p-2 ps-4 fs-5 rounded-pill" type="number" step="1"
                        name="expense_amount" placeholder="$0" aria-label="Expense Amount">
                    <select class="form-select m-2 mx-0 expense-type  p-2 ps-4 fs-5 rounded-pill" aria-label="Type of Expense" name="expense_type">
                        <option value="Fixed" selected>Fixed</option>
                        <option value="Variable">Variable</option>
                        <option value="Debt">Debt</option>
                        <option value="Savings">Savings</option>
                        <option value="Fun">Fun</option>
                        <option value="Other">Other</option>
                    </select>
                    <button id="submit-form" type="submit"
                        class="add-entry btn btn-primary ms-0 ms-md-2 m-2 me-0 w-100 p-2 fs-5 rounded-pill">+ Add expense</button>
                </div>
            </div>
        </form>
        <div class="container p-4 mt-3 mb-4 navy-blue-background rounded">
            <div id="graph" class="progress container p-0">
                <div id="fixed-spending-progress-bar" class="progress-bar light-blue-background" role="progressbar"
                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                </div>
                <div id="variable-spending-progress-bar" class="progress-bar purple-background" role="progressbar"
                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                </div>
                <div id="debt-spending-progress-bar" class="progress-bar progress-bar-striped bg-danger"
                    role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                <div id="savings-spending-progress-bar" class="progress-bar light-green-background" role="progressbar"
                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                </div>
                <div id="fun-spending-progress-bar" class="progress-bar light-yellow-background" role="progressbar"
                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                <div id="other-spending-progress-bar" class="progress-bar light-gray-background" role="progressbar"
                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                </div>
            </div>
            <div class="container fs-5 text mt-2 px-0" id="total-budget-amount-left"></div>
        </div>
        <div class="container px-3">
            <div class="row p-0">
                <p class="container col-6 col-sm-4 col-lg-2 px-2 border-left-fixed rounded"><span
                        id="fixed-spending-total"></span></p>
                <p class="container col-6 col-sm-4 col-lg-2 px-2 border-left-variable rounded"><span
                        id="variable-spending-total"></span></p>
                <p class="container col-6 col-sm-4 col-lg-2 px-2 border-left-debt rounded"><span
                        id="debt-spending-total"></span></p>
                <p class="container col-6 col-sm-4 col-lg-2 px-2 border-left-savings rounded"><span
                        id="savings-spending-total"></span></p>
                <p class="container col-6 col-sm-4 col-lg-2 px-2 border-left-fun rounded"><span id="fun-spending-total"></span>
                </p>
                <p class="container col-6 col-sm-4 col-lg-2 px-2 border-left-other rounded"><span
                        id="other-spending-total"></span></p>
            </div>
        </div>
        <div class="container px-0">
            <h2 class="mt-4 pb-3">Expenses:</h2>
        </div>
        <div id="expense-list-container"></div>
    </main>

    <script src="../js/ajax_post_to_form_submit_action.js"></script>

</body>

<?php 
    mysqli_close($budget_database);
    include ("includes/footer.html"); 
?>