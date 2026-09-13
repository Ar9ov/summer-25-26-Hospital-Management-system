<?php
/**
 * Leave model -- Unique Doctor Feature #1: Emergency Leave.
 */

function leave_create($conn, $doctorId, $data)
{
    $stmt = mysqli_prepare(
        $conn,
        'INSERT INTO doctor_leaves (doctor_id, leave_from, leave_to, reason) VALUES (?, ?, ?, ?)'
    );
    mysqli_stmt_bind_param($stmt, 'isss', $doctorId, $data['leave_from'], $data['leave_to'], $data['reason']);
    $ok = mysqli_stmt_execute($stmt);
    $newId = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);
    return $ok ? $newId : false;
}

function leave_list_by_doctor($conn, $doctorId)
{
    $stmt = mysqli_prepare(
        $conn,
        'SELECT * FROM doctor_leaves WHERE doctor_id = ? ORDER BY created_at DESC'
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

/** A doctor may only cancel their own request, and only while it's still pending. */
function leave_cancel($conn, $leaveId, $doctorId)
{
    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM doctor_leaves WHERE id = ? AND doctor_id = ? AND status = 'pending'"
    );
    mysqli_stmt_bind_param($stmt, 'ii', $leaveId, $doctorId);
    $ok = mysqli_stmt_execute($stmt);
    $affected = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    return $ok && $affected > 0;
}
