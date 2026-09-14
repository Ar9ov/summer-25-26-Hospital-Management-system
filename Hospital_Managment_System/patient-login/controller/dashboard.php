<?php

session_start();

require_once("../model/db.php");
require_once("../model/DoctorModel.php");

if(
empty($_SESSION["user_id"]) ||
($_SESSION["role"] ?? '') !== 'patient'
)
{
    header(
    "Location: login.php"
    );
    exit;
}

$doctors =
getDoctors(
$conn
);

include(
"../view/dashboard.php"
);

?>