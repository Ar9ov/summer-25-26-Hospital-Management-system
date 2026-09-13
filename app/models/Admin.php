<?php

class Admin
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    // Total doctors
    public function getTotalDoctors()
    {
        $sql = "SELECT COUNT(*) AS total FROM doctors";

        $result = mysqli_query($this->conn, $sql);

        if (!$result) {
            return 0;
        }

        $row = mysqli_fetch_assoc($result);

        return $row["total"];
    }


    // Total patients
    public function getTotalPatients()
    {
        $sql = "SELECT COUNT(*) AS total
        FROM users
        WHERE role = 'patient'";

        $result = mysqli_query($this->conn, $sql);

        if (!$result) {
            return 0;
        }

        $row = mysqli_fetch_assoc($result);

        return $row["total"];
    }


    // Total reviews
    public function getTotalReviews()
    {
        $sql = "SELECT COUNT(*) AS total FROM reviews";

        $result = mysqli_query($this->conn, $sql);

        if (!$result) {
            return 0;
        }

        $row = mysqli_fetch_assoc($result);

        return $row["total"];
    }


    // Total revenue
    public function getTotalRevenue()
    {
        $sql = "SELECT SUM(amount) AS total FROM revenue";

        $result = mysqli_query($this->conn, $sql);

        if (!$result) {
            return 0;
        }

        $row = mysqli_fetch_assoc($result);

        return $row["total"] ?? 0;
    }
}

?>
