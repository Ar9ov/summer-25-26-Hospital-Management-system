<?php
/**
 * Leave controller -- Unique Doctor Feature #1: Emergency Leave.
 */

function handle_emergency_leave($conn, $doctorId)
{
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!csrf_verify($_POST['csrf_token'] ?? '')) {
            set_flash('error', 'Invalid session token. Please try again.');
            redirect('index.php?page=emergency_leave');
        }

        $data = [
            'leave_from' => clean_input($_POST['leave_from'] ?? ''),
            'leave_to' => clean_input($_POST['leave_to'] ?? ''),
            'reason' => clean_input($_POST['reason'] ?? ''),
        ];
        $errors = validate_leave_data($data);

        if (empty($errors)) {
            $newId = leave_create($conn, $doctorId, $data);
            if ($newId) {
                set_flash('success', 'Emergency leave request submitted.');
                redirect('index.php?page=emergency_leave');
            }
            $errors['general'] = 'Something went wrong. Please try again.';
        }
    }

    $leaves = leave_list_by_doctor($conn, $doctorId);
    require __DIR__ . '/../views/doctor/emergency_leave.php';
}

function handle_leave_cancel($conn, $doctorId)
{
    if (!csrf_verify($_POST['csrf_token'] ?? '')) {
        set_flash('error', 'Invalid session token.');
        redirect('index.php?page=emergency_leave');
    }

    $id = (int) ($_POST['id'] ?? 0);
    if (leave_cancel($conn, $id, $doctorId)) {
        set_flash('success', 'Leave request cancelled.');
    } else {
        set_flash('error', 'Could not cancel that request (it may already be reviewed).');
    }
    redirect('index.php?page=emergency_leave');
}
