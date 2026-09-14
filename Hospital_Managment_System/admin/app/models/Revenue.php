<?php

class Revenue
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    // =========================================
    // CREATE
    // =========================================

    public function addRevenue($amount, $description)
    {
        $sql = "INSERT INTO revenue (amount, description)
        VALUES (?, ?)";

        $stmt = mysqli_prepare(
            $this->conn,
            $sql
        );

        if (!$stmt) {
            return mysqli_error($this->conn);
        }

        mysqli_stmt_bind_param(
            $stmt,
            "ds",
            $amount,
            $description
        );

        if (mysqli_stmt_execute($stmt)) {
            return true;
        }

        return mysqli_stmt_error($stmt);
    }


    // =========================================
    // READ + SEARCH
    // =========================================

    public function getAllRevenue($search = "")
    {
        $search = trim($search);


        if ($search !== "") {

            $sql = "SELECT
            id,
            amount,
            description,
            created_at
            FROM revenue
            WHERE description LIKE ?
            OR CAST(amount AS CHAR) LIKE ?
            ORDER BY created_at DESC";

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
                "ss",
                $searchValue,
                $searchValue
            );

            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

        } else {

            $sql = "SELECT
            id,
            amount,
            description,
            created_at
            FROM revenue
            ORDER BY created_at DESC";

            $result = mysqli_query(
                $this->conn,
                $sql
            );
        }


        if (!$result) {
            return [];
        }


        $revenues = [];

        while ($row = mysqli_fetch_assoc($result)) {

            $revenues[] = $row;

        }

        return $revenues;
    }


    // =========================================
    // READ ONE
    // =========================================

    public function getRevenueById($revenueId)
    {
        $sql = "SELECT
        id,
        amount,
        description,
        created_at
        FROM revenue
        WHERE id = ?";

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
            $revenueId
        );

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        return mysqli_fetch_assoc($result);
    }


    // =========================================
    // UPDATE
    // =========================================

    public function updateRevenue(
        $revenueId,
        $amount,
        $description
    )
    {
        $sql = "UPDATE revenue
        SET amount = ?,
        description = ?
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
            "dsi",
            $amount,
            $description,
            $revenueId
        );

        if (mysqli_stmt_execute($stmt)) {
            return true;
        }

        return mysqli_stmt_error($stmt);
    }


    // =========================================
    // DELETE
    // =========================================

    public function deleteRevenue($revenueId)
    {
        $sql = "DELETE FROM revenue
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
            $revenueId
        );

        if (mysqli_stmt_execute($stmt)) {

            if (mysqli_stmt_affected_rows($stmt) > 0) {
                return true;
            }

            return "Revenue record not found.";
        }

        return mysqli_stmt_error($stmt);
    }


    // =========================================
    // DASHBOARD STATISTICS
    // =========================================

    public function getTotalRevenue()
    {
        $sql = "SELECT SUM(amount) AS total
        FROM revenue";

        $result = mysqli_query(
            $this->conn,
            $sql
        );

        if (!$result) {
            return 0;
        }

        $row = mysqli_fetch_assoc($result);

        return $row["total"] ?? 0;
    }


    public function getTodayRevenue()
    {
        $sql = "SELECT SUM(amount) AS total
        FROM revenue
        WHERE DATE(created_at) = CURDATE()";

        $result = mysqli_query(
            $this->conn,
            $sql
        );

        if (!$result) {
            return 0;
        }

        $row = mysqli_fetch_assoc($result);

        return $row["total"] ?? 0;
    }


    public function getMonthRevenue()
    {
        $sql = "SELECT SUM(amount) AS total
        FROM revenue
        WHERE YEAR(created_at) = YEAR(CURDATE())
        AND MONTH(created_at) = MONTH(CURDATE())";

        $result = mysqli_query(
            $this->conn,
            $sql
        );

        if (!$result) {
            return 0;
        }

        $row = mysqli_fetch_assoc($result);

        return $row["total"] ?? 0;
    }


    public function getRecentRevenue()
    {
        $sql = "SELECT
        amount,
        description,
        created_at
        FROM revenue
        ORDER BY created_at DESC
        LIMIT 10";

        $result = mysqli_query(
            $this->conn,
            $sql
        );

        if (!$result) {
            return [];
        }

        $transactions = [];

        while ($row = mysqli_fetch_assoc($result)) {

            $transactions[] = $row;

        }

        return $transactions;
    }
}

?>
