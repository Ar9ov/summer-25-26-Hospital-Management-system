/**
 * Client-side validation for UX only. The PHP layer re-validates every
 * field before touching the database, since JS validation can be bypassed.
 */

function showFieldError(input, message) {
  clearFieldError(input);
  input.classList.add('invalid');
  const el = document.createElement('div');
  el.className = 'error-text js-error';
  el.textContent = message;
  input.insertAdjacentElement('afterend', el);
}

function clearFieldError(input) {
  input.classList.remove('invalid');
  const next = input.nextElementSibling;
  if (next && next.classList.contains('js-error')) {
    next.remove();
  }
}

function validatePatientForm(form) {
  let valid = true;
  const name = form.querySelector('[name=full_name]');
  const age = form.querySelector('[name=age]');
  const gender = form.querySelector('[name=gender]');
  const phone = form.querySelector('[name=phone]');

  if (name.value.trim().length < 2) {
    showFieldError(name, 'Full name must be at least 2 characters.');
    valid = false;
  } else clearFieldError(name);

  if (age.value === '' || age.value < 0 || age.value > 130) {
    showFieldError(age, 'Enter a valid age (0-130).');
    valid = false;
  } else clearFieldError(age);

  if (!gender.value) {
    showFieldError(gender, 'Select a gender.');
    valid = false;
  } else clearFieldError(gender);

  if (phone.value && !/^[0-9+\-\s]{6,20}$/.test(phone.value)) {
    showFieldError(phone, 'Enter a valid phone number.');
    valid = false;
  } else clearFieldError(phone);

  return valid;
}

function validatePrescriptionForm(form) {
  let valid = true;
  const patient = form.querySelector('[name=patient_id]');
  const diagnosis = form.querySelector('[name=diagnosis]');
  const visitDate = form.querySelector('[name=visit_date]');
  const medRows = form.querySelectorAll('.medicine-row');

  if (!patient.value) {
    showFieldError(patient, 'Select a patient.');
    valid = false;
  } else clearFieldError(patient);

  if (diagnosis.value.trim().length < 3) {
    showFieldError(diagnosis, 'Diagnosis must be at least 3 characters.');
    valid = false;
  } else clearFieldError(diagnosis);

  if (!visitDate.value) {
    showFieldError(visitDate, 'Select the visit date.');
    valid = false;
  } else clearFieldError(visitDate);

  let hasFilledMedicine = false;
  medRows.forEach((row) => {
    const nameInput = row.querySelector('[name="medicine_name[]"]');
    if (nameInput && nameInput.value.trim() !== '') hasFilledMedicine = true;
  });
  if (!hasFilledMedicine) {
    alert('Add at least one medicine to the prescription.');
    valid = false;
  }

  return valid;
}

function validateLeaveForm(form) {
  let valid = true;
  const from = form.querySelector('[name=leave_from]');
  const to = form.querySelector('[name=leave_to]');
  const reason = form.querySelector('[name=reason]');

  if (!from.value) {
    showFieldError(from, 'Select a start date.');
    valid = false;
  } else clearFieldError(from);

  if (!to.value) {
    showFieldError(to, 'Select an end date.');
    valid = false;
  } else if (from.value && to.value < from.value) {
    showFieldError(to, 'End date cannot be before start date.');
    valid = false;
  } else clearFieldError(to);

  if (reason.value.trim().length < 5) {
    showFieldError(reason, 'Give a brief reason (min 5 characters).');
    valid = false;
  } else clearFieldError(reason);

  return valid;
}

// Dynamically add / remove medicine rows on the Prescription Writer page.
function addMedicineRow() {
  const container = document.getElementById('medicine-rows');
  const row = document.createElement('div');
  row.className = 'medicine-row';
  row.innerHTML = `
    <input type="text" name="medicine_name[]" placeholder="Medicine name">
    <input type="text" name="dosage[]" placeholder="Dosage e.g. 500mg">
    <input type="text" name="frequency[]" placeholder="Frequency e.g. 2x/day">
    <input type="text" name="duration[]" placeholder="Duration e.g. 5 days">
    <button type="button" class="btn btn-outline btn-sm" onclick="this.parentElement.remove()">Remove</button>
  `;
  container.appendChild(row);
}

document.addEventListener('DOMContentLoaded', () => {
  const patientForm = document.getElementById('patient-form');
  if (patientForm) {
    patientForm.addEventListener('submit', (e) => {
      if (!validatePatientForm(patientForm)) e.preventDefault();
    });
  }

  const prescriptionForm = document.getElementById('prescription-form');
  if (prescriptionForm) {
    prescriptionForm.addEventListener('submit', (e) => {
      if (!validatePrescriptionForm(prescriptionForm)) e.preventDefault();
    });
  }

  const leaveForm = document.getElementById('leave-form');
  if (leaveForm) {
    leaveForm.addEventListener('submit', (e) => {
      if (!validateLeaveForm(leaveForm)) e.preventDefault();
    });
  }

  const addMedBtn = document.getElementById('add-medicine-btn');
  if (addMedBtn) addMedBtn.addEventListener('click', addMedicineRow);
});
