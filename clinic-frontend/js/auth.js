const API_BASE_URL = "http://localhost:9000";

document.addEventListener("DOMContentLoaded", () => {
  const signupForm = document.getElementById("signupForm");
  const loginForm = document.getElementById("loginForm");
  const errorMessage = document.getElementById("errorMessage");

  if (signupForm) {
    signupForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      const name = document.getElementById("name").value;
      const email = document.getElementById("email").value;
      const password = document.getElementById("password").value;

      errorMessage.style.display = "none";

      try {
        const response = await fetch(`${API_BASE_URL}/api/register`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
          },
          body: JSON.stringify({ name, email, password }),
        });

        const data = await response.json();

        // check if unauthorized 401

        if (response.ok) {
          window.location.href = "../login/index.html";
        } else {
          errorMessage.textContent =
            data.error || data.message || "Signup failed.";
          errorMessage.style.display = "block";
        }
      } catch (error) {
        errorMessage.textContent = "Unable to connect to the server.";
        errorMessage.style.display = "block";
      }
    });
  }

  if (loginForm) {
    loginForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const email = document.getElementById("email").value;
      const password = document.getElementById("password").value;
      errorMessage.style.display = "none";
      try {
        const response = await fetch(`${API_BASE_URL}/api/login`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
          },
          body: JSON.stringify({ email, password }),
          credentials: "include",
        });
        const data = await response.json();
        console.log("Login response:", data);
        // check if unauthorized 401
        if (response.status === 401) {
          errorMessage.textContent =
            "Unauthorized. Please check your credentials.";
          errorMessage.style.display = "block";
          return;
        }
        if (response.ok) {
          window.location.href = "../dashboard/index.html";
        } else {
          errorMessage.textContent =
            data.error || data.message || "Login failed.";
          errorMessage.style.display = "block";
        }
      } catch (error) {
        errorMessage.textContent = "Unable to connect to the server.";
        errorMessage.style.display = "block";
      }
    });
  }
});
