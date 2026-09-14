<!DOCTYPE html>
<html>

<head>

    <title>Hospital Management - Revenue</title>

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


    <!-- SIDEBAR -->

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


    <!-- MAIN CONTENT -->

    <main class="main-content">


        <!-- HEADER -->

        <div class="top-section">

            <div>

                <h1>
                    Revenue Management
                </h1>

                <p>
                    Manage hospital revenue and transactions
                </p>

            </div>

        </div>


        <!-- SUMMARY CARDS -->

        <div class="dashboard-cards">

            <div class="dashboard-card">

                <h3>
                    Total Revenue
                </h3>

                <p id="totalRevenuePage">
                    ৳0
                </p>

            </div>


            <div class="dashboard-card">

                <h3>
                    Today's Revenue
                </h3>

                <p id="todayRevenue">
                    ৳0
                </p>

            </div>


            <div class="dashboard-card">

                <h3>
                    This Month
                </h3>

                <p id="monthRevenue">
                    ৳0
                </p>

            </div>

        </div>


        <!-- ADD REVENUE -->

        <div class="dashboard-section">

            <div class="section-header">

                <div>

                    <h2>
                        Add Revenue
                    </h2>

                    <p class="section-description">
                        Create a new revenue record
                    </p>

                </div>

            </div>


            <form id="revenueForm">

                <input
                    type="hidden"
                    name="action"
                    value="addRevenue"
                >


                <label>
                    Amount
                </label>

                <input
                    type="number"
                    name="amount"
                    id="revenueAmount"
                    step="0.01"
                    min="0.01"
                    required
                >


                <label>
                    Description
                </label>

                <input
                    type="text"
                    name="description"
                    id="revenueDescription"
                    placeholder="Example: Consultation"
                    required
                >


                <button
                    type="submit"
                    class="add-doctor-button"
                >
                    Add Revenue
                </button>

            </form>


            <p id="revenueMessage"></p>

        </div>


        <!-- EDIT REVENUE -->

        <div
            id="editRevenueSection"
            class="dashboard-section"
            style="display: none;"
        >

            <div class="section-header">

                <div>

                    <h2>
                        Edit Revenue
                    </h2>

                    <p class="section-description">
                        Update revenue information
                    </p>

                </div>

            </div>


            <form id="editRevenueForm">

                <input
                    type="hidden"
                    name="action"
                    value="updateRevenue"
                >

                <input
                    type="hidden"
                    name="revenue_id"
                    id="editRevenueId"
                >


                <label>
                    Amount
                </label>

                <input
                    type="number"
                    name="amount"
                    id="editRevenueAmount"
                    step="0.01"
                    min="0.01"
                    required
                >


                <label>
                    Description
                </label>

                <input
                    type="text"
                    name="description"
                    id="editRevenueDescription"
                    required
                >


                <button
                    type="submit"
                    class="add-doctor-button"
                >
                    Update Revenue
                </button>


                <button
                    type="button"
                    id="cancelEditRevenue"
                    class="cancel-button"
                >
                    Cancel
                </button>

            </form>


            <p id="editRevenueMessage"></p>

        </div>


        <!-- REVENUE RECORDS -->

        <div class="dashboard-section">

            <div class="doctor-list-header">

                <div>

                    <h2>
                        Revenue Records
                    </h2>

                    <p class="section-description">
                        Search and manage transactions
                    </p>

                </div>


                <input
                    type="text"
                    id="revenueSearch"
                    placeholder="Search description..."
                >

            </div>


            <div class="table-wrapper">

                <table id="revenueTable">

                    <thead>

                        <tr>

                            <th>
                                ID
                            </th>

                            <th>
                                Description
                            </th>

                            <th>
                                Amount
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody id="revenueTableBody">

                    </tbody>

                </table>

            </div>

        </div>


    </main>

</div>


<script src="./assets/js/app.js"></script>

</body>

</html>
