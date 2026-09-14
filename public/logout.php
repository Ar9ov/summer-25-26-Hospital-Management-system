<?php

require_once "../app/auth/Auth.php";

Auth::logout();

header("Location: /hospital_management/public/login.php");
exit;

?>
