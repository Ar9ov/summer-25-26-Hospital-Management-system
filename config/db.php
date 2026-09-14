<?php
/**
 * Database connection (procedural mysqli).
 * Adjust credentials to match your XAMPP MySQL setup.
 */

$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'hospital_management'; // shared DB -- matches the Admin panel's schema

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if (!$conn) {
    // Never leak DB details to the browser in production.
    error_log('DB connection failed: ' . mysqli_connect_error());
    die('Something went wrong. Please try again later.');
}

mysqli_set_charset($conn, 'utf8mb4');
