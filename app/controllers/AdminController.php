<?php

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/Admin.php";
require_once __DIR__ . "/../models/Doctor.php";
require_once __DIR__ . "/../models/Revenue.php";
require_once __DIR__ . "/../models/Review.php";

class AdminController
{
    // =========================================
    // DASHBOARD COUNTS
    // =========================================

    public function dashboardCounts()
    {
        $adminModel = new Admin($GLOBALS["conn"]);

        $totalDoctors = $adminModel->getTotalDoctors();
        $totalPatients = $adminModel->getTotalPatients();
        $totalReviews = $adminModel->getTotalReviews();
        $totalRevenue = $adminModel->getTotalRevenue();

        echo json_encode([
            "success" => true,
            "totalDoctors" => $totalDoctors,
            "totalPatients" => $totalPatients,
            "totalReviews" => $totalReviews,
            "totalRevenue" => $totalRevenue
        ]);
    }


    // =========================================
    // ADD DOCTOR
    // =========================================

    public function addDoctor()
    {
        $name = trim($_POST["name"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $password = $_POST["password"] ?? "";
        $specialization = trim($_POST["specialization"] ?? "");
        $phone = trim($_POST["phone"] ?? "");

        if (
            $name === "" ||
            $email === "" ||
            $password === "" ||
            $specialization === ""
        ) {
            echo json_encode([
                "success" => false,
                "message" => "Please fill in all required fields."
            ]);

            return;
        }

        $doctorModel = new Doctor($GLOBALS["conn"]);

        $result = $doctorModel->addDoctor(
            $name,
            $email,
            $password,
            $specialization,
            $phone
        );

        if ($result === true) {

            echo json_encode([
                "success" => true,
                "message" => "Doctor added successfully!"
            ]);

        } else {

            echo json_encode([
                "success" => false,
                "message" => $result
            ]);
        }
    }


    // =========================================
    // GET ALL DOCTORS / SEARCH DOCTORS
    // =========================================

    public function getDoctors()
    {
        $search = trim($_GET["search"] ?? "");

        $doctorModel = new Doctor($GLOBALS["conn"]);

        $doctors = $doctorModel->getAllDoctors($search);

        echo json_encode([
            "success" => true,
            "doctors" => $doctors
        ]);
    }


    // =========================================
    // GET ONE DOCTOR
    // =========================================

    public function getDoctor()
    {
        $doctorId = $_GET["id"] ?? 0;

        if (!$doctorId) {

            echo json_encode([
                "success" => false,
                "message" => "Invalid doctor ID."
            ]);

            return;
        }

        $doctorModel = new Doctor($GLOBALS["conn"]);

        $doctor = $doctorModel->getDoctorById($doctorId);

        if ($doctor) {

            echo json_encode([
                "success" => true,
                "doctor" => $doctor
            ]);

        } else {

            echo json_encode([
                "success" => false,
                "message" => "Doctor not found."
            ]);
        }
    }


    // =========================================
    // DEACTIVATE DOCTOR
    // =========================================

    public function deactivateDoctor()
    {
        $doctorId = $_POST["doctor_id"] ?? 0;

        if (!$doctorId) {

            echo json_encode([
                "success" => false,
                "message" => "Invalid doctor ID."
            ]);

            return;
        }

        $doctorModel = new Doctor($GLOBALS["conn"]);

        $result = $doctorModel->deactivateDoctor($doctorId);

        if ($result === true) {

            echo json_encode([
                "success" => true,
                "message" => "Doctor deactivated successfully."
            ]);

        } else {

            echo json_encode([
                "success" => false,
                "message" => $result
            ]);
        }
    }


    // =========================================
    // UPDATE DOCTOR
    // =========================================

    public function updateDoctor()
    {
        $doctorId = $_POST["doctor_id"] ?? 0;

        $name = trim($_POST["name"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $specialization = trim($_POST["specialization"] ?? "");
        $phone = trim($_POST["phone"] ?? "");

        if (
            !$doctorId ||
            $name === "" ||
            $email === "" ||
            $specialization === ""
        ) {

            echo json_encode([
                "success" => false,
                "message" => "Please provide all required fields."
            ]);

            return;
        }

        $doctorModel = new Doctor($GLOBALS["conn"]);

        $result = $doctorModel->updateDoctor(
            $doctorId,
            $name,
            $email,
            $specialization,
            $phone
        );

        if ($result === true) {

            echo json_encode([
                "success" => true,
                "message" => "Doctor updated successfully."
            ]);

        } else {

            echo json_encode([
                "success" => false,
                "message" => $result
            ]);
        }
    }


    // =========================================
    // DELETE INACTIVE DOCTOR
    // =========================================

    public function deleteDoctor()
    {
        $doctorId = $_POST["doctor_id"] ?? 0;

        if (!$doctorId) {

            echo json_encode([
                "success" => false,
                "message" => "Invalid doctor ID."
            ]);

            return;
        }

        $doctorModel = new Doctor($GLOBALS["conn"]);

        $result = $doctorModel->deleteInactiveDoctor($doctorId);

        if ($result === true) {

            echo json_encode([
                "success" => true,
                "message" => "Inactive doctor deleted successfully."
            ]);

        } else {

            echo json_encode([
                "success" => false,
                "message" => $result
            ]);
        }
    }


    // =========================================
    // REVENUE DATA
    // =========================================

    public function revenueData()
    {
        $revenueModel = new Revenue($GLOBALS["conn"]);

        $totalRevenue = $revenueModel->getTotalRevenue();
        $todayRevenue = $revenueModel->getTodayRevenue();
        $monthRevenue = $revenueModel->getMonthRevenue();
        $recentRevenue = $revenueModel->getRecentRevenue();

        echo json_encode([
            "success" => true,
            "totalRevenue" => $totalRevenue,
            "todayRevenue" => $todayRevenue,
            "monthRevenue" => $monthRevenue,
            "recentRevenue" => $recentRevenue
        ]);
    }


    // =========================================
    // REVIEW DATA
    // =========================================

    public function reviewData()
    {
        $reviewModel = new Review($GLOBALS["conn"]);

        $totalReviews = $reviewModel->getTotalReviews();
        $averageRating = $reviewModel->getAverageRating();
        $recentReviews = $reviewModel->getRecentReviews();

        echo json_encode([
            "success" => true,
            "totalReviews" => $totalReviews,
            "averageRating" => $averageRating,
            "recentReviews" => $recentReviews
        ]);
    }
    // =========================================
    // ADD REVENUE
    // =========================================

    public function addRevenue()
    {
        $amount = trim($_POST["amount"] ?? "");
        $description = trim($_POST["description"] ?? "");

        if ($amount === "" || $description === "") {

            echo json_encode([
                "success" => false,
                "message" => "Please provide amount and description."
            ]);

            return;
        }

        if (!is_numeric($amount) || $amount <= 0) {

            echo json_encode([
                "success" => false,
                "message" => "Please enter a valid amount."
            ]);

            return;
        }

        $revenueModel = new Revenue($GLOBALS["conn"]);

        $result = $revenueModel->addRevenue(
            $amount,
            $description
        );

        if ($result === true) {

            echo json_encode([
                "success" => true,
                "message" => "Revenue added successfully."
            ]);

        } else {

            echo json_encode([
                "success" => false,
                "message" => $result
            ]);
        }
    }
    // =========================================
    // GET / SEARCH REVENUE
    // =========================================

    public function getRevenue()
    {
        $search = trim($_GET["search"] ?? "");

        $revenueModel = new Revenue($GLOBALS["conn"]);

        $revenues = $revenueModel->getAllRevenue($search);

        echo json_encode([
            "success" => true,
            "revenues" => $revenues
        ]);
    }
    // =========================================
    // GET ONE REVENUE
    // =========================================

    public function getRevenueById()
    {
        $revenueId = $_GET["id"] ?? 0;

        if (!$revenueId) {

            echo json_encode([
                "success" => false,
                "message" => "Invalid revenue ID."
            ]);

            return;
        }

        $revenueModel = new Revenue($GLOBALS["conn"]);

        $revenue = $revenueModel->getRevenueById(
            $revenueId
        );

        if ($revenue) {

            echo json_encode([
                "success" => true,
                "revenue" => $revenue
            ]);

        } else {

            echo json_encode([
                "success" => false,
                "message" => "Revenue record not found."
            ]);
        }
    }
    // =========================================
    // UPDATE REVENUE
    // =========================================

    public function updateRevenue()
    {
        $revenueId = $_POST["revenue_id"] ?? 0;

        $amount = trim($_POST["amount"] ?? "");
        $description = trim($_POST["description"] ?? "");

        if (
            !$revenueId ||
            $amount === "" ||
            $description === ""
        ) {

            echo json_encode([
                "success" => false,
                "message" => "Please provide all required fields."
            ]);

            return;
        }


        if (!is_numeric($amount) || $amount <= 0) {

            echo json_encode([
                "success" => false,
                "message" => "Please enter a valid amount."
            ]);

            return;
        }


        $revenueModel = new Revenue($GLOBALS["conn"]);

        $result = $revenueModel->updateRevenue(
            $revenueId,
            $amount,
            $description
        );


        if ($result === true) {

            echo json_encode([
                "success" => true,
                "message" => "Revenue updated successfully."
            ]);

        } else {

            echo json_encode([
                "success" => false,
                "message" => $result
            ]);
        }
    }
    // =========================================
    // DELETE REVENUE
    // =========================================

    public function deleteRevenue()
    {
        $revenueId = $_POST["revenue_id"] ?? 0;

        if (!$revenueId) {

            echo json_encode([
                "success" => false,
                "message" => "Invalid revenue ID."
            ]);

            return;
        }


        $revenueModel = new Revenue($GLOBALS["conn"]);

        $result = $revenueModel->deleteRevenue(
            $revenueId
        );


        if ($result === true) {

            echo json_encode([
                "success" => true,
                "message" => "Revenue deleted successfully."
            ]);

        } else {

            echo json_encode([
                "success" => false,
                "message" => $result
            ]);
        }
    }
    // =========================================
    // GET / SEARCH REVIEWS
    // =========================================

    public function getReviews()
    {
        $search = trim($_GET["search"] ?? "");

        $reviewModel = new Review($GLOBALS["conn"]);

        $reviews = $reviewModel->getAllReviews($search);

        echo json_encode([
            "success" => true,
            "reviews" => $reviews
        ]);
    }
    // =========================================
    // GET ONE REVIEW
    // =========================================

    public function getReview()
    {
        $reviewId = $_GET["id"] ?? 0;

        if (!$reviewId) {

            echo json_encode([
                "success" => false,
                "message" => "Invalid review ID."
            ]);

            return;
        }

        $reviewModel = new Review($GLOBALS["conn"]);

        $review = $reviewModel->getReviewById($reviewId);

        if ($review) {

            echo json_encode([
                "success" => true,
                "review" => $review
            ]);

        } else {

            echo json_encode([
                "success" => false,
                "message" => "Review not found."
            ]);
        }
    }
    // =========================================
    // UPDATE REVIEW
    // =========================================

    public function updateReview()
    {
        $reviewId = $_POST["review_id"] ?? 0;

        $rating = $_POST["rating"] ?? 0;

        $comment = trim($_POST["comment"] ?? "");


        if (
            !$reviewId ||
            !$rating ||
            $comment === ""
        ) {

            echo json_encode([
                "success" => false,
                "message" => "Please provide all required fields."
            ]);

            return;
        }


        if ($rating < 1 || $rating > 5) {

            echo json_encode([
                "success" => false,
                "message" => "Rating must be between 1 and 5."
            ]);

            return;
        }


        $reviewModel = new Review($GLOBALS["conn"]);

        $result = $reviewModel->updateReview(
            $reviewId,
            $rating,
            $comment
        );


        if ($result === true) {

            echo json_encode([
                "success" => true,
                "message" => "Review updated successfully."
            ]);

        } else {

            echo json_encode([
                "success" => false,
                "message" => $result
            ]);
        }
    }
    // =========================================
    // DELETE REVIEW
    // =========================================

    public function deleteReview()
    {
        $reviewId = $_POST["review_id"] ?? 0;


        if (!$reviewId) {

            echo json_encode([
                "success" => false,
                "message" => "Invalid review ID."
            ]);

            return;
        }


        $reviewModel = new Review($GLOBALS["conn"]);

        $result = $reviewModel->deleteReview($reviewId);


        if ($result === true) {

            echo json_encode([
                "success" => true,
                "message" => "Review deleted successfully."
            ]);

        } else {

            echo json_encode([
                "success" => false,
                "message" => $result
            ]);
        }
    }
}


// =========================================
// GET REQUESTS
// =========================================

if ($_SERVER["REQUEST_METHOD"] === "GET") {

    $action = $_GET["action"] ?? "";

    $controller = new AdminController();


    if ($action === "dashboardCounts") {

        $controller->dashboardCounts();

    }
    elseif ($action === "getDoctors") {

        $controller->getDoctors();

    }
    elseif ($action === "getDoctor") {

        $controller->getDoctor();

    }
    elseif ($action === "getRevenue") {

        $controller->getRevenue();

    }
    elseif ($action === "getRevenueById") {

        $controller->getRevenueById();

    }
    elseif ($action === "revenueData") {

        $controller->revenueData();

    }
    elseif ($action === "reviewData") {

        $controller->reviewData();

    }
    elseif ($action === "getReviews") {

        $controller->getReviews();

    }
    elseif ($action === "getReview") {

        $controller->getReview();

    }
    else {

        echo json_encode([
            "success" => false,
            "message" => "Invalid action."
        ]);
    }
}


// =========================================
// POST REQUESTS
// =========================================
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $action = $_POST["action"] ?? "";

    $controller = new AdminController();


    if ($action === "addDoctor") {

        $controller->addDoctor();

    }
    elseif ($action === "deactivateDoctor") {

        $controller->deactivateDoctor();

    }
    elseif ($action === "updateDoctor") {

        $controller->updateDoctor();

    }
    elseif ($action === "deleteDoctor") {

        $controller->deleteDoctor();

    }
    elseif ($action === "addRevenue") {

        $controller->addRevenue();

    }
    elseif ($action === "updateRevenue") {

        $controller->updateRevenue();

    }
    elseif ($action === "deleteRevenue") {

        $controller->deleteRevenue();

    }
    elseif ($action === "updateReview") {

        $controller->updateReview();

    }
    elseif ($action === "deleteReview") {

        $controller->deleteReview();

    }
    else {

        echo json_encode([
            "success" => false,
            "message" => "Invalid action."
        ]);

    }

}

?>
