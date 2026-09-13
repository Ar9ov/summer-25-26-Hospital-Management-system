<?php

class Doctor
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    // =========================================
    // CREATE DOCTOR
    // =========================================

    public function addDoctor(
        $name,
        $email,
        $password,
        $specialization,
        $phone
    )
    {
        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $sqlUser = "INSERT INTO users
        (name, email, password, role)
        VALUES (?, ?, ?, 'doctor')";

        $stmtUser = mysqli_prepare(
            $this->conn,
            $sqlUser
        );

        if (!$stmtUser) {
            return "Could not prepare user query.";
        }

        mysqli_stmt_bind_param(
            $stmtUser,
            "sss",
            $name,
            $email,
            $hashedPassword
        );

        if (!mysqli_stmt_execute($stmtUser)) {
            return mysqli_stmt_error($stmtUser);
        }

        $userId = mysqli_insert_id($this->conn);


        $sqlDoctor = "INSERT INTO doctors
        (user_id, specialization, phone, status)
        VALUES (?, ?, ?, 'active')";

        $stmtDoctor = mysqli_prepare(
            $this->conn,
            $sqlDoctor
        );

        if (!$stmtDoctor) {
            return "Could not prepare doctor query.";
        }

        mysqli_stmt_bind_param(
            $stmtDoctor,
            "iss",
            $userId,
            $specialization,
            $phone
        );

        if (!mysqli_stmt_execute($stmtDoctor)) {
            return mysqli_stmt_error($stmtDoctor);
        }

        return true;
    }


    // =========================================
    // READ / SEARCH DOCTORS
    // =========================================

    public function getAllDoctors($search = "")
    {
        $search = trim($search);

        if ($search !== "") {

            $sql = "SELECT
            doctors.id,
            doctors.user_id,
            doctors.specialization,
            doctors.phone,
            doctors.status,
            users.name,
            users.email

            FROM doctors

            INNER JOIN users
            ON doctors.user_id = users.id

            WHERE users.name LIKE ?
            OR users.email LIKE ?
            OR doctors.specialization LIKE ?

            ORDER BY doctors.id DESC";

            $stmt = mysqli_prepare(
                $this->conn,
                $sql
            );

            if (!$stmt) {
                return [];
            }

            $searchValue = "%" . $search . "%";

            mysqli_stmt_bind_param(
                $stmt,
                "sss",
                $searchValue,
                $searchValue,
                $searchValue
            );

            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

        } else {

            $sql = "SELECT
            doctors.id,
            doctors.user_id,
            doctors.specialization,
            doctors.phone,
            doctors.status,
            users.name,
            users.email

            FROM doctors

            INNER JOIN users
            ON doctors.user_id = users.id

            ORDER BY doctors.id DESC";

            $result = mysqli_query(
                $this->conn,
                $sql
            );
        }


        if (!$result) {
            return [];
        }


        $doctors = [];

        while ($row = mysqli_fetch_assoc($result)) {

            $doctors[] = $row;

        }

        return $doctors;
    }


    // =========================================
    // READ ONE DOCTOR
    // =========================================

    public function getDoctorById($doctorId)
    {
        $sql = "SELECT
        doctors.id,
        doctors.user_id,
        doctors.specialization,
        doctors.phone,
        doctors.status,
        users.name,
        users.email

        FROM doctors

        INNER JOIN users
        ON doctors.user_id = users.id

        WHERE doctors.id = ?";

        $stmt = mysqli_prepare(
            $this->conn,
            $sql
        );

        if (!$stmt) {
            return false;
        }

        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $doctorId
        );

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        return mysqli_fetch_assoc($result);
    }


    // =========================================
    // UPDATE DOCTOR
    // =========================================

    public function updateDoctor(
        $doctorId,
        $name,
        $email,
        $specialization,
        $phone
    )
    {
        $doctor = $this->getDoctorById($doctorId);

        if (!$doctor) {
            return "Doctor not found.";
        }

        $userId = $doctor["user_id"];


        // Update user information
        $sqlUser = "UPDATE users
        SET name = ?, email = ?
        WHERE id = ?";

        $stmtUser = mysqli_prepare(
            $this->conn,
            $sqlUser
        );

        if (!$stmtUser) {
            return "Could not prepare user update.";
        }

        mysqli_stmt_bind_param(
            $stmtUser,
            "ssi",
            $name,
            $email,
            $userId
        );

        if (!mysqli_stmt_execute($stmtUser)) {
            return mysqli_stmt_error($stmtUser);
        }


        // Update doctor information
        $sqlDoctor = "UPDATE doctors
        SET specialization = ?, phone = ?
        WHERE id = ?";

            $stmtDoctor = mysqli_prepare(
                $this->conn,
                $sqlDoctor
            );

            if (!$stmtDoctor) {
                return "Could not prepare doctor update.";
            }

            mysqli_stmt_bind_param(
                $stmtDoctor,
                "ssi",
                $specialization,
                $phone,
                $doctorId
            );

            if (!mysqli_stmt_execute($stmtDoctor)) {
                return mysqli_stmt_error($stmtDoctor);
            }

            return true;
    }


    // =========================================
    // DEACTIVATE DOCTOR
    // =========================================

    public function deactivateDoctor($doctorId)
    {
        $sql = "UPDATE doctors
        SET status = 'inactive'
        WHERE id = ?";

        $stmt = mysqli_prepare(
            $this->conn,
            $sql
        );

        if (!$stmt) {
            return mysqli_error($this->conn);
        }

        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $doctorId
        );

        if (mysqli_stmt_execute($stmt)) {
            return true;
        }

        return mysqli_stmt_error($stmt);
    }


    // =========================================
    // DELETE INACTIVE DOCTOR
    // =========================================

    public function deleteInactiveDoctor($doctorId)
    {
        $doctor = $this->getDoctorById($doctorId);

        if (!$doctor) {
            return "Doctor not found.";
        }

        if ($doctor["status"] !== "inactive") {
            return "Only inactive doctors can be deleted.";
        }


        $sql = "DELETE FROM doctors
        WHERE id = ?
        AND status = 'inactive'";

        $stmt = mysqli_prepare(
            $this->conn,
            $sql
        );

        if (!$stmt) {
            return mysqli_error($this->conn);
        }

        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $doctorId
        );

        if (mysqli_stmt_execute($stmt)) {

            if (mysqli_stmt_affected_rows($stmt) > 0) {
                return true;
            }

            return "Doctor could not be deleted.";
        }

        return mysqli_stmt_error($stmt);
    }
}

?>
