<?php
/**
 * Front controller (MVC "Controller" entry point).
 * All requests flow through here: index.php?page=<route>
 */

session_start();

require __DIR__ . '/config/db.php';
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/models/UserModel.php';
require __DIR__ . '/models/PatientModel.php';
require __DIR__ . '/models/PrescriptionModel.php';
require __DIR__ . '/models/LeaveModel.php';
require __DIR__ . '/models/HistoryModel.php';
require __DIR__ . '/controllers/AuthController.php';
require __DIR__ . '/controllers/PatientController.php';
require __DIR__ . '/controllers/PrescriptionController.php';
require __DIR__ . '/controllers/LeaveController.php';
require __DIR__ . '/controllers/HistoryController.php';

$page = $_GET['page'] ?? 'login';
$doctorId = $_SESSION['user_id'] ?? null;

// Routes that do NOT require an authenticated doctor session.
$publicRoutes = ['login', 'signup', 'logout'];

if (!in_array($page, $publicRoutes, true)) {
    require __DIR__ . '/includes/auth_check.php'; // redirects away if not logged in as doctor
}

switch ($page) {
    case 'login':
        handle_login($conn);
        break;

    case 'signup':
        handle_signup($conn);
        break;

    case 'logout':
        handle_logout();
        break;

    case 'doctor_dashboard':
        require __DIR__ . '/views/doctor/dashboard.php';
        break;

    // ---- Patients: Create / Read / Update / Delete / Search ----
    case 'patients':
        handle_patients_list($conn, $currentDoctorId);
        break;

    case 'patient_form':
        handle_patient_form($conn, $currentDoctorId);
        break;

    case 'patient_delete':
        handle_patient_delete($conn, $currentDoctorId);
        break;

    // ---- Feature 1: Emergency Leave ----
    case 'emergency_leave':
        handle_emergency_leave($conn, $currentDoctorId);
        break;

    case 'leave_cancel':
        handle_leave_cancel($conn, $currentDoctorId);
        break;

    // ---- Feature 2: Prescription Writer ----
    case 'prescription_writer':
        handle_prescription_writer($conn, $currentDoctorId);
        break;

    case 'prescriptions':
        handle_prescriptions_list($conn, $currentDoctorId);
        break;

    case 'prescription_view':
        handle_prescription_view($conn, $currentDoctorId);
        break;

    // ---- Feature 3: Patient History ----
    case 'patient_history':
        handle_patient_history($conn, $currentDoctorId);
        break;

    default:
        http_response_code(404);
        echo 'Page not found.';
}
