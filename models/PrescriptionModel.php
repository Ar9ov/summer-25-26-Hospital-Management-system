<?php
/**
 * Prescription model -- Unique Doctor Feature #2: Prescription Writer.
 * A prescription = one diagnosis record + N medicine rows.
 */

function prescription_create($conn, $doctorId, $data)
{
    mysqli_begin_transaction($conn);

    try {
        $stmt = mysqli_prepare(
            $conn,
            'INSERT INTO prescriptions (patient_id, doctor_id, diagnosis, notes, visit_date) VALUES (?, ?, ?, ?, ?)'
        );
        $patientId = (int) $data['patient_id'];
        mysqli_stmt_bind_param(
            $stmt,
            'iisss',
            $patientId,
            $doctorId,
            $data['diagnosis'],
            $data['notes'],
            $data['visit_date']
        );
        mysqli_stmt_execute($stmt);
        $prescriptionId = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);

        $medStmt = mysqli_prepare(
            $conn,
            'INSERT INTO prescription_medicines (prescription_id, medicine_name, dosage, frequency, duration) VALUES (?, ?, ?, ?, ?)'
        );
        foreach ($data['medicines'] as $medicine) {
            mysqli_stmt_bind_param(
                $medStmt,
                'issss',
                $prescriptionId,
                $medicine['name'],
                $medicine['dosage'],
                $medicine['frequency'],
                $medicine['duration']
            );
            mysqli_stmt_execute($medStmt);
        }
        mysqli_stmt_close($medStmt);

        mysqli_commit($conn);
        return $prescriptionId;
    } catch (Throwable $e) {
        mysqli_rollback($conn);
        error_log('Prescription creation failed: ' . $e->getMessage());
        return false;
    }
}

function prescription_list_by_doctor($conn, $doctorId)
{
    $stmt = mysqli_prepare(
        $conn,
        'SELECT p.*, pt.full_name AS patient_name
         FROM prescriptions p
         JOIN patients pt ON pt.id = p.patient_id
         WHERE p.doctor_id = ?
         ORDER BY p.visit_date DESC, p.id DESC'
    );
    mysqli_stmt_bind_param($stmt, 'i', $doctorId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $rows;
}

function prescription_find_with_medicines($conn, $prescriptionId, $doctorId)
{
    $stmt = mysqli_prepare(
        $conn,
        'SELECT p.*, pt.full_name AS patient_name, pt.age, pt.gender
         FROM prescriptions p
         JOIN patients pt ON pt.id = p.patient_id
         WHERE p.id = ? AND p.doctor_id = ? LIMIT 1'
    );
    mysqli_stmt_bind_param($stmt, 'ii', $prescriptionId, $doctorId);
    mysqli_stmt_execute($stmt);
    $prescription = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    if (!$prescription) {
        return null;
    }

    $medStmt = mysqli_prepare($conn, 'SELECT * FROM prescription_medicines WHERE prescription_id = ?');
    mysqli_stmt_bind_param($medStmt, 'i', $prescriptionId);
    mysqli_stmt_execute($medStmt);
    $medResult = mysqli_stmt_get_result($medStmt);
    $medicines = [];
    while ($row = mysqli_fetch_assoc($medResult)) {
        $medicines[] = $row;
    }
    mysqli_stmt_close($medStmt);

    $prescription['medicines'] = $medicines;
    return $prescription;
}

/** All prescriptions for one patient, oldest first -- feeds Patient History. */
function prescription_list_by_patient($conn, $patientId)
{
    $stmt = mysqli_prepare(
        $conn,
        'SELECT p.*, u.name AS doctor_name
         FROM prescriptions p
         JOIN users u ON u.id = p.doctor_id
         WHERE p.patient_id = ?
         ORDER BY p.visit_date ASC, p.id ASC'
    );
    mysqli_stmt_bind_param($stmt, 'i', $patientId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $medStmt = mysqli_prepare($conn, 'SELECT * FROM prescription_medicines WHERE prescription_id = ?');
        mysqli_stmt_bind_param($medStmt, 'i', $row['id']);
        mysqli_stmt_execute($medStmt);
        $medResult = mysqli_stmt_get_result($medStmt);
        $medicines = [];
        while ($med = mysqli_fetch_assoc($medResult)) {
            $medicines[] = $med;
        }
        mysqli_stmt_close($medStmt);
        $row['medicines'] = $medicines;
        $rows[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $rows;
}
