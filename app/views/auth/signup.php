<!DOCTYPE html>
<html>

<head>

<title>Hospital Management - Sign Up</title>

<link rel="stylesheet"
href="/hospital_management/public/assets/css/style.css">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap"
rel="stylesheet">

</head>

<body>

<div class="signup-container">

<h1>Create Account</h1>

<form id="signupForm">

<input type="hidden" name="action" value="signup">

<label>Name</label>
<input type="text" name="name" id="name" required>

<label>Email</label>
<input type="email" name="email" id="email" required>

<label>Password</label>
<input type="password" name="password" id="password" required>

<button type="submit">Create New Account</button>

</form>

<p id="message"></p>

<p>
Already have an account?
<a href="/hospital_management/public/login.php">
Login
</a>
</p>

</div>

<script src="/hospital_management/public/assets/js/app.js"></script>

</body>

</html>
