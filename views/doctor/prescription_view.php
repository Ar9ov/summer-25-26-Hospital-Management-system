<?php $pageTitle = 'Prescription'; $page = 'prescriptions'; require __DIR__ . '/../partials/header.php'; ?>
<div class="layout">
  <?php require __DIR__ . '/../partials/sidebar.php'; ?>

  <div class="main">
    <div class="topbar">
      <h1>Prescription #<?= (int) $prescription['id'] ?></h1>
      <button class="btn btn-outline" onclick="window.print()">Print</button>
    </div>

    <div class="card">
      <p><strong>Patient:</strong> <?= h($prescription['patient_name']) ?> (<?= h($prescription['age']) ?>, <?= h($prescription['gender']) ?>)</p>
      <p><strong>Visit Date:</strong> <?= h($prescription['visit_date']) ?></p>
      <p><strong>Diagnosis:</strong> <?= h($prescription['diagnosis']) ?></p>
      <?php if (!empty($prescription['notes'])): ?>
        <p><strong>Notes:</strong> <?= nl2br(h($prescription['notes'])) ?></p>
      <?php endif; ?>

      <h3>Medicines</h3>
      <table>
        <thead><tr><th>Medicine</th><th>Dosage</th><th>Frequency</th><th>Duration</th></tr></thead>
        <tbody>
        <?php foreach ($prescription['medicines'] as $m): ?>
          <tr>
            <td><?= h($m['medicine_name']) ?></td>
            <td><?= h($m['dosage']) ?></td>
            <td><?= h($m['frequency']) ?></td>
            <td><?= h($m['duration']) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
