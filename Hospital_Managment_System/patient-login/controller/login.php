<?php

session_start();

require_once("../model/db.php");
require_once("../model/PatientModel.php");

$loginError = "";

if($_SERVER["REQUEST_METHOD"]=="POST")
{
    $patient =
    loginPatient(
    $conn,
    $_POST["email"] ?? '',
    $_POST["password"] ?? ''
    );

    if($patient)
    {
        // Shared session keys -- same as the Admin and Doctor panels --
        // so a patient logged in from any panel is recognised everywhere.
        $_SESSION["user_id"] = $patient["id"];
        $_SESSION["patient_id"] = $patient["id"]; // kept for older views
        $_SESSION["role"] = "patient";
        $_SESSION["full_name"] = $patient["name"];

        header(
        "Location: dashboard.php"
        );
        exit;
    }

    $loginError = "Invalid email or password.";
}

include("../view/login.php");

?>