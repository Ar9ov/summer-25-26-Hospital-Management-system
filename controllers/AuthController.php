<?php
/**
 * Auth controller.
 * Login is shared across all roles (redirects by role after success);
 * this module only implements the doctor-facing signup form, since in
 * many hospital systems doctor accounts are created by Admin -- adjust
 * with your team if Admin-only creation is preferred instead.
 */

function handle_login($conn)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!csrf_verify($_POST['csrf_token'] ?? '')) {
            set_flash('error', 'Invalid session token. Please try again.');
            redirect('index.php?page=login');
        }

        $email = clean_input($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            set_flash('error', 'Email and password are required.');
            redirect('index.php?page=login');
        }

        $user = user_find_by_email($conn, $email);

        if (!$user || !password_verify($password, $user['password'])) {
            set_flash('error', 'Invalid email or password.');
            redirect('index.php?page=login');
        }

        if ($user['status'] !== 'active') {
            set_flash('error', 'Your account has been suspended. Contact the administrator.');
            redirect('index.php?page=login');
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['full_name'] = $user['name'];

        if ($user['role'] === 'doctor') {
            redirect('index.php?page=doctor_dashboard');
        }

        // Other roles are handled by their own team members' modules.
        redirect('index.php?page=login');
    }

    require __DIR__ . '/../views/auth/login.php';
}

function handle_signup($conn)
{
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!csrf_verify($_POST['csrf_token'] ?? '')) {
            set_flash('error', 'Invalid session token. Please try again.');
            redirect('index.php?page=signup');
        }

        $fullName = clean_input($_POST['full_name'] ?? '');
        $email = clean_input($_POST['email'] ?? '');
        $phone = clean_input($_POST['phone'] ?? '');
        $specialization = clean_input($_POST['specialization'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (strlen($fullName) < 2) {
            $errors['full_name'] = 'Enter your full name.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Enter a valid email address.';
        }
        if (strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters.';
        }
        if ($password !== $confirmPassword) {
            $errors['confirm_password'] = 'Passwords do not match.';
        }
        if (empty($errors) && user_find_by_email($conn, $email)) {
            $errors['email'] = 'An account with this email already exists.';
        }

        if (empty($errors)) {
            $newId = user_create_doctor($conn, $fullName, $email, $password, $phone, $specialization);
            if ($newId) {
                set_flash('success', 'Account created. Please log in.');
                redirect('index.php?page=login');
            }
            $errors['general'] = 'Something went wrong. Please try again.';
        }
    }

    require __DIR__ . '/../views/auth/signup.php';
}

function handle_logout()
{
    $_SESSION = [];
    session_destroy();
    redirect('index.php?page=login');
}
