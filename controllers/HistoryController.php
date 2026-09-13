<?php
/**
 * History controller -- Unique Doctor Feature #3: Patient History.
 */

function handle_patient_history($conn, $doctorId)
{
    $patients = patient_search($conn, $doctorId);
    $selectedPatientId = (int) ($_GET['patient_id'] ?? 0);
    $keyword = clean_input($_GET['q'] ?? '');
    $timeline = [];
    $selectedPatient = null;

    if ($selectedPatientId) {
        $selectedPatient = patient_find($conn, $selectedPatientId);
        if ($selectedPatient) {
            $timeline = patient_history_timeline($conn, $selectedPatientId, $keyword);
        }
    }

    $errors = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_visit_note'])) {
        if (!csrf_verify($_POST['csrf_token'] ?? '')) {
            set_flash('error', 'Invalid session token. Please try again.');
            redirect('index.php?page=patient_history&patient_id=' . $selectedPatientId);
        }

        $data = [
            'patient_id' => clean_input($_POST['patient_id'] ?? ''),
            'visit_date' => clean_input($_POST['visit_date'] ?? ''),
            'symptoms' => clean_input($_POST['symptoms'] ?? ''),
            'treatment_notes' => clean_input($_POST['treatment_notes'] ?? ''),
        ];

        if (empty($data['visit_date']) || !DateTime::createFromFormat('Y-m-d', $data['visit_date'])) {
            $errors['visit_date'] = 'Select a valid visit date.';
        }
        if (empty($data['symptoms'])) {
            $errors['symptoms'] = 'Describe the symptoms observed.';
        }

        if (empty($errors)) {
            visit_note_create($conn, $doctorId, $data);
            set_flash('success', 'Visit note added to patient history.');
            redirect('index.php?page=patient_history&patient_id=' . $data['patient_id']);
        }
    }

    require __DIR__ . '/../views/doctor/patient_history.php';
}
