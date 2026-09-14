<!DOCTYPE html>
<html>

<head>

    <title>Hospital Management - Reviews</title>

    <link rel="stylesheet"
          href="/hospital_management/public/assets/css/style.css">

    <link rel="preconnect"
          href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
          rel="stylesheet">

</head>

<body>

<div class="admin-layout">


    <!-- =========================================
         SIDEBAR
         ========================================= -->

    <aside class="sidebar">

        <h2>🏥 Hospital</h2>

        <p class="admin-label">
            ADMINISTRATION
        </p>


        <a href="/hospital_management/public/admin.php"
           class="nav-link">
            Dashboard
        </a>


        <a href="/hospital_management/public/doctors.php"
           class="nav-link">
            Doctors
        </a>


        <a href="/hospital_management/public/revenue.php"
           class="nav-link">
            Revenue
        </a>


        <a href="/hospital_management/public/reviews.php"
           class="nav-link">
            Reviews
        </a>


        <a href="/hospital_management/public/logout.php"
           class="logout-link">
            Logout
        </a>

    </aside>



    <!-- =========================================
         MAIN CONTENT
         ========================================= -->

    <main class="main-content">


        <!-- HEADER -->

        <div class="top-section">

            <div>

                <h1>
                    Review Management
                </h1>

                <p>
                    Manage patient feedback and doctor ratings
                </p>

            </div>

        </div>



        <!-- =========================================
             REVIEW SUMMARY
             ========================================= -->

        <div class="dashboard-cards">

            <div class="dashboard-card">

                <h3>
                    Total Reviews
                </h3>

                <p id="reviewTotal">
                    0
                </p>

            </div>


            <div class="dashboard-card">

                <h3>
                    Average Rating
                </h3>

                <p id="averageRating">
                    0.0
                </p>

            </div>

        </div>



        <!-- =========================================
             REVIEW LIST
             ========================================= -->

        <div class="dashboard-section">


            <div class="doctor-list-header">

                <div>

                    <h2>
                        Reviews
                    </h2>

                    <p class="section-description">
                        Search and manage patient reviews
                    </p>

                </div>


                <input
                    type="text"
                    id="reviewSearch"
                    placeholder="Search reviews..."
                >

            </div>



            <div class="table-wrapper">

                <table id="reviewTable">

                    <thead>

                        <tr>

                            <th>
                                ID
                            </th>

                            <th>
                                Patient
                            </th>

                            <th>
                                Doctor
                            </th>

                            <th>
                                Rating
                            </th>

                            <th>
                                Comment
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody id="reviewTableBody">

                    </tbody>

                </table>

            </div>

        </div>



        <!-- =========================================
             EDIT REVIEW
             ========================================= -->

        <div
            id="editReviewSection"
            class="dashboard-section"
            style="display: none;"
        >

            <div class="section-header">

                <div>

                    <h2>
                        Moderate Review
                    </h2>

                    <p class="section-description">
                        Update the rating or comment
                    </p>

                </div>

            </div>


            <form id="editReviewForm">

                <input
                    type="hidden"
                    name="action"
                    value="updateReview"
                >


                <input
                    type="hidden"
                    name="review_id"
                    id="editReviewId"
                >


                <label>
                    Rating
                </label>

                <select
                    name="rating"
                    id="editReviewRating"
                    required
                >

                    <option value="1">
                        1 - Very Poor
                    </option>

                    <option value="2">
                        2 - Poor
                    </option>

                    <option value="3">
                        3 - Average
                    </option>

                    <option value="4">
                        4 - Good
                    </option>

                    <option value="5">
                        5 - Excellent
                    </option>

                </select>


                <label>
                    Comment
                </label>

                <textarea
                    name="comment"
                    id="editReviewComment"
                    rows="5"
                    required
                ></textarea>


                <button
                    type="submit"
                    class="add-doctor-button"
                >
                    Update Review
                </button>


                <button
                    type="button"
                    id="cancelEditReview"
                    class="cancel-button"
                >
                    Cancel
                </button>

            </form>


            <p id="editReviewMessage"></p>

        </div>


    </main>

</div>


<script src="/hospital_management/public/assets/js/app.js"></script>

</body>

</html>
