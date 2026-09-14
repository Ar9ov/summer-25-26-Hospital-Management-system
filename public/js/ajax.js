/**
 * AJAX/JSON layer. Uses fetch() against the ajax/*.php endpoints, which
 * each return JSON. Every request/response is JSON-encoded per the
 * Ajax/JSON project requirement.
 */

function renderPatientRows(patients) {
  const tbody = document.getElementById('patients-tbody');
  if (!tbody) return;

  if (patients.length === 0) {
    tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;color:#64748b;">No patients found.</td></tr>';
    return;
  }

  tbody.innerHTML = patients.map((p) => `
    <tr>
      <td>${escapeHtml(p.full_name)}</td>
      <td>${escapeHtml(String(p.age))}</td>
      <td>${escapeHtml(p.gender)}</td>
      <td>${escapeHtml(p.phone || '-')}</td>
      <td>${escapeHtml(p.blood_group || '-')}</td>
      <td>
        <a class="btn btn-outline btn-sm" href="index.php?page=patient_form&id=${p.id}">Edit</a>
        <a class="btn btn-outline btn-sm" href="index.php?page=patient_history&patient_id=${p.id}">History</a>
        <button class="btn btn-danger btn-sm" onclick="confirmDeletePatient(${p.id})">Delete</button>
      </td>
    </tr>
  `).join('');
}

function escapeHtml(str) {
  const div = document.createElement('div');
  div.textContent = str;
  return div.innerHTML;
}

let searchDebounceTimer = null;

function liveSearchPatients(keyword) {
  clearTimeout(searchDebounceTimer);
  searchDebounceTimer = setTimeout(() => {
    fetch('ajax/search_patients.php?q=' + encodeURIComponent(keyword))
      .then((res) => res.json())
      .then((data) => {
        if (data.success) renderPatientRows(data.patients);
      })
      .catch(() => console.error('Search request failed.'));
  }, 300); // debounce so we don't fire a request on every keystroke
}

function confirmDeletePatient(id) {
  if (!confirm('Delete this patient record? This cannot be undone.')) return;

  const form = document.getElementById('delete-patient-form');
  form.querySelector('[name=id]').value = id;
  form.submit();
}

document.addEventListener('DOMContentLoaded', () => {
  const searchInput = document.getElementById('patient-search-input');
  if (searchInput) {
    searchInput.addEventListener('input', (e) => liveSearchPatients(e.target.value));
  }
});
