<?php
/**
 * History model -- Unique Doctor Feature #3: Patient History.
 * Lets a doctor log a free-form visit note (no prescription needed) and
 * combines those notes with past prescriptions into one timeline.
 */

function visit_note_create($conn, $doctorId, $data)
{
    $stmt = mysqli_prepare(
        $conn,
        'INSERT INTO patient_visit_notes (patient_id, doctor_id, visit_date, symptoms, treatment_notes) VALUES (?, ?, ?, ?, ?)'
    );
    $patientId = (int) $data['patient_id'];
    mysqli_stmt_bind_param(
        $stmt,
        'iisss',
        $patientId,
        $doctorId,
        $data['visit_date'],
        $data['symptoms'],
        $data['treatment_notes']
    );
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function visit_notes_by_patient($conn, $patientId)
{
    $stmt = mysqli_prepare(
        $conn,
        'SELECT v.*, u.name AS doctor_name
         FROM patient_visit_notes v
         JOIN users u ON u.id = v.doctor_id
         WHERE v.patient_id = ?
         ORDER BY v.visit_date ASC, v.id ASC'
    );
    mysqli_stmt_bind_param($stmt, 'i', $patientId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $rows;
}

/**
 * Merge prescriptions + visit notes into one chronological timeline for a
 * patient, optionally filtered by a keyword (matched against diagnosis /
 * symptoms / notes). Used by the Patient History page and its AJAX search.
 */
function patient_history_timeline($conn, $patientId, $keyword = '')
{
    $prescriptions = prescription_list_by_patient($conn, $patientId);
    $notes = visit_notes_by_patient($conn, $patientId);

    $timeline = [];
    foreach ($prescriptions as $p) {
        $timeline[] = [
            'type' => 'prescription',
            'date' => $p['visit_date'],
            'doctor_name' => $p['doctor_name'],
            'summary' => $p['diagnosis'],
            'details' => $p['notes'],
            'medicines' => $p['medicines'],
        ];
    }
    foreach ($notes as $n) {
        $timeline[] = [
            'type' => 'visit_note',
            'date' => $n['visit_date'],
            'doctor_name' => $n['doctor_name'],
            'summary' => $n['symptoms'],
            'details' => $n['treatment_notes'],
            'medicines' => [],
        ];
    }

    // Sort combined timeline by date.
    usort($timeline, function ($a, $b) {
        return strtotime($a['date']) <=> strtotime($b['date']);
    });

    if ($keyword !== '') {
        $keyword = mb_strtolower($keyword);
        $timeline = array_values(array_filter($timeline, function ($entry) use ($keyword) {
            $haystack = mb_strtolower($entry['summary'] . ' ' . $entry['details']);
            return mb_strpos($haystack, $keyword) !== false;
        }));
    }

    return $timeline;
}
