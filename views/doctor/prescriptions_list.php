<?php $pageTitle = 'Prescriptions'; $page = 'prescriptions'; require __DIR__ . '/../partials/header.php'; ?>
<div class="layout">
  <?php require __DIR__ . '/../partials/sidebar.php'; ?>

  <div class="main">
    <div class="topbar">
      <h1>All Prescriptions</h1>
      <a href="index.php?page=prescription_writer" class="btn">+ New Prescription</a>
    </div>

    <?php if ($flash): ?>
      <div class="flash flash-<?= h($flash['type']) ?>"><?= h($flash['message']) ?></div>
    <?php endif; ?>

    <div class="card">
      <table>
        <thead><tr><th>Patient</th><th>Diagnosis</th><th>Visit Date</th><th></th></tr></thead>
        <tbody>
        <?php if (empty($prescriptions)): ?>
          <tr><td colspan="4" style="text-align:center;color:#64748b;">No prescriptions yet.</td></tr>
        <?php else: foreach ($prescriptions as $p): ?>
          <tr>
            <td><?= h($p['patient_name']) ?></td>
            <td><?= h($p['diagnosis']) ?></td>
            <td><?= h($p['visit_date']) ?></td>
            <td><a class="btn btn-outline btn-sm" href="index.php?page=prescription_view&id=<?= (int) $p['id'] ?>">View</a></td>
          </tr>
        <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
