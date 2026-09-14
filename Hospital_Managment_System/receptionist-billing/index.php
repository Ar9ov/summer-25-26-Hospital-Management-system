<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// This module previously had no login check at all. It now reuses the
// shared PHP session set by the Admin panel's login page (all 4 panels
// share one MySQL `users` table and, by default, one PHP session across
// sibling folders on the same host).
if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'receptionist') {
    header('Location: ../admin/public/login.php');
    exit;
}

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/helpers/helpers.php';
require_once __DIR__ . '/models/patient_model.php';
require_once __DIR__ . '/models/invoice_model.php';

$patients = get_patients($conn, '');
$invoices = get_invoices($conn, '');

include __DIR__ . '/views/header.php';
include __DIR__ . '/views/home.php';
include __DIR__ . '/views/footer.php';
?>
