<?php
/**
 * Patient model -- implements the required Create/Read/Update/Delete/Search
 * actions on the doctor's dashboard. All queries use prepared statements.
 */

function patient_create($conn, $data, $doctorId)
{
    $stmt = mysqli_prepare(
        $conn,
        'INSERT INTO patients (full_name, age, gender, phone, address, blood_group, created_by) VALUES (?, ?, ?, ?, ?, ?, ?)'
    );
    mysqli_stmt_bind_param(
        $stmt,
        'sissssi', // full_name(s) age(i) gender(s) phone(s) address(s) blood_group(s) doctor_id(i)
        $data['full_name'],
        $data['age'],
        $data['gender'],
        $data['phone'],
        $data['address'],
        $data['blood_group'],
        $doctorId
    );
    $ok = mysqli_stmt_execute($stmt);
    $newId = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);
    return $ok ? $newId : false;
}

function patient_update($conn, $id, $data, $doctorId)
{
    // Doctors may only edit patients they created.
    $stmt = mysqli_prepare(
        $conn,
        'UPDATE patients SET full_name = ?, age = ?, gender = ?, phone = ?, address = ?, blood_group = ?
         WHERE id = ? AND created_by = ?'
    );
    mysqli_stmt_bind_param(
        $stmt,
        'sissssii', // full_name(s) age(i) gender(s) phone(s) address(s) blood_group(s) id(i) doctor_id(i)
        $data['full_name'],
        $data['age'],
        $data['gender'],
        $data['phone'],
        $data['address'],
        $data['blood_group'],
        $id,
        $doctorId
    );
    $ok = mysqli_stmt_execute($stmt);
    $affected = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    return $ok && $affected > 0;
}

function patient_delete($conn, $id, $doctorId)
{
    $stmt = mysqli_prepare($conn, 'DELETE FROM patients WHERE id = ? AND created_by = ?');
    mysqli_stmt_bind_param($stmt, 'ii', $id, $doctorId);
    $ok = mysqli_stmt_execute($stmt);
    $affected = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    return $ok && $affected > 0;
}

function patient_find($conn, $id)
{
    $stmt = mysqli_prepare($conn, 'SELECT * FROM patients WHERE id = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $row ?: null;
}

/** List/search patients created by this doctor. Used by the dashboard and AJAX search. */
function patient_search($conn, $doctorId, $keyword = '')
{
    if ($keyword === '') {
        $stmt = mysqli_prepare(
            $conn,
            'SELECT * FROM patients WHERE created_by = ? ORDER BY created_at DESC'
        );
        mysqli_stmt_bind_param($stmt, 'i', $doctorId);
    } else {
        $like = '%' . $keyword . '%';
        $stmt = mysqli_prepare(
            $conn,
            'SELECT * FROM patients WHERE created_by = ? AND (full_name LIKE ? OR phone LIKE ?) ORDER BY created_at DESC'
        );
        mysqli_stmt_bind_param($stmt, 'iss', $doctorId, $like, $like);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $patients = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $patients[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $patients;
}
