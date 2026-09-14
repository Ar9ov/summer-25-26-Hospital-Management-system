<?php
$pageTitle = $patient['id'] ? 'Edit Patient' : 'Add Patient';
$page = 'patient_form';
require __DIR__ . '/../partials/header.php';
?>
<div class="layout">
  <?php require __DIR__ . '/../partials/sidebar.php'; ?>

  <div class="main">
    <div class="topbar">
      <h1><?= $patient['id'] ? 'Edit Patient' : 'Add Patient' ?></h1>
    </div>

    <?php if (!empty($errors['general'])): ?>
      <div class="flash flash-error"><?= h($errors['general']) ?></div>
    <?php endif; ?>

    <div class="card" style="max-width:600px;">
      <form method="POST" id="patient-form" action="index.php?page=patient_form<?= $patient['id'] ? '&id=' . (int) $patient['id'] : '' ?>" novalidate>
        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">

        <div class="field">
          <label for="full_name">Full Name</label>
          <input type="text" id="full_name" name="full_name" value="<?= h($patient['full_name']) ?>" required>
          <?php if (!empty($errors['full_name'])): ?><div class="error-text"><?= h($errors['full_name']) ?></div><?php endif; ?>
        </div>

        <div class="field">
          <label for="age">Age</label>
          <input type="number" id="age" name="age" min="0" max="130" value="<?= h($patient['age']) ?>" required>
          <?php if (!empty($errors['age'])): ?><div class="error-text"><?= h($errors['age']) ?></div><?php endif; ?>
        </div>

        <div class="field">
          <label for="gender">Gender</label>
          <select id="gender" name="gender" required>
            <option value="">Select gender</option>
            <?php foreach (['male', 'female', 'other'] as $g): ?>
              <option value="<?= $g ?>" <?= $patient['gender'] === $g ? 'selected' : '' ?>><?= ucfirst($g) ?></option>
            <?php endforeach; ?>
          </select>
          <?php if (!empty($errors['gender'])): ?><div class="error-text"><?= h($errors['gender']) ?></div><?php endif; ?>
        </div>

        <div class="field">
          <label for="phone">Phone</label>
          <input type="tel" id="phone" name="phone" value="<?= h($patient['phone']) ?>">
          <?php if (!empty($errors['phone'])): ?><div class="error-text"><?= h($errors['phone']) ?></div><?php endif; ?>
        </div>

        <div class="field">
          <label for="address">Address</label>
          <textarea id="address" name="address"><?= h($patient['address']) ?></textarea>
        </div>

        <div class="field">
          <label for="blood_group">Blood Group</label>
          <select id="blood_group" name="blood_group">
            <option value="">Unknown</option>
            <?php foreach (['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg): ?>
              <option value="<?= $bg ?>" <?= $patient['blood_group'] === $bg ? 'selected' : '' ?>><?= $bg ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <button type="submit" class="btn"><?= $patient['id'] ? 'Update' : 'Save' ?> Patient</button>
        <a href="index.php?page=patients" class="btn btn-outline">Cancel</a>
      </form>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
