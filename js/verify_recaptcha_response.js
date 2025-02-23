// Declare variables for login form and reCAPTCHA error message container
const loginForm = document.getElementById("login-form");
const formErrorMessageContainer = document.getElementById("error-container");

// Listen for form submission and confirm reCAPTCHA has been completed by the user...
loginForm.addEventListener("submit", function (e) {
  e.preventDefault(); // Prevent default form submission

  var recaptchaResponse = grecaptcha.getResponse();

  // If reCAPTCHA is not completed, stop form submission and show error message...
  if (recaptchaResponse.length === 0) {
    formErrorMessageContainer.innerHTML =
      '<p class="error-msg text-danger mb-3">Please verify you are not a bot. Complete the reCAPTCHA form by clicking the checkbox below!</p>';
    return;
  }

  // Submit the form with the reCAPTCHA response token
  var hiddenRecaptchaField = document.createElement("input");
  hiddenRecaptchaField.type = "hidden";
  hiddenRecaptchaField.name = "gRecaptchaResponse";
  hiddenRecaptchaField.value = recaptchaResponse;
  loginForm.appendChild(hiddenRecaptchaField);

  loginForm.submit(); // Now submit the form
});
