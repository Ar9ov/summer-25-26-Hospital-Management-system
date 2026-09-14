<?php $pageTitle = 'Doctor Sign Up'; require __DIR__ . '/../partials/header.php'; ?>

<div class="auth-wrapper">
  <div class="auth-card">
    <h1>Doctor Sign Up</h1>
    <p class="subtitle">Create your doctor account.</p>

    <?php if (!empty($errors['general'])): ?>
      <div class="flash flash-error"><?= h($errors['general']) ?></div>
    <?php endif; ?>

    <form method="POST" action="index.php?page=signup" id="signup-form" novalidate>
      <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">

      <div class="field">
        <label for="full_name">Full Name</label>
        <input type="text" id="full_name" name="full_name" value="<?= h($_POST['full_name'] ?? '') ?>" required>
        <?php if (!empty($errors['full_name'])): ?><div class="error-text"><?= h($errors['full_name']) ?></div><?php endif; ?>
      </div>

      <div class="field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= h($_POST['email'] ?? '') ?>" required>
        <?php if (!empty($errors['email'])): ?><div class="error-text"><?= h($errors['email']) ?></div><?php endif; ?>
      </div>

      <div class="field">
        <label for="phone">Phone</label>
        <input type="tel" id="phone" name="phone" value="<?= h($_POST['phone'] ?? '') ?>">
      </div>

      <div class="field">
        <label for="specialization">Specialization</label>
        <input type="text" id="specialization" name="specialization" placeholder="e.g. General Physician" value="<?= h($_POST['specialization'] ?? '') ?>">
      </div>

      <div class="field">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required minlength="8">
        <?php if (!empty($errors['password'])): ?><div class="error-text"><?= h($errors['password']) ?></div><?php endif; ?>
      </div>

      <div class="field">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
        <?php if (!empty($errors['confirm_password'])): ?><div class="error-text"><?= h($errors['confirm_password']) ?></div><?php endif; ?>
      </div>

      <button type="submit" class="btn" style="width:100%;">Create Account</button>
    </form>

    <p style="margin-top:18px;font-size:14px;text-align:center;">
      Already have an account? <a href="index.php?page=login">Log in</a>
    </p>
  </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
