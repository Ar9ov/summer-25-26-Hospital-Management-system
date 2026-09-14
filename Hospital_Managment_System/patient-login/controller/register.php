<?php

require_once("../model/db.php");
require_once("../model/PatientModel.php");

$message="";

if($_SERVER["REQUEST_METHOD"]=="POST")
{
    $name = trim($_POST["name"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $mobile = trim($_POST["mobile"] ?? '');
    $password = $_POST["password"] ?? '';

    if ($name === '' || $email === '' || $password === '') {
        $message = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters.";
    } elseif (emailExists($conn, $email)) {
        $message = "An account with this email already exists.";
    } else {
        $ok = registerPatient($conn, $name, $email, $mobile, $password);
        $message = $ok ? "Registration Successful. You can now log in." : "Something went wrong. Please try again.";
    }
}

include("../view/register.php");

?>