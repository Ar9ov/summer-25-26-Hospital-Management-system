const api = 'controllers/reception_controller.php';

async function request(url, options = {}) {
    const r = await fetch(url, options);
    return await r.json();
}

function formData(form) {
    return new FormData(form);
}

async function loadPatients() {
    const q = document.getElementById('patientSearch').value;
    const r = await request(`${api}?action=patients&search=${encodeURIComponent(q)}`);
    document.getElementById('patientRows').innerHTML = r.data.map(p => `
        <tr>
            <td>${esc(p.patient_name)}</td>
            <td>${esc(p.phone)}</td>
            <td>${p.age}</td>
            <td>${p.wheelchair ? 'Yes' : 'No'}</td>
            <td>
                <button type="button" onclick="editPatient(${p.id})">Edit</button>
                <button type="button" onclick="deletePatient(${p.id})">Delete</button>
            </td>
        </tr>
    `).join('');

    const select = document.getElementById('invoicePatient');
    if (select) {
        const current = select.value;
        select.innerHTML = '<option value="">Select patient</option>' +
            r.data.map(p => `<option value="${p.id}">${esc(p.patient_name)}</option>`).join('');
        select.value = current;
    }
}

async function loadInvoices() {
    const q = document.getElementById('invoiceSearch').value;
    const r = await request(`${api}?action=invoices&search=${encodeURIComponent(q)}`);
    document.getElementById('invoiceRows').innerHTML = r.data.map(i => `
        <tr>
            <td>${esc(i.invoice_no)}</td>
            <td>${esc(i.patient_name)}</td>
            <td>${i.total_bill}</td>
            <td class="${i.payment_status.toLowerCase()}">${i.payment_status}</td>
            <td>
                <button type="button" onclick="setPayment(${i.id},'Paid')">Paid</button>
                <button type="button" onclick="setPayment(${i.id},'Pending')">Pending</button>
                <button type="button" onclick="printInvoice(${i.id})">Print</button>
            </td>
        </tr>
    `).join('');
}

async function editPatient(id) {
    const r = await request(`${api}?action=patients`);
    const p = r.data.find(x => x.id == id);
    if (!p) return;
    for (const k of ['patient_name', 'phone', 'age', 'gender']) {
        document.querySelector(`[name=${k}]`).value = p[k];
    }
    document.getElementById('patient_id').value = p.id;
    document.querySelector('[name=wheelchair]').checked = !!Number(p.wheelchair);
}

async function deletePatient(id) {
    if (!confirm('Delete patient?')) return;
    const f = new FormData();
    f.append('action', 'delete_patient');
    f.append('id', id);
    await request(api, { method: 'POST', body: f });
    loadPatients();
    loadInvoices();
}

async function setPayment(id, status) {
    const f = new FormData();
    f.append('action', 'payment');
    f.append('id', id);
    f.append('payment_status', status);
    await request(api, { method: 'POST', body: f });
    loadInvoices();
}

async function printInvoice(id) {
    const r = await request(`${api}?action=invoice&id=${id}`);
    const i = r.data;
    const w = window.open('', '_blank');
    w.document.write(`
        <h2>Invoice ${esc(i.invoice_no)}</h2>
        <p>Patient: ${esc(i.patient_name)}</p>
        <p>Phone: ${esc(i.phone)}</p>
        <p>Consultation Fee: ${i.consultation_fee}</p>
        <h3>Total Bill: ${i.total_bill}</h3>
        <p>Status: ${i.payment_status}</p>
        <button onclick="window.print()">Print</button>
    `);
    w.document.close();
}

document.getElementById('patientForm').addEventListener('submit', async e => {
    e.preventDefault();
    const f = formData(e.target);
    f.append('action', document.getElementById('patient_id').value ? 'update_patient' : 'add_patient');
    const r = await request(api, { method: 'POST', body: f });
    if (!r.success) {
        alert(r.message);
        return;
    }
    e.target.reset();
    document.getElementById('patient_id').value = '';
    loadPatients();
});

document.getElementById('invoiceForm').addEventListener('submit', async e => {
    e.preventDefault();
    const f = formData(e.target);
    f.append('action', 'add_invoice');
    const r = await request(api, { method: 'POST', body: f });
    document.getElementById('invoiceMessage').textContent = r.message;
    if (r.success) {
        e.target.reset();
        loadInvoices();
    }
});

document.getElementById('patientSearch').addEventListener('input', loadPatients);
document.getElementById('invoiceSearch').addEventListener('input', loadInvoices);

function esc(s) {
    return String(s).replace(/[&<>"']/g, c => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
    }[c]));
}

loadPatients();
loadInvoices();
