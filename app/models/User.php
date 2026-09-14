<?php

class User
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    public function createUser(
        $name,
        $email,
        $password,
        $role = "patient"
    )
    {
        $sql = "INSERT INTO users
        (name, email, password, role)
        VALUES (?, ?, ?, ?)";

        $stmt = mysqli_prepare($this->conn, $sql);

        if (!$stmt) {
            return mysqli_error($this->conn);
        }

        mysqli_stmt_bind_param(
            $stmt,
            "ssss",
            $name,
            $email,
            $password,
            $role
        );

        if (mysqli_stmt_execute($stmt)) {

            return true;

        }

        return mysqli_stmt_error($stmt);
    }


    public function findByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = ?";

        $stmt = mysqli_prepare($this->conn, $sql);

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

        return mysqli_fetch_assoc($result);
    }
}

?>
