<?php


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
