<?php

require_once "../app/auth/Auth.php";

Auth::requireRole("admin");

require_once "../app/views/admin/reviews.php";

?>
