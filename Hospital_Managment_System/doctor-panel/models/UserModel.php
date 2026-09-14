<?php
/**
 * User model -- procedural mysqli, prepared statements throughout.
 *
 * NOTE: matches the shared `hospital_management` schema owned by the
 * Admin panel (Member 4): users(id, name, email, password, role, status,
 * created_at) + a separate doctors(id, user_id, specialization, phone,
 * status, joined_at) table. Doctor-specific fields live in `doctors`,
 * not on `users`.
 */

function user_find_by_email($conn, $email)
{
    $stmt = mysqli_prepare($conn, 'SELECT * FROM users WHERE email = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $user ?: null;
}

/**
 * Creates a doctor account: one row in `users` (role = doctor) plus one
 * matching row in `doctors` (specialization/phone/status). Wrapped in a
 * transaction so we never end up with a user and no doctor profile.
 */
function user_create_doctor($conn, $fullName, $email, $plainPassword, $phone, $specialization)
{
    $hash = password_hash($plainPassword, PASSWORD_DEFAULT);
    $role = 'doctor';
    $status = 'active';

    mysqli_begin_transaction($conn);

    try {
        $stmt = mysqli_prepare(
            $conn,
            'INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, ?, ?)'
        );
        mysqli_stmt_bind_param($stmt, 'sssss', $fullName, $email, $hash, $role, $status);
        mysqli_stmt_execute($stmt);
        $newUserId = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);

        $docStmt = mysqli_prepare(
            $conn,
            'INSERT INTO doctors (user_id, specialization, phone, status) VALUES (?, ?, ?, ?)'
        );
        // `specialization` is NOT NULL in the shared schema -- fall back to a placeholder.
        $specialization = $specialization !== '' ? $specialization : 'General';
        mysqli_stmt_bind_param($docStmt, 'isss', $newUserId, $specialization, $phone, $status);
        mysqli_stmt_execute($docStmt);
        mysqli_stmt_close($docStmt);

        mysqli_commit($conn);
        return $newUserId;
    } catch (Throwable $e) {
        mysqli_rollback($conn);
        error_log('Doctor signup failed: ' . $e->getMessage());
        return false;
    }
}
