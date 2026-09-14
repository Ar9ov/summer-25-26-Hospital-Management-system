<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Login - Hospital Patient System</title>
<link rel="stylesheet" href="../assets/style.css">
</head>

<body>

<div class="container auth">

<div class="card">

<h2>Login</h2>

<?php if (!empty($loginError)): ?>
<div class="message" style="color:#b00020;"><?php echo htmlspecialchars($loginError); ?></div>
<?php endif; ?>

<form method="post" id="loginForm" novalidate>

<div class="field">
<label for="email">Email</label>
<input
type="email"
id="email"
name="email"
placeholder="you@example.com">
</div>

<div class="field">
<label for="password">Password</label>
<input
type="password"
id="password"
name="password"
placeholder="Enter your password">
</div>

<input
type="submit"
value="Login">

</form>

<div class="form-footer">
Don't have an account? <a href="register.php">Create New Account</a>
</div>

</div>

</div>

<script src="../assets/script.js"></script>

</body>

</html>
