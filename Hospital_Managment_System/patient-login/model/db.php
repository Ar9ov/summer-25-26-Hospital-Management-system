<?php

// Shared database for the whole Hospital Management System.
// All 4 panels (Admin, Doctor, Patient, Receptionist) must point at this
// same database -- see /Hospital_Managment_System/hospital_management.sql
$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "hospital_management"
);

if(!$conn)
{
    die(
        mysqli_connect_error()
    );
}

mysqli_set_charset($conn, 'utf8mb4');

?>