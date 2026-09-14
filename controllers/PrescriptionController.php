<?php
/**
 * Prescription controller -- Unique Doctor Feature #2: Prescription Writer.
 */

function handle_prescription_writer($conn, $doctorId)
{
    $errors = [];
    $patients = patient_search($conn, $doctorId); // populates the patient dropdown

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!csrf_verify($_POST['csrf_token'] ?? '')) {
            set_flash('error', 'Invalid session token. Please try again.');
            redirect('index.php?page=prescription_writer');
        }

        $medicineNames = $_POST['medicine_name'] ?? [];
        $dosages = $_POST['dosage'] ?? [];
        $frequencies = $_POST['frequency'] ?? [];
        $durations = $_POST['duration'] ?? [];

        $medicines = [];
        for ($i = 0; $i < count($medicineNames); $i++) {
            if (trim($medicineNames[$i]) === '') {
                continue; // skip fully empty rows
            }
            $medicines[] = [
                'name' => clean_input($medicineNames[$i]),
                'dosage' => clean_input($dosages[$i] ?? ''),
                'frequency' => clean_input($frequencies[$i] ?? ''),
                'duration' => clean_input($durations[$i] ?? ''),
            ];
        }

        $data = [
            'patient_id' => clean_input($_POST['patient_id'] ?? ''),
            'diagnosis' => clean_input($_POST['diagnosis'] ?? ''),
            'notes' => clean_input($_POST['notes'] ?? ''),
            'visit_date' => clean_input($_POST['visit_date'] ?? ''),
            'medicines' => $medicines,
        ];

        $errors = validate_prescription_data($data);

        if (empty($errors)) {
            $newId = prescription_create($conn, $doctorId, $data);
            if ($newId) {
                set_flash('success', 'Prescription saved.');
                redirect('index.php?page=prescription_view&id=' . $newId);
            }
            $errors['general'] = 'Something went wrong while saving the prescription.';
        }
    }

    require __DIR__ . '/../views/doctor/prescription_writer.php';
}

function handle_prescriptions_list($conn, $doctorId)
{
    $prescriptions = prescription_list_by_doctor($conn, $doctorId);
    require __DIR__ . '/../views/doctor/prescriptions_list.php';
}

function handle_prescription_view($conn, $doctorId)
{
    $id = (int) ($_GET['id'] ?? 0);
    $prescription = prescription_find_with_medicines($conn, $id, $doctorId);

    if (!$prescription) {
        set_flash('error', 'Prescription not found.');
        redirect('index.php?page=prescriptions');
    }

    require __DIR__ . '/../views/doctor/prescription_view.php';
}
