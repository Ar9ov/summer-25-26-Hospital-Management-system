<?php

function get_invoices($conn, $search = '')
{
    $search = "%" . $search . "%";

    $sql = "SELECT i.*, p.patient_name
            FROM invoices i
            JOIN reception_patients p
            ON p.id = i.patient_id
            WHERE i.invoice_no LIKE ?
            OR p.patient_name LIKE ?
            ORDER BY i.id DESC";

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

function add_invoice(
    $conn,
    $invoice_no,
    $patient_id,
    $fee
)
{
    $sql = "INSERT INTO invoices
            (
                invoice_no,
                patient_id,
                consultation_fee,
                total_bill
            )
            VALUES
            (?, ?, ?, ?)";

    $stmt = mysqli_prepare(
        $conn,
        $sql
    );

    mysqli_stmt_bind_param(
        $stmt,
        "sidd",
        $invoice_no,
        $patient_id,
        $fee,
        $fee
    );

    return mysqli_stmt_execute(
        $stmt
    );
}

function update_payment(
    $conn,
    $id,
    $status
)
{
    $sql = "UPDATE invoices
            SET payment_status = ?
            WHERE id = ?";

    $stmt = mysqli_prepare(
        $conn,
        $sql
    );

    mysqli_stmt_bind_param(
        $stmt,
        "si",
        $status,
        $id
    );

    return mysqli_stmt_execute(
        $stmt
    );
}

function find_invoice(
    $conn,
    $id
)
{
    $sql = "SELECT i.*,
                   p.patient_name,
                   p.phone
            FROM invoices i
            JOIN reception_patients p
            ON p.id = i.patient_id
            WHERE i.id = ?";

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

    $result =
    mysqli_stmt_get_result(
        $stmt
    );

    return mysqli_fetch_assoc(
        $result
    );
}

?>
