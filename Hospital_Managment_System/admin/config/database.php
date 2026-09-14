<?php

// Shared database for the whole Hospital Management System.
// All 4 panels (Admin, Doctor, Patient, Receptionist) must point at this
// same database -- see /Hospital_Managment_System/hospital_management.sql
$host = "localhost";
$username = "root";
$password = "";
$database = "hospital_management";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');

?>
