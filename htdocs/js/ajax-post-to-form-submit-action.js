/******************************************************************************************************** 
- This file uses AJAX to send and receive data from the form-submit-action.php file, which interacts 
  with the SQL server. This enables the dashboard to update dynamically without a full page refresh.
- This file is included at the bottom of the "dashboard.php" file.
*********************************************************************************************************/

// Variable declaration for "Add an expense" form field...
const addAnExpenseFormField = document.getElementById("add-expense-container");

// Variable to get "Clear amount spent" button...
const clearAmountSpentBtn = document.getElementById("clear-amount-spent-fields");

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

// Variable declarations for income amount text field and budget analytics container...
const incomeAmountField = document.getElementById("budget-amount-field");
const budgetAnalyticsContainer = document.getElementById("budget-analytics");

// Variable declaration for expense list container...
const expenseListContainer = document.getElementById("expense-list-container");

// Variable declaration for container containing the total amount paid off...
const totalAmountPaid = document.getElementById("total-amount-paid");

// Variable declaration for container containing the total budget amount...
const totalBudgetAmount = document.getElementById("total-budget-amount");

// Variable declaration for budget amount field...
const budgetAmountField = document.getElementById("budget-amount-field");

// Variable declaration for expense emount field...
const expenseAmountField = document.getElementById("expense-amount");

// Variable declaration for income expense form...
const incomeExpenseForm = document.getElementById("income-expense-form");

// On page load, fire AJAX to retrieve data...
window.addEventListener("load", ajaxRequestToPhpForProcessing);

// Display analytics if user enters an income that isn't "0" or empty...
displayBudgetAnalytics();

// On form submit, fire AJAX to send/retrieve data...
incomeExpenseForm.addEventListener("submit", ajaxRequestToPhpForProcessing);

// On "Enter your budget amount" field value change, update the displayed budget info and format the text field input...
budgetAmountField.addEventListener("input", handleBudgetAmountInput);

// On "expense amount" field value change, format the text field input...
expenseAmountField.addEventListener("input", restrictFieldInput);

function handleBudgetAmountInput(event) {
	restrictFieldInput(event);
	ajaxRequestToPhpForProcessing(event);
}

// Function to make AJAX post to PHP for processing the user's input.
// Returns "$all_budget_data" array as "result" from "form-submit-action.php" file...
async function ajaxRequestToPhpForProcessing(e) {
	e.preventDefault();

	const formData = new FormData(incomeExpenseForm);
	const formBody = new URLSearchParams(formData).toString();

	try {
		const response = await fetch("../controllers/form-submit-action.php", {
			method: "POST",
			headers: {
				"Content-Type": "application/x-www-form-urlencoded",
			},
			body: formBody,
		});

		if (!response.ok) {
			throw new Error("Network response was not ok.");
		}

		const result = await response.json();

		updateBarGraph(result);
		printExpensesToPage(result["all_expenses"], result["income_amount"]);
		clearExpenseFields();
		displayBudgetAnalytics();
		printTotalAmountPaidOffToPage(result["total_amount_paid_off"]);
	} catch (error) {
		window.location.href = "../../error-pages/views/form-verification-error.php";
	}
}

// Function to remove an item from the expense list when the user clicks the 'X' button...
// Returns "$all_budget_data" array as "result" from "form-submit-action.php" file...
expenseListContainer.addEventListener("click", (e) => {
	e.preventDefault();

	const expenseItemForm = e.target.parentNode;

	if (e.target.classList.contains("remove-btn")) {
		let expenseItemFormToDelete = `#${expenseItemForm.id}`;

		// This invisible field is populated on the form item (expense item) that needs to be deleted.
		// PHP checks if this field has been filled, and if it has, deletes the item.
		let invisibleFieldForTogglingValue = document.getElementById(`delete-${expenseItemForm.id}`);

		// Add "delete" value to signal to PHP that this expense needs to be deleted from database...
		invisibleFieldForTogglingValue.value = "delete";

		(async () => {
			const formData = new FormData(expenseItemForm);
			const formBody = new URLSearchParams(formData).toString();

			try {
				const response = await fetch("../controllers/form-submit-action.php", {
					method: "POST",
					headers: {
						"Content-Type": "application/x-www-form-urlencoded",
					},
					body: formBody,
				});

				if (!response.ok) {
					throw new Error("Network response was not ok.");
				}

				const result = await response.json();

				updateBarGraph(result);
				printExpensesToPage(result["all_expenses"], result["income_amount"]);
				printTotalAmountPaidOffToPage(result["total_amount_paid_off"]);
			} catch (error) {
				window.location.href = "../../error-pages/views/form-verification-error.php";
			}
		})();
	}
});

