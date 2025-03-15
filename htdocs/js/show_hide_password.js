// Allow the user to show/hide the password on the login screen.
// Users can click the eye icon to toggle on/off the password character bubbles.

const iconToTogglePassword = document.getElementById("togglePassword");

iconToTogglePassword.addEventListener("click", function () {
  const passwordField = document.getElementById("password");
  const passwordFieldType = passwordField.type;

  // Toggle the input type between 'password' and 'text'...
  if (passwordFieldType === "password") {
    passwordField.type = "text";
  } else {
    passwordField.type = "password";
  }

  // Optionally change the icon (eye open/eye closed)...
  if (passwordField.type === "password") {
    this.innerHTML = '<i class="bi bi-eye"></i>';
  } else {
    this.innerHTML = '<i class="bi bi-eye-slash"></i>';
  }
});
