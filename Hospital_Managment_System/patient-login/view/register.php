<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Register - Hospital Patient System</title>
<link rel="stylesheet" href="../assets/style.css">
</head>

<body>

<div class="container auth">

<div class="card">

<h2>Patient Registration</h2>

<form method="post" id="registerForm" novalidate>

<div class="field">
<label for="name">Full Name</label>
<input
type="text"
id="name"
name="name"
placeholder="Your full name">
</div>

<div class="field">
<label for="email">Email</label>
<input
type="email"
id="email"
name="email"
placeholder="you@example.com">
</div>

<div class="field">
<label for="mobile">Mobile</label>
<input
type="text"
id="mobile"
name="mobile"
placeholder="01XXXXXXXXX">
</div>

<div class="field">
<label for="password">Password</label>
<input
type="password"
id="password"
name="password"
placeholder="Create a password">
</div>

<input
type="submit"
value="Register">

</form>

<?php if(!empty($message)){ ?>
<div class="message">
<?php echo $message; ?>
</div>
<?php } ?>

<div class="form-footer">
Already have an account? <a href="login.php">Login</a>
</div>

</div>

</div>

<script src="../assets/script.js"></script>

</body>
</html>