// Function to save the "Spent" amount within each expense item field...
expenseListContainer.addEventListener("input", saveSpentAmountsForEachExpenseItemField);

function saveSpentAmountsForEachExpenseItemField(e) {
	e.preventDefault();
	if (e.target.classList.contains("amount-paid-field")) {
		let expenseItemFormToAddPaymentTo = document.getElementById(
			`expense-id-${e.target.getAttribute("data-expense-id")}`
		);
		(async () => {
			const formData = new FormData(expenseItemFormToAddPaymentTo);
			const formBody = new URLSearchParams(formData).toString();

			try {
				const response = await fetch("../controllers/form-submit-action.php", {
					method: "POST",
					headers: {
						"Content-Type": "application/x-www-form-urlencoded",
					},
					body: formBody,
				});

				if (!response.ok) {
					throw new Error("Network response was not ok.");
				}

				const result = await response.json();

				restrictFieldInput(e);
				printTotalAmountPaidOffToPage(result["total_amount_paid_off"]);
			} catch (error) {
				window.location.href = "../../error-pages/views/form-verification-error.php";
			}
		})();
	}
}

// Function to update bar graph and totals for each spending category...
function updateBarGraph(result) {
	const totalBudgetAmountLeft = document.getElementById("total-budget-amount-left");
	totalBudgetAmountLeft.innerHTML = `<p class="mb-0"><strong class="me-1 fs-3">$${result["total_amount_left_to_budget_with"]}</strong> left to budget with.</p>`;

	// If amount left for budget is negative, make number red...
	result["total_amount_left_to_budget_with"] < 0
		? totalBudgetAmountLeft.classList.add("negative-balance-to-budget-with")
		: totalBudgetAmountLeft.classList.remove("negative-balance-to-budget-with");

	// Adjust bars in bar chart for each spending type (adjust widths and attributes)...
	adjustBarsInGraph(result, fixedSpendingGraphBar, "fixed_percentage");
	adjustBarsInGraph(result, variableSpendingGraphBar, "variable_percentage");
	adjustBarsInGraph(result, debtSpendingGraphBar, "debt_percentage");
	adjustBarsInGraph(result, savingsSpendingGraphBar, "savings_percentage");
	adjustBarsInGraph(result, funSpendingGraphBar, "fun_percentage");
	adjustBarsInGraph(result, otherSpendingGraphBar, "other_percentage");

	// Post total amounts for each spending type...
	fixedSpendingTotal.innerHTML = `<h3 class="fs-5 fw-bold text-decoration-none">Fixed:</h3> $${result["total_fixed_spending"]}<br>${result["fixed_percentage"]}%`;
	variableSpendingTotal.innerHTML = `<h3 class="fs-5 fw-bold text-decoration-none">Variable:</h3> $${result["total_variable_spending"]}<br>${result["variable_percentage"]}%`;
	debtSpendingTotal.innerHTML = `<h3 class="fs-5 fw-bold text-decoration-none">Debt:</h3> $${result["total_debt_spending"]}<br>${result["debt_percentage"]}%`;
	savingsSpendingTotal.innerHTML = `<h3 class="fs-5 fw-bold text-decoration-none">Savings:</h3> $${result["total_savings_spending"]}<br>${result["savings_percentage"]}%`;
	funSpendingTotal.innerHTML = `<h3 class="fs-5 fw-bold text-decoration-none">Fun:</h3> $${result["total_fun_spending"]}<br>${result["fun_percentage"]}%`;
	otherSpendingTotal.innerHTML = `<h3 class="fs-5 fw-bold text-decoration-none">Other:</h3> $${result["total_other_spending"]}<br>${result["other_percentage"]}%`;
}

