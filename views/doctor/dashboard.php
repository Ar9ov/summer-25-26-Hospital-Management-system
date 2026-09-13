<?php
$pageTitle = 'Dashboard';
$myPatients = patient_search($conn, $currentDoctorId);
$myPrescriptions = prescription_list_by_doctor($conn, $currentDoctorId);
$myLeaves = leave_list_by_doctor($conn, $currentDoctorId);
$pendingLeaves = array_filter($myLeaves, fn($l) => $l['status'] === 'pending');

require __DIR__ . '/../partials/header.php';
?>
<div class="layout">
  <?php require __DIR__ . '/../partials/sidebar.php'; ?>

  <div class="main">
    <div class="topbar">
      <h1>Welcome, Dr. <?= h($currentDoctorName) ?></h1>
      <span class="user-chip"><?= date('l, F j, Y') ?></span>
    </div>

    <?php if ($flash): ?>
      <div class="flash flash-<?= h($flash['type']) ?>"><?= h($flash['message']) ?></div>
    <?php endif; ?>

    <div class="grid-cards">
      <div class="stat-card">
        <div class="num"><?= count($myPatients) ?></div>
        <div class="label">Patients under your care</div>
      </div>
      <div class="stat-card">
        <div class="num"><?= count($myPrescriptions) ?></div>
        <div class="label">Prescriptions written</div>
      </div>
      <div class="stat-card">
        <div class="num"><?= count($pendingLeaves) ?></div>
        <div class="label">Pending leave requests</div>
      </div>
    </div>

    <div class="card">
      <h3 style="margin-top:0;">Quick Actions</h3>
      <a href="index.php?page=patient_form" class="btn">+ Add Patient</a>
      <a href="index.php?page=prescription_writer" class="btn btn-outline">Write Prescription</a>
      <a href="index.php?page=emergency_leave" class="btn btn-outline">Request Emergency Leave</a>
    </div>

    <div class="card">
      <h3 style="margin-top:0;">Recent Prescriptions</h3>
      <table>
        <thead><tr><th>Patient</th><th>Diagnosis</th><th>Date</th><th></th></tr></thead>
        <tbody>
        <?php if (empty($myPrescriptions)): ?>
          <tr><td colspan="4" style="color:#64748b;">No prescriptions yet.</td></tr>
        <?php else: foreach (array_slice($myPrescriptions, 0, 5) as $p): ?>
          <tr>
            <td><?= h($p['patient_name']) ?></td>
            <td><?= h($p['diagnosis']) ?></td>
            <td><?= h($p['visit_date']) ?></td>
            <td><a href="index.php?page=prescription_view&id=<?= (int) $p['id'] ?>">View</a></td>
          </tr>
        <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
