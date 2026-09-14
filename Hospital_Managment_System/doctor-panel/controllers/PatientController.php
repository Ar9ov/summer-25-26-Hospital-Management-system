<?php
/**
 * Patient controller -- Create/Read/Update/Delete/Search for the doctor
 * dashboard's core CRUD requirement.
 */

function handle_patients_list($conn, $doctorId)
{
    $keyword = clean_input($_GET['q'] ?? '');
    $patients = patient_search($conn, $doctorId, $keyword);
    require __DIR__ . '/../views/doctor/patients_list.php';
}

function handle_patient_form($conn, $doctorId)
{
    $errors = [];
    $patient = ['id' => '', 'full_name' => '', 'age' => '', 'gender' => '', 'phone' => '', 'address' => '', 'blood_group' => ''];
    $editId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    if ($editId) {
        $existing = patient_find($conn, $editId);
        if ($existing && (int) $existing['created_by'] === $doctorId) {
            $patient = $existing;
        } else {
            set_flash('error', 'Patient not found.');
            redirect('index.php?page=patients');
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!csrf_verify($_POST['csrf_token'] ?? '')) {
            set_flash('error', 'Invalid session token. Please try again.');
            redirect('index.php?page=patients');
        }

        $data = [
            'full_name' => clean_input($_POST['full_name'] ?? ''),
            'age' => clean_input($_POST['age'] ?? ''),
            'gender' => clean_input($_POST['gender'] ?? ''),
            'phone' => clean_input($_POST['phone'] ?? ''),
            'address' => clean_input($_POST['address'] ?? ''),
            'blood_group' => clean_input($_POST['blood_group'] ?? ''),
        ];
        $errors = validate_patient_data($data);
        $patient = array_merge($patient, $data, ['id' => $editId]);

        if (empty($errors)) {
            if ($editId) {
                $ok = patient_update($conn, $editId, $data, $doctorId);
                $message = 'Patient record updated.';
            } else {
                $ok = patient_create($conn, $data, $doctorId);
                $message = 'Patient record created.';
            }

            if ($ok) {
                set_flash('success', $message);
                redirect('index.php?page=patients');
            }
            $errors['general'] = 'Something went wrong. Please try again.';
        }
    }

    require __DIR__ . '/../views/doctor/patient_form.php';
}

function handle_patient_delete($conn, $doctorId)
{
    if (!csrf_verify($_POST['csrf_token'] ?? '')) {
        set_flash('error', 'Invalid session token.');
        redirect('index.php?page=patients');
    }

    $id = (int) ($_POST['id'] ?? 0);
    if (patient_delete($conn, $id, $doctorId)) {
        set_flash('success', 'Patient record deleted.');
    } else {
        set_flash('error', 'Could not delete that record.');
    }
    redirect('index.php?page=patients');
}
