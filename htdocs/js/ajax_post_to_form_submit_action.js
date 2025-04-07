// Variable declaration for "Add an expense" form field...
const addAnExpenseFormField = document.getElementById("add-expense-container");

// Variable declarations for bar chart bars for each spending type...
const fixedSpendingGraphBar = document.getElementById(
  "fixed-spending-progress-bar"
);
const variableSpendingGraphBar = document.getElementById(
  "variable-spending-progress-bar"
);
const debtSpendingGraphBar = document.getElementById(
  "debt-spending-progress-bar"
);
const savingsSpendingGraphBar = document.getElementById(
  "savings-spending-progress-bar"
);
const funSpendingGraphBar = document.getElementById(
  "fun-spending-progress-bar"
);
const otherSpendingGraphBar = document.getElementById(
  "other-spending-progress-bar"
);

// Variable declarations for total amount spent for each spending type...
const fixedSpendingTotal = document.getElementById("fixed-spending-total");
const variableSpendingTotal = document.getElementById(
  "variable-spending-total"
);
const debtSpendingTotal = document.getElementById("debt-spending-total");
const savingsSpendingTotal = document.getElementById("savings-spending-total");
const funSpendingTotal = document.getElementById("fun-spending-total");
const otherSpendingTotal = document.getElementById("other-spending-total");

// Variable declarations for expense item form fields...
const expenseName = document.getElementById("expense");
const expenseAmount = document.getElementById("expense-amount");

// Variable declarations for income amount text field and budget analytics container...
const incomeAmountField = document.getElementById("budget-amount-field");
const budgetAnalyticsContainer = document.getElementById("budget-analytics");

// Variable declaration for expense list container...
const expenseListContainer = document.getElementById("expense-list-container");

// On page load, fire AJAX to retrieve data...
$(window).load(ajaxRequestToPhpForProcessing);

// Display analytics if user enters an income that isn't "0" or empty...
displayBudgetAnalytics();

// On form submit, fire AJAX to send/retrieve data...
$("#income-expense-form").on("submit", ajaxRequestToPhpForProcessing);

// On "income amount" field value change, update the displayed budget info and format the text field input...
$("#budget-amount-field").on("input", handleBudgetAmountInput);

// On "expense amount" field value change, format the text field input...
$("#expense-amount").on("input", restrictFieldInput);

function handleBudgetAmountInput(event) {
  ajaxRequestToPhpForProcessing(event);
  restrictFieldInput(event);
}

// Function to make AJAX post to PHP for processing the user's input.
// Returns "$all_budget_data" array as "result" from "form_submit_action.php" file...
function ajaxRequestToPhpForProcessing(e) {
  e.preventDefault();
  $.ajax({
    type: "post",
    url: "../budget_queries/form_submit_action.php",
    data: $("#income-expense-form").serialize(),
    dataType: "json",
    success: function (result) {
      // Update bar graph...
      updateBarGraph(result);
      // Print the expense items list to the page...
      printExpensesToPage(result["all_expenses"], result["income_amount"]);
      // Clear expense item fields after submission...
      clearExpenseFields();
      // Display the budget analytics if the user has entered an income amount...
      displayBudgetAnalytics();
    },
  });
}

// Function to remove an item from the expense list when the user clicks the 'X' button...
// Returns "$all_budget_data" array as "result" from "form_submit_action.php" file...
expenseListContainer.addEventListener("click", (e) => {
  e.preventDefault();
  if (e.target.classList.contains("remove-btn")) {
    let expenseItemFormToDelete = `#${e.target.parentNode.id}`;
    // This invisible field is populated on the form item (expense item) that needs to be deleted.
    // PHP checks if this field has been filled, and if it has, deletes the item.
    let invisibleFieldForTogglingValue = document.getElementById(
      `delete-${e.target.parentNode.id}`
    );
    // Add "delete" value to signal to PHP that this expense needs to be deleted from database...
    invisibleFieldForTogglingValue.value = "delete";
    $.ajax({
      type: "post",
      url: "../budget_queries/form_submit_action.php",
      data: $(expenseItemFormToDelete).serialize(),
      dataType: "json",
      success: function (result) {
        // Update bar graph...
        updateBarGraph(result);
        // Print the expense items list to the page...
        printExpensesToPage(result["all_expenses"], result["income_amount"]);
      },
    });
  }
});

