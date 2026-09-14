<?php
/**
 * AJAX endpoint: live patient search. Returns JSON.
 * GET /ajax/search_patients.php?q=keyword
 */

session_start();
header('Content-Type: application/json');

require __DIR__ . '/../config/db.php';
require __DIR__ . '/../includes/functions.php';
require __DIR__ . '/../models/PatientModel.php';

if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'doctor') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not authenticated.']);
    exit;
}

$doctorId = (int) $_SESSION['user_id'];
$keyword = clean_input($_GET['q'] ?? '');

$patients = patient_search($conn, $doctorId, $keyword);

echo json_encode(['success' => true, 'patients' => $patients]);
