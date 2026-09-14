<!DOCTYPE html>
<html>

<head>

    <title>Hospital Management - Admin Dashboard</title>

    <link rel="stylesheet" href="/hospital_management/public/assets/css/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

</head>

<body>

    <div class="admin-layout">

        <!-- SIDEBAR -->
        <aside class="sidebar">

            <h2>Hospital</h2>

            <p class="admin-label">ADMIN PANEL</p>

            <a href="#">Dashboard</a>
            <a href="/hospital_management/public/doctors.php">
                Doctors
            </a>
            <a href="/hospital_management/public/revenue.php">
                Revenue
            </a>
            <a href="/hospital_management/public/reviews.php">
                Reviews
            </a>

            <a href="/hospital_management/public/logout.php" class="logout-link">
                Logout
            </a>

        </aside>


        <!-- MAIN CONTENT -->
        <main class="main-content">

            <div class="top-section">

                <div>
                    <h1>Admin Dashboard</h1>
                    <p>Hospital Management System</p>
                </div>

                <div class="admin-user">
                    Admin
                </div>

            </div>


            <!-- DASHBOARD CARDS -->

            <div class="dashboard-cards">

                <div class="dashboard-card">

                    <h3>Total Doctors</h3>

                    <p id="totalDoctors">
                        0
                    </p>

                </div>


                <div class="dashboard-card">

                    <h3>Total Patients</h3>

                    <p id="totalPatients">
                        0
                    </p>

                </div>


                <div class="dashboard-card">

                    <h3>Total Appointments</h3>

                    <p id="totalAppointments">
                        0
                    </p>

                </div>


                <div class="dashboard-card">

                    <h3>Total Reviews</h3>

                    <p id="totalReviews">
                        0
                    </p>

                </div>


                <div class="dashboard-card">

                    <h3>Total Revenue</h3>

                    <p id="totalRevenue">
                        ৳0
                    </p>

                </div>

            </div>


            <!-- RECENT REVIEWS -->

            <div class="dashboard-section">

                <div class="section-header">

                    <h2>Recent Reviews</h2>

                    <a href="#">
                        View All
                    </a>

                </div>


                <div class="review-box">

                    <div class="review-header">

                        <strong>Patient Name</strong>

                        <span>★★★★★</span>

                    </div>

                    <p>
                        Very good service and helpful doctor.
                    </p>

                    <small>
                        Today
                    </small>

                </div>


                <div class="review-box">

                    <div class="review-header">

                        <strong>Patient Name</strong>

                        <span>★★★★☆</span>

                    </div>

                    <p>
                        Overall experience was good.
                    </p>

                    <small>
                        Yesterday
                    </small>

                </div>

            </div>


    <script src="/hospital_management/public/assets/js/app.js"></script>

</body>

</html>
