<?php $pageTitle = 'Prescription Writer'; $page = 'prescription_writer'; require __DIR__ . '/../partials/header.php'; ?>
<div class="layout">
  <?php require __DIR__ . '/../partials/sidebar.php'; ?>

  <div class="main">
    <div class="topbar"><h1>Prescription Writer</h1></div>

    <?php if (!empty($errors['general'])): ?>
      <div class="flash flash-error"><?= h($errors['general']) ?></div>
    <?php endif; ?>
    <?php if (!empty($errors['medicines'])): ?>
      <div class="flash flash-error"><?= h($errors['medicines']) ?></div>
    <?php endif; ?>

    <div class="card" style="max-width:720px;">
      <form method="POST" id="prescription-form" action="index.php?page=prescription_writer" novalidate>
        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">

        <div class="field">
          <label for="patient_id">Patient</label>
          <select id="patient_id" name="patient_id" required>
            <option value="">Select a patient</option>
            <?php foreach ($patients as $p): ?>
              <option value="<?= (int) $p['id'] ?>" <?= (($_POST['patient_id'] ?? '') == $p['id']) ? 'selected' : '' ?>>
                <?= h($p['full_name']) ?> (<?= h($p['age']) ?>, <?= h($p['gender']) ?>)
              </option>
            <?php endforeach; ?>
          </select>
          <?php if (!empty($errors['patient_id'])): ?><div class="error-text"><?= h($errors['patient_id']) ?></div><?php endif; ?>
          <?php if (empty($patients)): ?>
            <div class="error-text">No patients yet -- <a href="index.php?page=patient_form">add one first</a>.</div>
          <?php endif; ?>
        </div>

        <div class="field">
          <label for="visit_date">Visit Date</label>
          <input type="date" id="visit_date" name="visit_date" value="<?= h($_POST['visit_date'] ?? date('Y-m-d')) ?>" required>
          <?php if (!empty($errors['visit_date'])): ?><div class="error-text"><?= h($errors['visit_date']) ?></div><?php endif; ?>
        </div>

        <div class="field">
          <label for="diagnosis">Diagnosis</label>
          <textarea id="diagnosis" name="diagnosis" required><?= h($_POST['diagnosis'] ?? '') ?></textarea>
          <?php if (!empty($errors['diagnosis'])): ?><div class="error-text"><?= h($errors['diagnosis']) ?></div><?php endif; ?>
        </div>

        <div class="field">
          <label>Medicines</label>
          <div id="medicine-rows">
            <div class="medicine-row">
              <input type="text" name="medicine_name[]" placeholder="Medicine name">
              <input type="text" name="dosage[]" placeholder="Dosage e.g. 500mg">
              <input type="text" name="frequency[]" placeholder="Frequency e.g. 2x/day">
              <input type="text" name="duration[]" placeholder="Duration e.g. 5 days">
              <button type="button" class="btn btn-outline btn-sm" onclick="this.parentElement.remove()">Remove</button>
            </div>
          </div>
          <button type="button" id="add-medicine-btn" class="btn btn-outline btn-sm" style="margin-top:6px;">+ Add Medicine</button>
        </div>

        <div class="field">
          <label for="notes">Additional Notes (optional)</label>
          <textarea id="notes" name="notes"><?= h($_POST['notes'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn">Save Prescription</button>
      </form>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
