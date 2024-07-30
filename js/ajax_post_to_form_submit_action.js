// Variable declarations for bar chart bars for each spending type...
const fixedSpendingGraphBar = document.getElementById("fixed-spending-progress-bar");
const variableSpendingGraphBar = document.getElementById("variable-spending-progress-bar");
const debtSpendingGraphBar = document.getElementById("debt-spending-progress-bar");
const savingsSpendingGraphBar = document.getElementById("savings-spending-progress-bar");
const funSpendingGraphBar = document.getElementById("fun-spending-progress-bar");
const otherSpendingGraphBar = document.getElementById("other-spending-progress-bar");

// Variable declarations for total amount spent for each spending type...
const fixedSpendingTotal = document.getElementById("fixed-spending-total");
const variableSpendingTotal = document.getElementById("variable-spending-total");
const debtSpendingTotal = document.getElementById("debt-spending-total");
const savingsSpendingTotal = document.getElementById("savings-spending-total");
const funSpendingTotal = document.getElementById("fun-spending-total");
const otherSpendingTotal = document.getElementById("other-spending-total");

// Variable declarations for expense item form fields...
const expenseName = document.getElementById("expense");
const expenseAmount = document.getElementById("expense-amount");

// Variable declaration for expense list container...
const expenseListContainer = document.getElementById("expense-list-container");

// On page load, fire AJAX to retrieve data...
$(window).load(ajaxRequestToPhpForProcessing);

// On form submit, fire AJAX to send/retrieve data...
$("#income-expense-form").on("submit", ajaxRequestToPhpForProcessing);

// On "income amount" field value change, update the displayed budget info...
$("#budget-amount-field").on("input", ajaxRequestToPhpForProcessing);

// Function to make AJAX post to PHP for processing the user's input.
// Returns "$all_budget_data" array as "result" from "form_submit_action.php" file...
function ajaxRequestToPhpForProcessing(e) {
  e.preventDefault();
  $.ajax({
    type: "post",
    url: "../php/form_submit_action.php",
    data: $("#income-expense-form").serialize(),
    dataType: "json",
    success: function (result) {
      // Update bar graph...
      updateBarGraph(result);
      // Print the expense items list to the page...
      printExpensesToPage(result['all_expenses']);
      // Clear expense item fields after submission...
      clearExpenseFields();
    },
  });
}

// Function to remove an item from the expense list when the user clicks the 'X' button...
// Returns "$all_budget_data" array as "result" from "form_submit_action.php" file...
expenseListContainer.addEventListener("click", (e) => {
  e.preventDefault();
  if (e.target.classList.contains("remove-btn")) {
    let expenseItemFormToDelete = `#${e.target.parentNode.id}`;
    $.ajax({
      type: "post",
      url: "../php/form_submit_action.php",
      data: $(expenseItemFormToDelete).serialize(),
      dataType: "json",
      success: function (result) {
        // Update bar graph...
        updateBarGraph(result);
        // Print the expense items list to the page...
        printExpensesToPage(result['all_expenses']);
      },
    });
  }
});

// Function to update bar graph and totals for each spending category...
function updateBarGraph(result) {
  document.getElementById(
    "total-budget-amount-left"
  ).innerHTML = `<p class="mb-0"><strong>$${result["total_amount_left_to_budget_with"]}</strong> left to budget with.</p>`;

  // Adjust bars in bar chart for each spending type (adjust widths and attributes)...
  fixedSpendingGraphBar.style.width = `${result['fixed_percentage']}%`;
  fixedSpendingGraphBar.setAttribute("aria-valuenow", `${result['fixed_percentage']}%`);

  variableSpendingGraphBar.style.width = `${result['variable_percentage']}%`;
  variableSpendingGraphBar.setAttribute("aria-valuenow", `${result['variable_percentage']}%`);

  debtSpendingGraphBar.style.width = `${result['debt_percentage']}%`;
  debtSpendingGraphBar.setAttribute("aria-valuenow", `${result['debt_percentage']}%`);

  savingsSpendingGraphBar.style.width = `${result['savings_percentage']}%`;
  savingsSpendingGraphBar.setAttribute("aria-valuenow", `${result['savings_percentage']}%`);

  funSpendingGraphBar.style.width = `${result['fun_percentage']}%`;
  funSpendingGraphBar.setAttribute("aria-valuenow", `${result['fun_percentage']}%`);

  otherSpendingGraphBar.style.width = `${result['other_percentage']}%`;
  otherSpendingGraphBar.setAttribute("aria-valuenow", `${result['other_percentage']}%`);

  // Post total amounts for each spending type...
  fixedSpendingTotal.innerHTML = `Fixed total: <br>$${result["total_fixed_spending"]}`;
  variableSpendingTotal.innerHTML = `Variable total: <br>$${result["total_variable_spending"]}`;
  debtSpendingTotal.innerHTML = `Debt total: <br>$${result["total_debt_spending"]}`;
  savingsSpendingTotal.innerHTML = `Savings total: <br>$${result["total_savings_spending"]}`;
  funSpendingTotal.innerHTML = `Fun total: <br>$${result["total_fun_spending"]}`;
  otherSpendingTotal.innerHTML = `Other spending: <br>$${result["total_other_spending"]}`;
}

// Function to print each expense item to the the page – creating a list of items...
function printExpensesToPage(result) {
  expenseListContainer.innerHTML = "";
  result.forEach((expenseItem) => {
    let containerForExpense = document.createElement("div");
    containerForExpense.innerHTML = `
      <form id="expense-id-${expenseItem[0]}" data-user-id="${expenseItem[1]}" class="container d-flex flex-column flex-md-row position-relative mb-3 bg-light rounded dark-blue expense-entry-block">
        <input type="hidden" name="expense_item" value="${expenseItem[0]}">
        <p class="d-block m-2 w-100">Name: <span class="fw-bold">${expenseItem[2]}</span></p>
        <p class="d-block ms-2 my-0 my-sm-2 w-100">Amount: <span class="fw-bold">${expenseItem[3]}</span></p>
        <p class="d-block m-2 w-100">Type: <span class="fw-bold ${expenseItem[4]}">${expenseItem[4]}</span></p>
        <input type="submit" value="✕" id="${expenseItem[0]}" class="remove-btn btn fw-bolder text-secondary position-absolute end-0">
      </form>`;
    expenseListContainer.append(containerForExpense);
  });
}

// Function to clear expense fields after each submission...
function clearExpenseFields() {
  expenseName.value = "";
  expenseAmount.value = "";
} 
