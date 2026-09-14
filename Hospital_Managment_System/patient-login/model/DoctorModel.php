<?php
/**
 * Doctor model.
 *
 * NOTE: This used to read from a standalone `doctors` table with columns
 * (doctor_id, full_name, department, availability) that had nothing to do
 * with the Admin/Doctor panels' shared `doctors` table (id, user_id,
 * specialization, phone, status), which itself needs a JOIN to `users`
 * to get the doctor's name. The query below now targets the real shared
 * schema but aliases the columns back to the names the views
 * (dashboard.php, appointment.php) already use, so those views did not
 * need to change.
 */

function getDoctors($conn)
{
    $doctors = array();

    $sql =
    "SELECT
        doctors.id AS doctor_id,
        users.name AS full_name,
        doctors.specialization AS department,
        doctors.status AS availability,
        doctors.phone
    FROM doctors
    INNER JOIN users ON doctors.user_id = users.id
    WHERE doctors.status = 'active'
    ORDER BY users.name";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        return $doctors;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $doctors[] = $row;
    }

    return $doctors;
}

?>
