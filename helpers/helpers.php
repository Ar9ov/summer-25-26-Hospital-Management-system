<?php
function clean($value) { return trim((string)$value); }
function json_response($success, $message, $data = []) {
    header('Content-Type: application/json');
    echo json_encode(['success'=>$success, 'message'=>$message, 'data'=>$data]);
    exit;
}
function valid_patient($name, $phone, $age, $gender) {
    return $name !== '' && preg_match('/^[0-9+ -]{6,20}$/', $phone) && filter_var($age, FILTER_VALIDATE_INT) !== false && $age >= 0 && $age <= 120 && in_array($gender, ['Male','Female','Other'], true);
}
?>
