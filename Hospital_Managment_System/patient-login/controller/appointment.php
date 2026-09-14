<?php

session_start();

require_once("../model/db.php");
require_once("../model/DoctorModel.php");
require_once("../model/AppointmentModel.php");

if (empty($_SESSION["user_id"]) || ($_SESSION["role"] ?? '') !== 'patient') {
    header("Location: login.php");
    exit;
}

$doctors =
getDoctors($conn);

if($_SERVER["REQUEST_METHOD"]=="POST")
{
    bookAppointment(
        $conn,
        $_SESSION["user_id"],
        (int) $_POST["doctor_id"],
        $_POST["appointment_date"]
    );
}

$appointments =
getAppointments(
$conn,
$_SESSION["user_id"]
);

include(
"../view/appointment.php"
);

?>