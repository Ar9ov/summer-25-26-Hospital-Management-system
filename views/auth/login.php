<?php $pageTitle = 'Login'; require __DIR__ . '/../partials/header.php'; ?>

<div class="auth-wrapper">
  <div class="auth-card">
    <h1>Welcome back</h1>
    <p class="subtitle">Log in to access your dashboard.</p>

    <?php if ($flash): ?>
      <div class="flash flash-<?= h($flash['type']) ?>"><?= h($flash['message']) ?></div>
    <?php endif; ?>

    <form method="POST" action="index.php?page=login" novalidate>
      <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">

      <div class="field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
      </div>

      <div class="field">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>

      <button type="submit" class="btn" style="width:100%;">Log In</button>
    </form>

    <p style="margin-top:18px;font-size:14px;text-align:center;">
      Doctor account? <a href="index.php?page=signup">Sign up here</a>
    </p>
  </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
