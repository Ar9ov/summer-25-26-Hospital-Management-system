<?php

require_once "../app/auth/Auth.php";

Auth::logout();

header("Location: ./login.php");
exit;

?>
