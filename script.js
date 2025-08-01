document.addEventListener("DOMContentLoaded", function () {
  const toggleButton = document.getElementById("toggle-mode");
  const formTitle = document.getElementById("form-title");
  const registerForm = document.getElementById("register-form");
  const loginForm = document.getElementById("login-form");
  const toggleText = document.getElementById("toggle-text");

  toggleButton.addEventListener("click", function () {
    document.body.classList.toggle("dark-mode");
    toggleButton.textContent = document.body.classList.contains("dark-mode")
      ? "☀️ Light Mode"
      : "🌙 Dark Mode";
  });

  toggleText.addEventListener("click", (e) => {
    e.preventDefault();
    if (registerForm.style.display === "none") {
      registerForm.style.display = "block";
      loginForm.style.display = "none";
      formTitle.textContent = "Create Account";
      toggleText.innerHTML =
        `Already have an account? <a id="toggle-link">Sign In</a>`;
    } else {
      registerForm.style.display = "none";
      loginForm.style.display = "block";
      formTitle.textContent = "Welcome Back";
      toggleText.innerHTML =
        `Don't have an account? <a id="toggle-link">Sign Up</a>`;
    }
  });
});
