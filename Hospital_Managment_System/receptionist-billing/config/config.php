<?php
// Shared database for the whole Hospital Management System.
// All 4 panels (Admin, Doctor, Patient, Receptionist) must point at this
// same database -- see /Hospital_Managment_System/hospital_management.sql
$conn = new mysqli('localhost', 'root', '', 'hospital_management');
if ($conn->connect_error) { die('Database connection failed.'); }
$conn->set_charset('utf8mb4');
?>
