<div class="sidebar">
  <h2>🩺 Doctor Panel</h2>
  <nav>
    <a href="index.php?page=doctor_dashboard" class="<?= $page === 'doctor_dashboard' ? 'active' : '' ?>">Dashboard</a>

    <div class="section-label">Patients</div>
    <a href="index.php?page=patients" class="<?= $page === 'patients' ? 'active' : '' ?>">All Patients</a>
    <a href="index.php?page=patient_form" class="<?= $page === 'patient_form' ? 'active' : '' ?>">Add Patient</a>

    <div class="section-label">My Features</div>
    <a href="index.php?page=prescription_writer" class="<?= $page === 'prescription_writer' ? 'active' : '' ?>">Prescription Writer</a>
    <a href="index.php?page=prescriptions" class="<?= $page === 'prescriptions' ? 'active' : '' ?>">All Prescriptions</a>
    <a href="index.php?page=patient_history" class="<?= $page === 'patient_history' ? 'active' : '' ?>">Patient History</a>
    <a href="index.php?page=emergency_leave" class="<?= $page === 'emergency_leave' ? 'active' : '' ?>">Emergency Leave</a>

    <div class="section-label">Account</div>
    <a href="index.php?page=logout">Logout</a>
  </nav>
</div>
