<?php $pageTitle = 'Patient History'; $page = 'patient_history'; require __DIR__ . '/../partials/header.php'; ?>
<div class="layout">
  <?php require __DIR__ . '/../partials/sidebar.php'; ?>

  <div class="main">
    <div class="topbar"><h1>Patient History</h1></div>

    <?php if ($flash): ?>
      <div class="flash flash-<?= h($flash['type']) ?>"><?= h($flash['message']) ?></div>
    <?php endif; ?>

    <div class="card">
      <form method="GET" action="index.php" class="search-bar">
        <input type="hidden" name="page" value="patient_history">
        <select name="patient_id" onchange="this.form.submit()" style="max-width:280px;">
          <option value="">Select a patient</option>
          <?php foreach ($patients as $p): ?>
            <option value="<?= (int) $p['id'] ?>" <?= $selectedPatientId == $p['id'] ? 'selected' : '' ?>>
              <?= h($p['full_name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <input type="text" name="q" placeholder="Search this patient's history..." value="<?= h($keyword) ?>">
        <button type="submit" class="btn btn-outline">Search</button>
      </form>
    </div>

    <?php if ($selectedPatient): ?>
      <div class="card">
        <h3 style="margin-top:0;">Timeline for <?= h($selectedPatient['full_name']) ?></h3>

        <?php if (empty($timeline)): ?>
          <p style="color:#64748b;">No history recorded yet for this patient.</p>
        <?php else: ?>
          <div class="timeline">
            <?php foreach ($timeline as $entry): ?>
              <div class="timeline-item">
                <div class="timeline-date"><?= h($entry['date']) ?> · <?= $entry['type'] === 'prescription' ? 'Prescription' : 'Visit Note' ?> · Dr. <?= h($entry['doctor_name']) ?></div>
                <p style="margin:4px 0;"><strong><?= h($entry['summary']) ?></strong></p>
                <?php if (!empty($entry['details'])): ?><p style="margin:0;color:#475569;"><?= h($entry['details']) ?></p><?php endif; ?>
                <?php if (!empty($entry['medicines'])): ?>
                  <ul style="margin:6px 0 0;padding-left:18px;font-size:13px;color:#475569;">
                    <?php foreach ($entry['medicines'] as $m): ?>
                      <li><?= h($m['medicine_name']) ?> - <?= h($m['dosage']) ?>, <?= h($m['frequency']) ?>, <?= h($m['duration']) ?></li>
                    <?php endforeach; ?>
                  </ul>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <div class="card" style="max-width:600px;">
        <h3 style="margin-top:0;">Add a Visit Note</h3>
        <p style="color:#64748b;font-size:13px;margin-top:-8px;">Use this to log a visit that doesn't need a full prescription.</p>

        <form method="POST" action="index.php?page=patient_history&patient_id=<?= (int) $selectedPatientId ?>" novalidate>
          <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
          <input type="hidden" name="patient_id" value="<?= (int) $selectedPatientId ?>">
          <input type="hidden" name="add_visit_note" value="1">

          <div class="field">
            <label for="visit_date">Visit Date</label>
            <input type="date" id="visit_date" name="visit_date" value="<?= h(date('Y-m-d')) ?>" required>
            <?php if (!empty($errors['visit_date'])): ?><div class="error-text"><?= h($errors['visit_date']) ?></div><?php endif; ?>
          </div>

          <div class="field">
            <label for="symptoms">Symptoms</label>
            <textarea id="symptoms" name="symptoms" required></textarea>
            <?php if (!empty($errors['symptoms'])): ?><div class="error-text"><?= h($errors['symptoms']) ?></div><?php endif; ?>
          </div>

          <div class="field">
            <label for="treatment_notes">Treatment Notes (optional)</label>
            <textarea id="treatment_notes" name="treatment_notes"></textarea>
          </div>

          <button type="submit" class="btn">Add to History</button>
        </form>
      </div>
    <?php else: ?>
      <p style="color:#64748b;">Select a patient above to view their history.</p>
    <?php endif; ?>
  </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
