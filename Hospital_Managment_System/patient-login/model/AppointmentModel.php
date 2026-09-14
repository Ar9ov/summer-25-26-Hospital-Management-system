<?php

function bookAppointment(
$conn,
$patientId,
$doctorId,
$date
)
{
    $sql =
    "INSERT INTO appointments
    (
        patient_id,
        doctor_id,
        appointment_date,
        status
    )
    VALUES
    (?,?,?,'Pending')";

    $stmt =
    mysqli_prepare(
    $conn,
    $sql
    );

    mysqli_stmt_bind_param(
    $stmt,
    "iis",
    $patientId,
    $doctorId,
    $date
    );

    return mysqli_stmt_execute(
    $stmt
    );
}

function getAppointments(
$conn,
$patientId
)
{
    $sql =
    "SELECT
    appointments.*,
    users.name AS full_name
    FROM appointments
    JOIN doctors
    ON appointments.doctor_id =
    doctors.id
    JOIN users
    ON doctors.user_id = users.id
    WHERE patient_id=?";

    $stmt =
    mysqli_prepare(
    $conn,
    $sql
    );

    mysqli_stmt_bind_param(
    $stmt,
    "i",
    $patientId
    );

    mysqli_stmt_execute(
    $stmt
    );

    $result =
    mysqli_stmt_get_result(
    $stmt
    );

    $appointments =
    array();

    while(
    $row =
    mysqli_fetch_assoc(
    $result
    ))
    {
        $appointments[] =
        $row;
    }

    return $appointments;
}

?>