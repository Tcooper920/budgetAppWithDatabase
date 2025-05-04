<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require("../../includes/header.php");

?>

<body class="white min-vh-100 d-flex flex-column gradient-background">
    <?php require("../../includes/navbar.php"); ?>
    <main class="p-3 pt-sm-5 mt-lg-5 container align-items-center flex-fill">
        <div class="row mb-2">
            <div class="col-lg-6 fade-in">
                <h1 class="pb-3 fw-bold">How to use BudgetBuilder</h1>
                <hr class="mb-4">
                <h2 class="pb-3 fw-bold align-items-center d-sm-flex"><span
                        class="circle-numbers flex-shrink-0">1</span>Enter the total dollar amount you would like to
                    budget with</h2>
                <img src="../../images/screenshots/how-to-use-screenshot-1.jpg"
                    class="w-100 h-auto mb-4 rounded-4 shadow-lg" alt="" width="1000" height="239">
                <hr class="mb-4">
                <h2 class="pb-3 fw-bold align-items-center d-sm-flex"><span
                        class="circle-numbers flex-shrink-0">2</span>Add your expenses</h2>
                <p class="fs-5 mb-4">Add one expense at a time. Enter the expense name, the amount of money you
                    would like to allocate for the expense, and the expense type.</p>
                <p class="fs-5 mb-4">Then click the "+ Add expense" button.</p>
                <img src="../../images/screenshots/how-to-use-screenshot-2.jpg"
                    class="w-100 h-auto mb-4 rounded-4 shadow-lg" alt="" width="1000" height="171">
                <hr class="mb-4">
                <h2 class="pb-3 fw-bold align-items-center d-sm-flex"><span
                        class="circle-numbers flex-shrink-0">3</span>View your budget breakdown</h2>
                <p class="fs-5 mb-4">After entering all your expenes, you can view your budget breakdown in the graph.
                    The graph diplays the total amount of money you have allocated for each expense type.</p>
                <p class="fs-5 mb-4">Under the graph you will find a table that displays the total amount of money
                    allocated to each expense type
                    along with each expense type's percentage of your total spending.</p>
                <img src="../../images/screenshots/how-to-use-screenshot-3.jpg"
                    class="w-100 h-auto mb-4 rounded-4 shadow-lg" alt="" width="1000" height="418">
                <hr class="mb-4">
                <h2 class="pb-3 fw-bold align-items-center d-sm-flex"><span
                        class="circle-numbers flex-shrink-0">4</span>View/Edit your expense list and track spending
                </h2>
                <p class="fs-5 mb-4">Each individual expense item is sorted by its expense type and displayed at the
                    bottom of the page. The expense name, amount of money allocated for the expense, and expense type
                    are displayed for each item.</p>
                <h3 class="pb-1">Track your spending:</h3>
                <p class="fs-5 mb-4">The "Spent" field (next to each expense item) allows you to enter how much you've
                    spent towards the expense item. It is recommended that you avoid spending more than the amount your
                    budget allocates for. Typically, a user will add up their receipts before entering an amount into
                    the fields.</p>
                <h3 class="pb-1">Delete an expense:</h3>
                <p class="fs-5 mb-4">To delete an expense item from your budget, click the "X" button next to the
                    expense item you want to delete.</p>
                <h3 class="pb-1">Clear amount spent:</h3>
                <p class="fs-5 mb-4">If you want to clear all of the "Spent" fields, click the button that reads "Clear
                    amount spent". This is typically done at the end of your budget cycle. Then you can begin tracking
                    your expenses again.</p>
                <img src="../../images/screenshots/how-to-use-screenshot-4.jpg"
                    class="w-100 h-auto mb-4 rounded-4 shadow-lg" alt="" width="1000" height="465">
                <hr class="mb-4">
                <h2 class="pb-3 fw-bold align-items-center d-sm-flex"><span
                        class="circle-numbers flex-shrink-0">5</span>Review your total spending</h2>
                <p class="fs-5 mb-4">The total amount spent is displayed at the bottom of the page. This adds up all the
                    "Spent" amounts entered for each expense item. Underneath, you will find your total amount to budget
                    with.</p>
                <p class="fs-5 mb-4">It is recommended that you avoid spending more than your total budget amount.
                    Below, you can see that the user spent $1,900 out of their total budget amount of $4,500.</p>
                <img src="../../images/screenshots/how-to-use-screenshot-5.jpg"
                    class="w-100 h-auto mb-4 rounded-4 shadow-lg" alt="" width="1000" height="190">
                <div class="row pt-2">
                    <div class="col-sm-7">
                        <a href="/budget_app_with_database/htdocs/index.php"
                            class="btn btn-primary ms-0 m-2 ms-0 w-100 p-2 fs-5 rounded-pill"
                            title="Return to homepage">Return to homepage</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-flex">
                <img src="/budget_app_with_database/htdocs/images/calculator-help.svg" alt="" width="450" height="465"
                    class="col w-100 mt-5 mt-lg-0 ps-lg-4 d-none d-xl-block">
            </div>
        </div>
    </main>
    <script src="../../js/preserve_scroll_position_onload.js"></script>
</body>

<?php include("../../includes/footer.php"); ?>