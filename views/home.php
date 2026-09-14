<section>
    <h2>Counter R - Walk-in Patient</h2>
    <form id="patientForm">
        <input type="hidden" name="id" id="patient_id">
        <input name="patient_name" placeholder="Patient name" required>
        <input name="phone" placeholder="Phone" required>
        <input type="number" name="age" placeholder="Age" required>
        <select name="gender" required>
            <option value="">Gender</option>
            <option>Male</option>
            <option>Female</option>
            <option>Other</option>
        </select>
        <label><input type="checkbox" name="wheelchair"> Wheelchair provided</label>
        <button type="submit">Save Patient</button>
    </form>
    <input id="patientSearch" placeholder="Search patient">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Age</th>
                <th>Wheelchair</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="patientRows"></tbody>
    </table>
</section>

<section>
    <h2>Invoice Creation</h2>
    <form id="invoiceForm">
        <select name="patient_id" id="invoicePatient" required>
            <option value="">Select patient</option>
            <?php foreach ($patients as $p): ?>
                <option value="<?= (int)$p['id'] ?>"><?= htmlspecialchars($p['patient_name']) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="number" step="0.01" name="consultation_fee" placeholder="Consultation fee" required>
        <button type="submit">Create Invoice</button>
    </form>
    <div id="invoiceMessage"></div>
    <input id="invoiceSearch" placeholder="Search invoice">
    <table>
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Patient</th>
                <th>Total</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="invoiceRows"></tbody>
    </table>
</section>