// Function to adjust bars in bar chart for each spending type (adjust widths and attributes)...
function adjustBarsInGraph(result, barInGraph, spendingType) {
	barInGraph.style.width = `${result[spendingType]}%`;
	barInGraph.setAttribute("aria-valuenow", `${result[spendingType]}`);
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

	// Get CSRF token from form and add it to each expense item that is printed to the page
	const csrfToken = document.querySelector('input[name="csrf_token"]').value;

	expenseListContainer.innerHTML = "";
	sortedResults.forEach((expenseItem) => {
		const containerForExpense = document.createElement("div");
		const expenseItemId = expenseItem[0];
		const userId = expenseItem[1];
		const expenseItemName = expenseItem[2];
		const expenseAmount = expenseItem[3];
		const expenseCategory = expenseItem[4];
		let expenseAmountSpent = expenseItem[5];

		// Remove default zero value from "Spent" field...
		if (expenseAmountSpent == "0") {
			expenseAmountSpent = "";
		}

		containerForExpense.innerHTML = `
		<form id="expense-id-${expenseItemId}" data-user-id="${userId}" class="container d-flex mb-3 p-3 pe-5 flex-column flex-md-row position-relative align-items-center bg-light rounded expense-entry-block">
			<input type="hidden" name="expense_id" value="${expenseItemId}">
			<input id="delete-expense-id-${expenseItemId}" type="hidden" name="expense_item_delete" value="">
			<p class="d-block mb-2 mb-sm-0 w-100 align-self-start">Name: <span class="fw-bold">${expenseItemName}</span></p>
			<p class="d-block mb-2 mb-sm-0 w-100 align-self-start">Amount: <span class="fw-bold pe-2">$${expenseAmount}</span>
				<span class="text-secondary d-md-block d-lg-inline">(
					${findExpensePercentageRelativeToTotalBudgetIncomeAmount(incomeAmount, expenseAmount)}%)
				</span>
			</p>
			<p class="d-block mb-2 mb-sm-0 ps-0 ps-md-5 w-100 align-self-start">
				<span class="fw-bold ${expenseCategory}">${expenseCategory}</span>
			</p>
			<div class="amount-paid-field-container row align-self-start me-2">
			<label class="col pe-0 px-md-0" for="data-expense-id-${expenseItemId}">Spent $</label>
			<input id="data-expense-id-${expenseItemId}" data-expense-id="${expenseItemId}" class="amount-paid-field form-control col pe-0 rounded-pill" type="text" maxlength="9" name="amount_paid" min="0" placeholder="0" autocomplete="off" aria-label="Amount paid" value="${expenseAmountSpent}">
			</div>
			<button type="submit" aria-label="Delete item" title="Delete this item" id="${expenseItemId}" class="remove-btn btn fw-bolder position-absolute px-3 py-0 mt-3 top-0 end-0">✕</button>
			<input type="hidden" name="csrf_token" value="${csrfToken}" class="form-control m-2 ms-0 p-2 ps-4 fs-5 rounded-pill d-none">
		</form>`;
		expenseListContainer.append(containerForExpense);
	});
}

// Function to limit what the user can enter into input fields...
function restrictFieldInput(e) {
	let value = e.target.value;

	// Remove non-numeric characters...
	value = value.replace(/\D/g, "");

	// Limit the length of the value to 11 characters...
	value = value.slice(0, 9);

	// Set the new value to the input field and remove leading zeros...
	e.target.value = value.replace(/^0+(?!$)/, "");
}

// At the end of the page, print the total amount spent (This adds up all the "Paid" fields within each expense item)
// and the total budget amount...
function printTotalAmountPaidOffToPage(totalAmountPaidOff) {
	// Print amount spent...
	totalAmountPaid.innerText = totalAmountPaidOff;
	// Print budget amount, or print "0" if empty...
	totalBudgetAmount.innerText = incomeAmountField.value;
	if (totalBudgetAmount.innerText == "") {
		totalBudgetAmount.innerText = "0";
	}
}

// Function to clear expense fields after each submission...
function clearExpenseFields() {
	if (expenseName.value !== "" && expenseAmount.value !== "") {
		expenseName.value = "";
		expenseAmount.value = "";
	}
}

// Function to clear "Spent" fields within each expense item...
clearAmountSpentBtn.addEventListener("click", (e) => {
	const expenseAmountPaidFields = [...document.getElementsByClassName("amount-paid-field")];
	expenseAmountPaidFields.forEach((paidField) => {
		paidField.value = "";
		// Manually call save function with a fake event object for each "Spent" field...
		saveSpentAmountsForEachExpenseItemField({
			preventDefault: () => {},
			target: paidField,
		});
	});
});

// Function to calculate percentages for each expense item in the list...
function findExpensePercentageRelativeToTotalBudgetIncomeAmount(totalIncomeAmount, expenseAmount) {
	let expensePercentage = (expenseAmount / totalIncomeAmount) * 100;
	let roundedExpensePercentage = Math.round(expensePercentage * 100) / 100;
	return roundedExpensePercentage;
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
