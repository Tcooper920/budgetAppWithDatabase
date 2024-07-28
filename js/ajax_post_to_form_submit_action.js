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

// Variable declaration for expense list container...
const expenseListContainer = document.getElementById("expense-list-container");

// On page load, fire AJAX to retrieve data...
$(window).load(ajaxRequestToPhpForProcessing);

// On form submit, fire AJAX to send/retrieve data...
$("#income-expense-form").on("submit", ajaxRequestToPhpForProcessing);

// On "income amount" field value change, update the displayed budget info...
$("#budget-amount-field").on("input", ajaxRequestToPhpForProcessing);

// Function to make AJAX post to PHP for processing.
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
      printExpensesToPage(result[14]);
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
        printExpensesToPage(result[14]);
      },
    });
  }
});

// Function to update bar graph...
function updateBarGraph(result) {
  document.getElementById("total-budget-amount-left").innerHTML = `<p class="mb-0"><strong>$${result[1]}</strong> left to budget with.</p>`;

  // Adjust bars in bar chart for each spending type (adjust widths and attributes)...
  fixedSpendingGraphBar.style.width = `${result[8]}%`;
  fixedSpendingGraphBar.setAttribute("aria-valuenow", `${result[8]}%`);

  variableSpendingGraphBar.style.width = `${result[9]}%`;
  variableSpendingGraphBar.setAttribute("aria-valuenow", `${result[9]}%`);

  debtSpendingGraphBar.style.width = `${result[10]}%`;
  debtSpendingGraphBar.setAttribute("aria-valuenow", `${result[10]}%`);

  savingsSpendingGraphBar.style.width = `${result[11]}%`;
  savingsSpendingGraphBar.setAttribute("aria-valuenow", `${result[11]}%`);

  funSpendingGraphBar.style.width = `${result[12]}%`;
  funSpendingGraphBar.setAttribute("aria-valuenow", `${result[12]}%`);

  otherSpendingGraphBar.style.width = `${result[13]}%`;
  otherSpendingGraphBar.setAttribute("aria-valuenow", `${result[13]}%`);

  // Post total amounts for each spending type...
  fixedSpendingTotal.innerHTML = `Fixed spending total: ${result[2]}`;
  variableSpendingTotal.innerHTML = `Variable spending total: ${result[3]}`;
  debtSpendingTotal.innerHTML = `Debt spending total: ${result[4]}`;
  savingsSpendingTotal.innerHTML = `Savings spending total: ${result[5]}`;
  funSpendingTotal.innerHTML = `Fun spending total: ${result[6]}`;
  otherSpendingTotal.innerHTML = `Other spending total: ${result[7]}`;
}

// Function to print all expenses to the the page as a list of items...
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





