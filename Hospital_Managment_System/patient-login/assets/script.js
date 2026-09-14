/* ============================================================
   Hospital Patient System — Client-side form validation
   Applies to: login.php, register.php, appointment.php
   ============================================================ */

document.addEventListener("DOMContentLoaded", function () {
    initLoginForm();
    initRegisterForm();
    initAppointmentForm();
});

/* ---------------- Shared helpers ---------------- */

function isValidEmail(value) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value.trim());
}

function isValidMobile(value) {
    // Bangladeshi mobile format: 01 followed by 9 digits (11 digits total)
    return /^01[0-9]{9}$/.test(value.trim());
}

function showError(input, message) {
    clearError(input);
    input.classList.add("input-error");

    const error = document.createElement("div");
    error.className = "field-error";
    error.textContent = message;
    error.setAttribute("data-error-for", input.id);

    input.insertAdjacentElement("afterend", error);
}

function clearError(input) {
    input.classList.remove("input-error");
    const existing = input.parentElement.querySelector(
        '.field-error[data-error-for="' + input.id + '"]'
    );
    if (existing) {
        existing.remove();
    }
}

function clearAllErrors(form) {
    form.querySelectorAll(".field-error").forEach(function (el) {
        el.remove();
    });
    form.querySelectorAll(".input-error").forEach(function (el) {
        el.classList.remove("input-error");
    });
}

/* ---------------- Login form ---------------- */

function initLoginForm() {
    const form = document.querySelector("form#loginForm");
    if (!form) return;

    form.addEventListener("submit", function (e) {
        clearAllErrors(form);
        let valid = true;

        const email = form.querySelector("#email");
        const password = form.querySelector("#password");

        if (!email.value.trim()) {
            showError(email, "Email is required.");
            valid = false;
        } else if (!isValidEmail(email.value)) {
            showError(email, "Enter a valid email address.");
            valid = false;
        }

        if (!password.value) {
            showError(password, "Password is required.");
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
        }
    });
}

/* ---------------- Register form ---------------- */

function initRegisterForm() {
    const form = document.querySelector("form#registerForm");
    if (!form) return;

    form.addEventListener("submit", function (e) {
        clearAllErrors(form);
        let valid = true;

        const name = form.querySelector("#name");
        const email = form.querySelector("#email");
        const mobile = form.querySelector("#mobile");
        const password = form.querySelector("#password");

        if (!name.value.trim()) {
            showError(name, "Full name is required.");
            valid = false;
        }

        if (!email.value.trim()) {
            showError(email, "Email is required.");
            valid = false;
        } else if (!isValidEmail(email.value)) {
            showError(email, "Enter a valid email address.");
            valid = false;
        }

        if (!mobile.value.trim()) {
            showError(mobile, "Mobile number is required.");
            valid = false;
        } else if (!isValidMobile(mobile.value)) {
            showError(mobile, "Enter a valid 11-digit mobile number (e.g. 01712345678).");
            valid = false;
        }

        if (!password.value) {
            showError(password, "Password is required.");
            valid = false;
        } else if (password.value.length < 6) {
            showError(password, "Password must be at least 6 characters.");
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
        }
    });
}

/* ---------------- Appointment booking form ---------------- */

function initAppointmentForm() {
    const form = document.querySelector("form#appointmentForm");
    if (!form) return;

    form.addEventListener("submit", function (e) {
        clearAllErrors(form);
        let valid = true;

        const doctor = form.querySelector("#doctor_id");
        const date = form.querySelector("#appointment_date");

        if (doctor && !doctor.value) {
            showError(doctor, "Please select a doctor.");
            valid = false;
        }

        if (!date.value) {
            showError(date, "Please choose a date.");
            valid = false;
        } else {
            const chosen = new Date(date.value + "T00:00:00");
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (chosen < today) {
                showError(date, "Appointment date cannot be in the past.");
                valid = false;
            }
        }

        if (!valid) {
            e.preventDefault();
        }
    });
}
