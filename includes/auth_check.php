<?php
/**
 * Auth guard: include this at the top of every doctor-only page.
 * Requires a session started with role = 'doctor' (see AuthController).
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user_id']) || empty($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
    set_flash('error', 'Please log in as a doctor to continue.');
    redirect('index.php?page=login');
}

// Convenience variables available to any file that includes this guard.
$currentDoctorId = (int) $_SESSION['user_id'];
$currentDoctorName = $_SESSION['full_name'] ?? 'Doctor';
