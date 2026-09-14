<?php
/**
 * Shared helper functions used across the Doctor Panel module.
 * These implement the "Basic Web Security" + "PHP Validation" requirements.
 */

/** Clean a single input value: trim, strip tags, escape for safe output. */
function clean_input($value)
{
    $value = trim($value ?? '');
    $value = stripslashes($value);
    $value = strip_tags($value);
    return $value;
}

/** Escape output for safe HTML rendering (prevents stored/reflected XSS). */
function h($value)
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/** Generate (or reuse) a CSRF token for the current session. */
function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/** Verify a submitted CSRF token matches the session token. */
function csrf_verify($token)
{
    return !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token ?? '');
}

/** Redirect helper. */
function redirect($url)
{
    header('Location: ' . $url);
    exit;
}

/** Simple flash-message helper (stored in session, shown once). */
function set_flash($type, $message)
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash()
{
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Server-side validators. Client-side (JS) validation is for UX only --
 * these are the source of truth and must always run before any DB write.
 */
function validate_patient_data($data)
{
    $errors = [];

    if (empty($data['full_name']) || strlen($data['full_name']) < 2) {
        $errors['full_name'] = 'Full name must be at least 2 characters.';
    }
    if (!isset($data['age']) || !is_numeric($data['age']) || $data['age'] < 0 || $data['age'] > 130) {
        $errors['age'] = 'Enter a valid age between 0 and 130.';
    }
    if (empty($data['gender']) || !in_array($data['gender'], ['male', 'female', 'other'], true)) {
        $errors['gender'] = 'Select a valid gender.';
    }
    if (!empty($data['phone']) && !preg_match('/^[0-9+\-\s]{6,20}$/', $data['phone'])) {
        $errors['phone'] = 'Enter a valid phone number.';
    }
    if (!empty($data['blood_group']) && !in_array($data['blood_group'], ['A+','A-','B+','B-','AB+','AB-','O+','O-'], true)) {
        $errors['blood_group'] = 'Select a valid blood group.';
    }

    return $errors;
}

function validate_prescription_data($data)
{
    $errors = [];

    if (empty($data['patient_id']) || !ctype_digit((string) $data['patient_id'])) {
        $errors['patient_id'] = 'Select a valid patient.';
    }
    if (empty($data['diagnosis']) || strlen($data['diagnosis']) < 3) {
        $errors['diagnosis'] = 'Diagnosis must be at least 3 characters.';
    }
    if (empty($data['visit_date']) || !DateTime::createFromFormat('Y-m-d', $data['visit_date'])) {
        $errors['visit_date'] = 'Select a valid visit date.';
    }
    if (empty($data['medicines']) || !is_array($data['medicines']) || count($data['medicines']) === 0) {
        $errors['medicines'] = 'Add at least one medicine.';
    } else {
        foreach ($data['medicines'] as $i => $m) {
            if (empty($m['name']) || empty($m['dosage']) || empty($m['frequency']) || empty($m['duration'])) {
                $errors['medicines'] = 'Fill in all fields for every medicine row.';
                break;
            }
        }
    }

    return $errors;
}

function validate_leave_data($data)
{
    $errors = [];

    if (empty($data['leave_from']) || !DateTime::createFromFormat('Y-m-d', $data['leave_from'])) {
        $errors['leave_from'] = 'Select a valid start date.';
    }
    if (empty($data['leave_to']) || !DateTime::createFromFormat('Y-m-d', $data['leave_to'])) {
        $errors['leave_to'] = 'Select a valid end date.';
    }
    if (empty($errors) && strtotime($data['leave_to']) < strtotime($data['leave_from'])) {
        $errors['leave_to'] = 'End date cannot be before start date.';
    }
    if (empty($data['reason']) || strlen($data['reason']) < 5) {
        $errors['reason'] = 'Please give a brief reason (min 5 characters).';
    }

    return $errors;
}
