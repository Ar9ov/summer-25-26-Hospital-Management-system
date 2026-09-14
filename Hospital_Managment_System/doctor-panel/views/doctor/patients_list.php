<?php $pageTitle = 'Patients'; $page = 'patients'; require __DIR__ . '/../partials/header.php'; ?>
<div class="layout">
  <?php require __DIR__ . '/../partials/sidebar.php'; ?>

  <div class="main">
    <div class="topbar">
      <h1>Patients</h1>
      <a href="index.php?page=patient_form" class="btn">+ Add Patient</a>
    </div>

    <?php if ($flash): ?>
      <div class="flash flash-<?= h($flash['type']) ?>"><?= h($flash['message']) ?></div>
    <?php endif; ?>

    <div class="card">
      <div class="search-bar">
        <input type="text" id="patient-search-input" placeholder="Search by name or phone..." value="<?= h($keyword) ?>">
      </div>

      <table>
        <thead>
          <tr><th>Name</th><th>Age</th><th>Gender</th><th>Phone</th><th>Blood Group</th><th>Actions</th></tr>
        </thead>
        <tbody id="patients-tbody">
          <?php if (empty($patients)): ?>
            <tr><td colspan="6" style="text-align:center;color:#64748b;">No patients found.</td></tr>
          <?php else: foreach ($patients as $p): ?>
            <tr>
              <td><?= h($p['full_name']) ?></td>
              <td><?= h($p['age']) ?></td>
              <td><?= h($p['gender']) ?></td>
              <td><?= h($p['phone'] ?: '-') ?></td>
              <td><?= h($p['blood_group'] ?: '-') ?></td>
              <td>
                <a class="btn btn-outline btn-sm" href="index.php?page=patient_form&id=<?= (int) $p['id'] ?>">Edit</a>
                <a class="btn btn-outline btn-sm" href="index.php?page=patient_history&patient_id=<?= (int) $p['id'] ?>">History</a>
                <button class="btn btn-danger btn-sm" onclick="confirmDeletePatient(<?= (int) $p['id'] ?>)">Delete</button>
              </td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>

    <form id="delete-patient-form" method="POST" action="index.php?page=patient_delete" style="display:none;">
      <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
      <input type="hidden" name="id" value="">
    </form>
  </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