// Function to save the "Paid" amount within each expense item field...
expenseListContainer.addEventListener("input", (e) => {
  e.preventDefault();
  if (e.target.classList.contains("amount-paid-field")) {
    let expenseItemFormToAddPaymentTo = `#expense-id-${e.target.getAttribute(
      "data-expense-id"
    )}`;
    $.ajax({
      type: "post",
      url: "../budget_queries/form_submit_action.php",
      data: $(expenseItemFormToAddPaymentTo).serialize(),
      dataType: "json",
      success: function (result) {},
    });
  }
});

// Function to update bar graph and totals for each spending category...
function updateBarGraph(result) {
  const totalBudgetAmountLeft = document.getElementById(
    "total-budget-amount-left"
  );
  totalBudgetAmountLeft.innerHTML = `<p class="mb-0"><strong class="me-1 fs-3">$${result["total_amount_left_to_budget_with"]}</strong> left to budget with.</p>`;

  // If amount left for budget is negative, make number red:
  if (result["total_amount_left_to_budget_with"] < 0) {
    totalBudgetAmountLeft.classList.add("negative-balance-to-budget-with");
  } else {
    totalBudgetAmountLeft.classList.remove("negative-balance-to-budget-with");
  }

  // Adjust bars in bar chart for each spending type (adjust widths and attributes)...
  fixedSpendingGraphBar.style.width = `${result["fixed_percentage"]}%`;
  fixedSpendingGraphBar.setAttribute(
    "aria-valuenow",
    `${result["fixed_percentage"]}%`
  );

  variableSpendingGraphBar.style.width = `${result["variable_percentage"]}%`;
  variableSpendingGraphBar.setAttribute(
    "aria-valuenow",
    `${result["variable_percentage"]}%`
  );

  debtSpendingGraphBar.style.width = `${result["debt_percentage"]}%`;
  debtSpendingGraphBar.setAttribute(
    "aria-valuenow",
    `${result["debt_percentage"]}%`
  );

  savingsSpendingGraphBar.style.width = `${result["savings_percentage"]}%`;
  savingsSpendingGraphBar.setAttribute(
    "aria-valuenow",
    `${result["savings_percentage"]}%`
  );

  funSpendingGraphBar.style.width = `${result["fun_percentage"]}%`;
  funSpendingGraphBar.setAttribute(
    "aria-valuenow",
    `${result["fun_percentage"]}%`
  );

  otherSpendingGraphBar.style.width = `${result["other_percentage"]}%`;
  otherSpendingGraphBar.setAttribute(
    "aria-valuenow",
    `${result["other_percentage"]}%`
  );

  // Post total amounts for each spending type...
  fixedSpendingTotal.innerHTML = `<h3 class="fs-5 fw-bold">Fixed:</h3> $${result["total_fixed_spending"]}<br>${result["fixed_percentage"]}%`;
  variableSpendingTotal.innerHTML = `<h3 class="fs-5 fw-bold">Variable:</h3> $${result["total_variable_spending"]}<br>${result["variable_percentage"]}%`;
  debtSpendingTotal.innerHTML = `<h3 class="fs-5 fw-bold">Debt:</h3> $${result["total_debt_spending"]}<br>${result["debt_percentage"]}%`;
  savingsSpendingTotal.innerHTML = `<h3 class="fs-5 fw-bold">Savings:</h3> $${result["total_savings_spending"]}<br>${result["savings_percentage"]}%`;
  funSpendingTotal.innerHTML = `<h3 class="fs-5 fw-bold">Fun:</h3> $${result["total_fun_spending"]}<br>${result["fun_percentage"]}%`;
  otherSpendingTotal.innerHTML = `<h3 class="fs-5 fw-bold">Other:</h3> $${result["total_other_spending"]}<br>${result["other_percentage"]}%`;
}

// Function to sort expense items by their expense types (i.e. Fixed, Variable, Debt, etc.)
function sortExpenseItems(result) {
  const expenseGroups = {
    Fixed: [],
    Variable: [],
    Debt: [],
    Savings: [],
    Fun: [],
    Other: [],
  };

  // Sort into separate arrays by expense type
  result.forEach((expenseItem) => {
    const category = expenseItem[4];
    if (expenseGroups[category]) {
      expenseGroups[category].push(expenseItem);
    }
  });

  // Sort each expense array alphabetically based on expense name
  for (let expenseArray in expenseGroups) {
    expenseGroups[expenseArray].sort((a, b) => {
      let expenseA = a[2].toLowerCase();
      let expenseB = b[2].toLowerCase();

      return expenseA < expenseB ? -1 : expenseA > expenseB ? 1 : 0;
    });
  }

  // Concatenate expense arrays into single array
  return [
    ...expenseGroups.Fixed,
    ...expenseGroups.Variable,
    ...expenseGroups.Debt,
    ...expenseGroups.Savings,
    ...expenseGroups.Fun,
    ...expenseGroups.Other,
  ];
}

