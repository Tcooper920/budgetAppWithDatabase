// On page load, retrieve data with AJAX...
$(window).load(ajaxRequestToPhpForProcessing);

// On form submit, fire AJAX...
$(function () {
  $("form").on("submit", ajaxRequestToPhpForProcessing);
});

// Function to make AJAX post to PHP for processing...
function ajaxRequestToPhpForProcessing(e) {
  e.preventDefault();
  $.ajax({
    type: "post",
    url: "form_submit_action.php",
    data: $("form").serialize(),
    dataType: "json",
    success: function (result) {
      console.log(result);
      document.getElementById("total-budget-amount").innerHTML = `${result[1]}`;

      // Adjust bars in bar graph chart...
      document.getElementById("fixed-spending-progress-bar").style.width = `${result[8]}%`;
      document.getElementById("fixed-spending-progress-bar").setAttribute("aria-valuenow", `${result[8]}%`);

      document.getElementById("variable-spending-progress-bar").style.width = `${result[9]}%`;
      document.getElementById("variable-spending-progress-bar").setAttribute("aria-valuenow", `${result[9]}%`);

      document.getElementById("debt-spending-progress-bar").style.width = `${result[10]}%`;
      document.getElementById("debt-spending-progress-bar").setAttribute("aria-valuenow", `${result[10]}%`);

      document.getElementById("savings-spending-progress-bar").style.width = `${result[11]}%`;
      document.getElementById("savings-spending-progress-bar").setAttribute("aria-valuenow", `${result[11]}%`);

      document.getElementById("fun-spending-progress-bar").style.width = `${result[12]}%`;
      document.getElementById("fun-spending-progress-bar").setAttribute("aria-valuenow", `${result[12]}%`);

      document.getElementById("other-spending-progress-bar").style.width = `${result[13]}%`;
      document.getElementById("other-spending-progress-bar").setAttribute("aria-valuenow", `${result[13]}%`);

      // Post total amounts for each spending type...
      document.getElementById("fixed-spending-total").innerHTML = `Fixed spending total: ${result[2]}`;
      document.getElementById("variable-spending-total").innerHTML = `Variable spending total: ${result[3]}`;
      document.getElementById("debt-spending-total").innerHTML = `Debt spending total: ${result[4]}`;
      document.getElementById("savings-spending-total").innerHTML = `Savings spending total: ${result[5]}`;
      document.getElementById("fun-spending-total").innerHTML = `Fun spending total: ${result[6]}`;
      document.getElementById("other-spending-total").innerHTML = `Other spending total: ${result[7]}`;
    },
  });
}
