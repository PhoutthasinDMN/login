// Toggle password visibility
document.addEventListener('DOMContentLoaded', function() {
  const userPasswordEl = document.querySelector("#password");
  const togglePasswordEl = document.querySelector("#togglePassword");

  if (userPasswordEl && togglePasswordEl) {
    togglePasswordEl.addEventListener("click", function () {
      if (this.checked === true) {
        userPasswordEl.setAttribute("type", "text");
      } else {
        userPasswordEl.setAttribute("type", "password");
      }
    });
  }
});