// Function to print each expense item to the page – creating a list of items...
function printExpensesToPage(result, incomeAmount) {
  // Sort results by expense type
  let sortedResults = sortExpenseItems(result);

  expenseListContainer.innerHTML = "";
  sortedResults.forEach((expenseItem) => {
    let containerForExpense = document.createElement("div");
    containerForExpense.innerHTML = `
      <form id="expense-id-${expenseItem[0]}" data-user-id="${
      expenseItem[1]
    }" class="container d-flex mb-3 p-3 pe-5 flex-column flex-md-row position-relative align-items-center bg-light rounded expense-entry-block">
        <input type="hidden" name="expense_item" value="${expenseItem[0]}">
        <input id="delete-expense-id-${
          expenseItem[0]
        }" type="hidden" name="expense_item_delete" value="">
        <p class="d-block m-0 w-100 align-self-start">Name: <span class="fw-bold">${
          expenseItem[2]
        }</span></p>
        <p class="d-block m-0 w-100 align-self-start">Amount: <span class="fw-bold pe-2">$${
          expenseItem[3]
        }</span><span class="text-secondary d-md-block d-lg-inline">(${findExpensePercentageRelativeToTotalBudgetIncomeAmount(
      incomeAmount,
      expenseItem[3]
    )}%)</span></p>
        <p class="d-block m-0 ps-0 ps-md-5 w-100 align-self-start"><span class="fw-bold ${
          expenseItem[4]
        }">${expenseItem[4]}</span></p>
        <div class="amount-paid-field-container d-flex align-self-start me-2">
          <label class="me-2" for="data-expense-id="${
            expenseItem[0]
          }">Paid: </label>
          <input data-expense-id="${
            expenseItem[0]
          }" class="amount-paid-field form-control w-100 pe-0 rounded-pill" type="number" name="amount_paid" min="0" placeholder="$0" aria-label="Amount paid" value="${
      expenseItem[5]
    }">
        </div>
        <button type="submit" aria-label="Delete item" title="Delete this item" id="${
          expenseItem[0]
        }" class="remove-btn btn fw-bolder position-absolute px-3 py-0 mt-3 top-0 end-0">✕</button>
      </form>`;
    expenseListContainer.append(containerForExpense);
  });

  // On "amount paid" field value change, format the text field input...
  $(".amount-paid-field").on("input", restrictFieldInput);
}

// Function to clear expense fields after each submission...
function clearExpenseFields() {
  if (expenseName.value !== "" && expenseAmount.value !== "") {
    expenseName.value = "";
    expenseAmount.value = "";
  }
}

// Function to calculate percentages for each expense item in the list...
function findExpensePercentageRelativeToTotalBudgetIncomeAmount(
  totalIncomeAmount,
  expenseAmount
) {
  let expensePercentage = (expenseAmount / totalIncomeAmount) * 100;
  let roundedExpensePercentage = Math.round(expensePercentage * 100) / 100;
  return roundedExpensePercentage;
}

// Function to limit what the user can enter into input fields...
function restrictFieldInput(event) {
  let value = event.target.value;

  // Remove non-numeric characters...
  value = value.replace(/\D/g, "");

  // Limit the length of the value to 11 characters
  if (value.length > 11) {
    value = value.slice(0, 11);
  }

  // Set the new value to the input field
  event.target.value = parseInt(value);
}

// Function to display "Add an expense" form and budget analytics if the user's income field is not set to "0" or empty...
function displayBudgetAnalytics() {
  const incomeAmountValue = parseInt(incomeAmountField.value); // Convert string to integer
  const isVisible = incomeAmountValue !== 0 && incomeAmountValue !== "";

  // Toggle "Add an expense" form...
  addAnExpenseFormField.classList.toggle("visible", isVisible);
  addAnExpenseFormField.classList.toggle("invisible", !isVisible);

  // Toggle analytics visibility...
  budgetAnalyticsContainer.classList.toggle("visible", isVisible);
  budgetAnalyticsContainer.classList.toggle("invisible", !isVisible);
}
