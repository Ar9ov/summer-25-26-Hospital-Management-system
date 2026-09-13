<?php
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
