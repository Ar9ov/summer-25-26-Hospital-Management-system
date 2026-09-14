<?php
/**
 * Patient model.
 *
 * NOTE: This used to write to a standalone `patients` table with a
 * plaintext `password` column in its own database (`hospital_db`). That
 * table name collided with the Doctor panel's `patients` table (which
 * means something different there -- a medical record, not a login) and
 * stored passwords in plain text. It now uses the shared `users` table
 * (role = 'patient') with hashed passwords, matching how the Admin and
 * Doctor panels handle accounts, so a patient can log in from any panel.
 */

function registerPatient(
    $conn,
    $name,
    $email,
    $mobile,
    $password
) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql =
    "INSERT INTO users
    (
        name,
        email,
        phone,
        password,
        role
    )
    VALUES
    (?,?,?,?,'patient')";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param(
        $stmt,
        "ssss",
        $name,
        $email,
        $mobile,
        $hashedPassword
    );

    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $ok;
}

function emailExists($conn, $email)
{
    $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    return $row !== null;
}

function loginPatient(
    $conn,
    $email,
    $password
) {
    $sql =
    "SELECT *
    FROM users
    WHERE email = ?
    AND role = 'patient'
    LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $email
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $user = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    if (!$user) {
        return false;
    }

    if (!password_verify($password, $user["password"])) {
        return false;
    }

    if (($user["status"] ?? "active") !== "active") {
        return false;
    }

    return $user;
}

?>
