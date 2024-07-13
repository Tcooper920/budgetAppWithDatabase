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

// On page load, fire AJAX to retrieve data...
$(window).load(ajaxRequestToPhpForProcessing);

// On form submit, fire AJAX to send/retrieve data...
$(function () {
  $("form").on("submit", ajaxRequestToPhpForProcessing);
});

// Function to make AJAX post to PHP for processing.
// Returns "$all_budget_data" array as "result" from "form_submit_action.php" file...
function ajaxRequestToPhpForProcessing(e) {
  e.preventDefault();
  $.ajax({
    type: "post",
    url: "../php/form_submit_action.php",
    data: $("form").serialize(),
    dataType: "json",
    success: function (result) {
      document.getElementById("total-budget-amount").innerHTML = `${result[1]}`;

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
    },
  });
}
