<?php

function get_patients(
    $conn,
    $search = ''
)
{
    $search = "%" . $search . "%";

    $sql = "SELECT *
            FROM reception_patients
            WHERE patient_name LIKE ?
            OR phone LIKE ?
            ORDER BY id DESC";

    $stmt = mysqli_prepare(
        $conn,
        $sql
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ss",
        $search,
        $search
    );

    mysqli_stmt_execute(
        $stmt
    );

    $result =
    mysqli_stmt_get_result(
        $stmt
    );

    return mysqli_fetch_all(
        $result,
        MYSQLI_ASSOC
    );
}

function add_patient(
    $conn,
    $name,
    $phone,
    $age,
    $gender,
    $wheelchair
)
{
    $sql = "INSERT INTO reception_patients
            (
                patient_name,
                phone,
                age,
                gender,
                wheelchair
            )
            VALUES
            (?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare(
        $conn,
        $sql
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ssisi",
        $name,
        $phone,
        $age,
        $gender,
        $wheelchair
    );

    return mysqli_stmt_execute(
        $stmt
    );
}

function update_patient(
    $conn,
    $id,
    $name,
    $phone,
    $age,
    $gender,
    $wheelchair
)
{
    $sql = "UPDATE reception_patients
            SET
                patient_name = ?,
                phone = ?,
                age = ?,
                gender = ?,
                wheelchair = ?
            WHERE id = ?";

    $stmt = mysqli_prepare(
        $conn,
        $sql
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ssisii",
        $name,
        $phone,
        $age,
        $gender,
        $wheelchair,
        $id
    );

    return mysqli_stmt_execute(
        $stmt
    );
}

function delete_patient(
    $conn,
    $id
)
{
    $sql = "DELETE
            FROM reception_patients
            WHERE id = ?";

    $stmt = mysqli_prepare(
        $conn,
        $sql
    );

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $id
    );

    return mysqli_stmt_execute(
        $stmt
    );
}

function find_patient(
    $conn,
    $id
)
{
    $sql = "SELECT *
            FROM reception_patients
            WHERE id = ?";

    $stmt = mysqli_prepare(
        $conn,
        $sql
    );

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $id
    );

    mysqli_stmt_execute(
        $stmt
    );

    $result = mysqli_stmt_get_result(
        $stmt
    );

    return mysqli_fetch_assoc(
        $result
    );
}

?>
