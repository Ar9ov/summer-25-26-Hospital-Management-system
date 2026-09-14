<!DOCTYPE html>
<html>

<head>

    <title>Hospital Management - Doctors</title>

    <link rel="stylesheet"
          href="./assets/css/style.css">

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


        <a href="./admin.php"
           class="nav-link">
            Dashboard
        </a>


        <a href="./doctors.php"
           class="nav-link">
            Doctors
        </a>


        <a href="./revenue.php"
           class="nav-link">
            Revenue
        </a>


        <a href="./reviews.php"
           class="nav-link">
            Reviews
        </a>


        <a href="./logout.php"
           class="logout-link">
            Logout
        </a>

    </aside>



    <!-- =========================================
         MAIN CONTENT
         ========================================= -->

    <main class="main-content">


        <!-- PAGE HEADER -->

        <div class="top-section">

            <div>

                <h1>
                    Doctor Management
                </h1>

                <p>
                    Add and manage hospital doctors
                </p>

            </div>

        </div>



        <!-- =========================================
             ADD DOCTOR
             ========================================= -->

        <div class="dashboard-section">

            <div class="section-header">

                <div>

                    <h2>
                        Add New Doctor
                    </h2>

                    <p class="section-description">
                        Create a new doctor account
                    </p>

                </div>

            </div>


            <form id="doctorForm">

                <input
                    type="hidden"
                    name="action"
                    value="addDoctor"
                >


                <label>
                    Name
                </label>

                <input
                    type="text"
                    name="name"
                    required
                >


                <label>
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    required
                >


                <label>
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    required
                >


                <label>
                    Specialization
                </label>

                <input
                    type="text"
                    name="specialization"
                    placeholder="Example: Cardiology"
                    required
                >


                <label>
                    Phone
                </label>

                <input
                    type="text"
                    name="phone"
                >


                <button
                    type="submit"
                    class="add-doctor-button"
                >
                    Add Doctor
                </button>

            </form>


            <p id="doctorMessage"></p>

        </div>



        <!-- =========================================
             EDIT DOCTOR
             ========================================= -->

        <div
            id="editDoctorSection"
            class="dashboard-section"
            style="display: none;"
        >

            <div class="section-header">

                <div>

                    <h2>
                        Edit Doctor
                    </h2>

                    <p class="section-description">
                        Update doctor information
                    </p>

                </div>

            </div>


            <form id="editDoctorForm">

                <input
                    type="hidden"
                    name="action"
                    value="updateDoctor"
                >


                <input
                    type="hidden"
                    name="doctor_id"
                    id="editDoctorId"
                >


                <label>
                    Name
                </label>

                <input
                    type="text"
                    name="name"
                    id="editDoctorName"
                    required
                >


                <label>
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    id="editDoctorEmail"
                    required
                >


                <label>
                    Specialization
                </label>

                <input
                    type="text"
                    name="specialization"
                    id="editDoctorSpecialization"
                    required
                >


                <label>
                    Phone
                </label>

                <input
                    type="text"
                    name="phone"
                    id="editDoctorPhone"
                >


                <button
                    type="submit"
                    class="add-doctor-button"
                >
                    Update Doctor
                </button>


                <button
                    type="button"
                    id="cancelEditDoctor"
                    class="cancel-button"
                >
                    Cancel
                </button>

            </form>


            <p id="editDoctorMessage"></p>

        </div>



        <!-- =========================================
             DOCTOR LIST
             ========================================= -->

        <div class="dashboard-section doctor-list-section">


            <div class="doctor-list-header">

                <div>

                    <h2>
                        All Doctors
                    </h2>

                    <p class="section-description">
                        Search and manage registered doctors
                    </p>

                </div>


                <input
                    type="text"
                    id="doctorSearch"
                    placeholder="Search doctor..."
                >

            </div>



            <div class="table-wrapper">

                <table id="doctorTable">

                    <thead>

                        <tr>

                            <th>
                                Name
                            </th>

                            <th>
                                Email
                            </th>

                            <th>
                                Specialization
                            </th>

                            <th>
                                Phone
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody id="doctorTableBody">

                        <!-- JavaScript loads doctors here -->

                    </tbody>

                </table>

            </div>

        </div>


    </main>

</div>



<script src="./assets/js/app.js"></script>

</body>

</html>
