const API_BASE_URL = "http://localhost:9000";

document.addEventListener("DOMContentLoaded", () => {
  const appointmentsBody = document.getElementById("appointmentsBody");
  const bookingForm = document.getElementById("bookingForm");
  const bookingMessage = document.getElementById("bookingMessage");
  const logoutBtn = document.getElementById("logoutBtn");

  fetchAppointments();

  if (bookingForm) {
    bookingForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      const appointmentDate = document.getElementById("appointment_date").value;
      const notes = document.getElementById("notes").value;

      bookingMessage.style.display = "none";

      try {
        const response = await fetch(`${API_BASE_URL}/api/appointments`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
          },
          credentials: "include",
          body: JSON.stringify({
            appointment_date: appointmentDate,
            notes: notes,
          }),
        });

        const data = await response.json();

        if (response.status === 401) {
          window.location.href = "../login/index.html";
          return;
        }

        if (response.ok || response.status === 201) {
          bookingMessage.textContent = "Appointment booked successfully!";
          bookingMessage.style.color = "green";
          bookingMessage.style.display = "block";

          bookingForm.reset();
          fetchAppointments(); // update the appointments list immediately without waiting for page refresh
        } else {
          bookingMessage.textContent =
            data.error || data.message || "Failed to book appointment.";
          bookingMessage.style.color = "red";
          bookingMessage.style.display = "block";
        }
      } catch (error) {
        bookingMessage.textContent = "Network error. Please try again.";
        bookingMessage.style.color = "red";
        bookingMessage.style.display = "block";
      }
    });
  }

  async function fetchAppointments() {
    try {
      const response = await fetch(`${API_BASE_URL}/api/appointments`, {
        method: "GET",
        headers: { Accept: "application/json" },
        credentials: "include",
      });

      if (response.status === 401) {
        window.location.href = "../login/index.html";
        return;
      }

      const data = await response.json();

      if (response.ok && data.status === "success") {
        renderTable(data.data);
      } else {
        appointmentsBody.innerHTML = `<tr><td colspan="3" style="color:red; text-align:center;">Failed to load data.</td></tr>`;
      }
    } catch (error) {
      appointmentsBody.innerHTML = `<tr><td colspan="3" style="color:red; text-align:center;">Network error. Server might be down.</td></tr>`;
    }
  }

  function renderTable(appointments) {
    appointmentsBody.innerHTML = "";

    if (!appointments || appointments.length === 0) {
      appointmentsBody.innerHTML = `<tr><td colspan="3" style="text-align:center;">No appointments found.</td></tr>`;
      return;
    }

    appointments.map((app) => {
      const row = document.createElement("tr");

      const dateObj = new Date(app.appointmentDate);
      const formattedDate = isNaN(dateObj)
        ? app.appointmentDate
        : dateObj.toLocaleString();

      row.innerHTML = `
                <td>${formattedDate}</td>
                <td><strong>${app.status.toUpperCase()}</strong></td>
                <td>${app.notes || "---"}</td>
            `;
      appointmentsBody.appendChild(row);
    });
  }

  if (logoutBtn) {
    logoutBtn.addEventListener("click", async () => {
      try {
        const res = await fetch(`${API_BASE_URL}/api/logout`, {
          method: "POST",
          headers: { Accept: "application/json" },
          credentials: "include",
        });
        const data = await res.json();
        if (res.ok && data.status === "success")
          window.location.href = "../login/index.html";
      } catch (error) {
        console.error("Logout failed:", error);
        window.location.href = "../login/index.html";
      }
    });
  }
});
