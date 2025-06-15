const buttonToResendConfirmationCode = document.getElementById("resend-code");

buttonToResendConfirmationCode.addEventListener("click", (e) => {
  // Prevent the form from submitting...
  e.preventDefault();

  // Add a hidden field or change a parameter to indicate a resend action...
  const form = document.getElementById("confirmation-form");
  const resendInput = document.createElement("input");
  resendInput.type = "hidden";
  resendInput.name = "resend_code";
  resendInput.value = "1";
  form.appendChild(resendInput);

  // Submit the form (will now trigger the resend action on the server)...
  form.submit();
});
