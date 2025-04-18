// Allow the user to show/hide the passwords in forms.
// Users can click the eye icon to toggle on/off the password character bubbles.

const iconToTogglePassword = document.getElementById("togglePassword");
const passwordField = document.getElementById("password");

iconToTogglePassword.addEventListener("click", function () {
  const isPassword = passwordField.type === "password";
  passwordField.type = isPassword ? "text" : "password";
  this.innerHTML = isPassword
    ? '<i class="bi bi-eye-slash"></i>'
    : '<i class="bi bi-eye"></i>';
});
