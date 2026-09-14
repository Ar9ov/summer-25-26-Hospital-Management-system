<?php $pageTitle = 'Emergency Leave'; $page = 'emergency_leave'; require __DIR__ . '/../partials/header.php'; ?>
<div class="layout">
  <?php require __DIR__ . '/../partials/sidebar.php'; ?>

  <div class="main">
    <div class="topbar"><h1>Emergency Leave</h1></div>

    <?php if ($flash): ?>
      <div class="flash flash-<?= h($flash['type']) ?>"><?= h($flash['message']) ?></div>
    <?php endif; ?>
    <?php if (!empty($errors['general'])): ?>
      <div class="flash flash-error"><?= h($errors['general']) ?></div>
    <?php endif; ?>

    <div class="card" style="max-width:560px;">
      <h3 style="margin-top:0;">Request Leave</h3>
      <form method="POST" id="leave-form" action="index.php?page=emergency_leave" novalidate>
        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">

        <div class="field">
          <label for="leave_from">From</label>
          <input type="date" id="leave_from" name="leave_from" value="<?= h($_POST['leave_from'] ?? '') ?>" required>
          <?php if (!empty($errors['leave_from'])): ?><div class="error-text"><?= h($errors['leave_from']) ?></div><?php endif; ?>
        </div>

        <div class="field">
          <label for="leave_to">To</label>
          <input type="date" id="leave_to" name="leave_to" value="<?= h($_POST['leave_to'] ?? '') ?>" required>
          <?php if (!empty($errors['leave_to'])): ?><div class="error-text"><?= h($errors['leave_to']) ?></div><?php endif; ?>
        </div>

        <div class="field">
          <label for="reason">Reason</label>
          <textarea id="reason" name="reason" required><?= h($_POST['reason'] ?? '') ?></textarea>
          <?php if (!empty($errors['reason'])): ?><div class="error-text"><?= h($errors['reason']) ?></div><?php endif; ?>
        </div>

        <button type="submit" class="btn">Submit Request</button>
      </form>
    </div>

    <div class="card">
      <h3 style="margin-top:0;">My Leave Requests</h3>
      <table>
        <thead><tr><th>From</th><th>To</th><th>Reason</th><th>Status</th><th></th></tr></thead>
        <tbody>
        <?php if (empty($leaves)): ?>
          <tr><td colspan="5" style="text-align:center;color:#64748b;">No leave requests yet.</td></tr>
        <?php else: foreach ($leaves as $l): ?>
          <tr>
            <td><?= h($l['leave_from']) ?></td>
            <td><?= h($l['leave_to']) ?></td>
            <td><?= h($l['reason']) ?></td>
            <td><span class="badge badge-<?= h($l['status']) ?>"><?= h(ucfirst($l['status'])) ?></span></td>
            <td>
              <?php if ($l['status'] === 'pending'): ?>
                <form method="POST" action="index.php?page=leave_cancel" style="display:inline;" onsubmit="return confirm('Cancel this request?');">
                  <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
                  <input type="hidden" name="id" value="<?= (int) $l['id'] ?>">
                  <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                </form>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
