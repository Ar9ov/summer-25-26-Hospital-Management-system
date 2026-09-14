<?php

class Review
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    // =========================================
    // TOTAL REVIEWS
    // =========================================

    public function getTotalReviews()
    {
        $sql = "SELECT COUNT(*) AS total
        FROM reviews";

        $result = mysqli_query(
            $this->conn,
            $sql
        );

        if (!$result) {
            return 0;
        }

        $row = mysqli_fetch_assoc($result);

        return $row["total"];
    }


    // =========================================
    // AVERAGE RATING
    // =========================================

    public function getAverageRating()
    {
        $sql = "SELECT AVG(rating) AS average
        FROM reviews";

        $result = mysqli_query(
            $this->conn,
            $sql
        );

        if (!$result) {
            return 0;
        }

        $row = mysqli_fetch_assoc($result);

        return round(
            $row["average"] ?? 0,
            1
        );
    }


    // =========================================
    // READ + SEARCH REVIEWS
    // =========================================

    public function getAllReviews($search = "")
    {
        $search = trim($search);


        if ($search !== "") {

            $sql = "SELECT
            reviews.id,
            reviews.user_id,
            reviews.doctor_id,
            reviews.rating,
            reviews.comment,
            reviews.created_at,

            patient_users.name AS patient_name,

            doctor_users.name AS doctor_name

            FROM reviews

            INNER JOIN users AS patient_users
            ON reviews.user_id = patient_users.id

            INNER JOIN doctors
            ON reviews.doctor_id = doctors.id

            INNER JOIN users AS doctor_users
            ON doctors.user_id = doctor_users.id

            WHERE patient_users.name LIKE ?
            OR doctor_users.name LIKE ?
            OR reviews.comment LIKE ?

            ORDER BY reviews.created_at DESC";


            $stmt = mysqli_prepare(
                $this->conn,
                $sql
            );

            if (!$stmt) {
                return [];
            }


            $searchValue =
            "%" . $search . "%";


            mysqli_stmt_bind_param(
                $stmt,
                "sss",
                $searchValue,
                $searchValue,
                $searchValue
            );


            mysqli_stmt_execute($stmt);


            $result =
            mysqli_stmt_get_result($stmt);

        }
        else {

            $sql = "SELECT
            reviews.id,
            reviews.user_id,
            reviews.doctor_id,
            reviews.rating,
            reviews.comment,
            reviews.created_at,

            patient_users.name AS patient_name,

            doctor_users.name AS doctor_name

            FROM reviews

            INNER JOIN users AS patient_users
            ON reviews.user_id = patient_users.id

            INNER JOIN doctors
            ON reviews.doctor_id = doctors.id

            INNER JOIN users AS doctor_users
            ON doctors.user_id = doctor_users.id

            ORDER BY reviews.created_at DESC";


            $result = mysqli_query(
                $this->conn,
                $sql
            );
        }


        if (!$result) {
            return [];
        }


        $reviews = [];


        while ($row = mysqli_fetch_assoc($result)) {

            $reviews[] = $row;

        }


        return $reviews;
    }


    // =========================================
    // READ ONE REVIEW
    // =========================================

    public function getReviewById($reviewId)
    {
        $sql = "SELECT
        reviews.id,
        reviews.user_id,
        reviews.doctor_id,
        reviews.rating,
        reviews.comment,
        reviews.created_at,

        patient_users.name AS patient_name,

        doctor_users.name AS doctor_name

        FROM reviews

        INNER JOIN users AS patient_users
        ON reviews.user_id = patient_users.id

        INNER JOIN doctors
        ON reviews.doctor_id = doctors.id

        INNER JOIN users AS doctor_users
        ON doctors.user_id = doctor_users.id

        WHERE reviews.id = ?";


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
            $reviewId
        );


        mysqli_stmt_execute($stmt);


        $result =
        mysqli_stmt_get_result($stmt);


        return mysqli_fetch_assoc($result);
    }


    // =========================================
    // UPDATE / MODERATE REVIEW
    // =========================================

    public function updateReview(
        $reviewId,
        $rating,
        $comment
    )
    {
        $sql = "UPDATE reviews
        SET rating = ?,
        comment = ?
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
            "isi",
            $rating,
            $comment,
            $reviewId
        );


        if (mysqli_stmt_execute($stmt)) {

            return true;

        }


        return mysqli_stmt_error($stmt);
    }


    // =========================================
    // DELETE REVIEW
    // =========================================

    public function deleteReview($reviewId)
    {
        $sql = "DELETE FROM reviews
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
            $reviewId
        );


        if (mysqli_stmt_execute($stmt)) {

            if (
                mysqli_stmt_affected_rows($stmt) > 0
            ) {

                return true;

            }

            return "Review not found.";
        }


        return mysqli_stmt_error($stmt);
    }
}

?>
