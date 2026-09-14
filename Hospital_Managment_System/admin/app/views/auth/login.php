<!DOCTYPE html>
<html>

<head>

<title>Hospital Management - Login</title>

<link rel="stylesheet"
href="./assets/css/style.css">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap"
rel="stylesheet">

</head>

<body>

<div class="signup-container">

<h1>Login</h1>

<form id="loginForm">

<input type="hidden" name="action" value="login">

<label>Email</label>
<input
type="email"
name="email"
id="loginEmail"
required
>

<label>Password</label>
<input
type="password"
name="password"
id="loginPassword"
required
>

<button type="submit">Login</button>

</form>

<p id="loginMessage"></p>

<p>
Don't have an account?
<a href="./signup.php">
Create New Account
</a>
</p>

</div>

<script src="./assets/js/app.js"></script>

</body>

</html>
