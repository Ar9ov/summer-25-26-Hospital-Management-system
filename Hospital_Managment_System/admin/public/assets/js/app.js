// ======================================================
// HOSPITAL MANAGEMENT SYSTEM
// Single JavaScript File
// ======================================================


// ======================================================
// SIGNUP
// ======================================================

let signupForm = document.getElementById("signupForm");

if (signupForm) {

    signupForm.addEventListener("submit", function (event) {

        event.preventDefault();

        let formData = new FormData(this);

        fetch("../app/controllers/AuthController.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {

            document.getElementById("message").innerText =
            data.message;

            if (data.success) {

                signupForm.reset();

            }

        })
        .catch(error => {

            console.log("Signup error:", error);

            document.getElementById("message").innerText =
            "Something went wrong.";

        });

    });

}


// ======================================================
// LOGIN
// ======================================================

let loginForm = document.getElementById("loginForm");

if (loginForm) {

    loginForm.addEventListener("submit", function (event) {

        event.preventDefault();

        let formData = new FormData(this);

        fetch("../app/controllers/AuthController.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {

            document.getElementById("loginMessage").innerText =
            data.message;

            if (data.success) {

                // NOTE: Admin, Doctor, Patient and Receptionist are 4
                // separate apps that share one database. Deploy them as
                // sibling folders under the same htdocs root (see the
                // project's SETUP_README.md) so these relative links work:
                //   htdocs/hospital-management/admin/        (this app)
                //   htdocs/hospital-management/doctor-panel/
                //   htdocs/hospital-management/patient-login/
                //   htdocs/hospital-management/receptionist-billing/
                if (data.role === "admin") {

                    window.location.href = "admin.php";

                }
                else if (data.role === "doctor") {

                    window.location.href =
                    "../../doctor-panel/index.php?page=doctor_dashboard";

                }
                else if (data.role === "receptionist") {

                    window.location.href =
                    "../../receptionist-billing/index.php";

                }
                else {

                    window.location.href =
                    "../../patient-login/controller/dashboard.php";

                }

            }

        })
        .catch(error => {

            console.log("Login error:", error);

            document.getElementById("loginMessage").innerText =
            "Something went wrong.";

        });

    });

}


// ======================================================
// ADMIN DASHBOARD
// ======================================================

let totalDoctorsElement =
document.getElementById("totalDoctors");

if (totalDoctorsElement) {

    fetch(
        "../app/controllers/AdminController.php?action=dashboardCounts"
    )
    .then(response => response.json())
    .then(data => {

        if (data.success) {

            document.getElementById("totalDoctors").innerText =
            data.totalDoctors;

            document.getElementById("totalPatients").innerText =
            data.totalPatients;

            document.getElementById("totalReviews").innerText =
            data.totalReviews;

            document.getElementById("totalRevenue").innerText =
            "৳" +
            Number(data.totalRevenue).toLocaleString();

        }

    })
    .catch(error => {

        console.log("Dashboard error:", error);

    });

}


// ======================================================
// DOCTOR LIST + SEARCH
// ======================================================

let doctorTableBody =
document.getElementById("doctorTableBody");

let doctorSearch =
document.getElementById("doctorSearch");


function loadDoctors(search = "")
{
    let url =
    "../app/controllers/AdminController.php?action=getDoctors";


if (search !== "") {

    url +=
    "&search=" +
    encodeURIComponent(search);

}


fetch(url)
.then(response => response.json())
.then(data => {

    if (!data.success) {

        return;

    }


    doctorTableBody.innerHTML = "";


    data.doctors.forEach(function (doctor) {

        let row =
        document.createElement("tr");


        let statusClass =
        doctor.status === "active"
        ? "status-active"
        : "status-inactive";


        let actionHTML = "";


        if (doctor.status === "active") {

            actionHTML = `

            <button
            type="button"
            class="edit-doctor-button"
            data-id="${doctor.id}">
            Edit
            </button>

            <button
            type="button"
            class="remove-doctor-button"
            data-id="${doctor.id}">
            Deactivate
            </button>

            `;

        }
        else {

            actionHTML = `

            <span class="inactive-label">
            Inactive
            </span>

            <button
            type="button"
            class="delete-doctor-button"
            data-id="${doctor.id}">
            Delete
            </button>

            `;

        }


        row.innerHTML = `

        <td>
        ${doctor.name}
        </td>

        <td>
        ${doctor.email}
        </td>

        <td>
        ${doctor.specialization}
        </td>

        <td>
        ${doctor.phone ?? ""}
        </td>

        <td class="${statusClass}">
        ${doctor.status}
        </td>

        <td>
        ${actionHTML}
        </td>

        `;


        doctorTableBody.appendChild(row);

    });

})
.catch(error => {

    console.log(
        "Doctor loading error:",
        error
    );

});

}


// Load doctors when the Doctor page opens

if (doctorTableBody) {

    loadDoctors();

}


// Search doctors

if (doctorSearch) {

    doctorSearch.addEventListener(
        "input",
        function () {

            loadDoctors(
                this.value.trim()
            );

        }
    );

}


// ======================================================
// ADD DOCTOR
// ======================================================

let doctorForm =
document.getElementById("doctorForm");

if (doctorForm) {

    doctorForm.addEventListener(
        "submit",
        function (event) {

            event.preventDefault();

            let formData =
            new FormData(this);


            fetch(
                "../app/controllers/AdminController.php",
                {
                    method: "POST",
                    body: formData
                }
            )
            .then(response => response.json())
            .then(data => {

                document.getElementById(
                    "doctorMessage"
                ).innerText =
                data.message;


                if (data.success) {

                    doctorForm.reset();

                    loadDoctors();

                }

            })
            .catch(error => {

                console.log(
                    "Add doctor error:",
                    error
                );

                document.getElementById(
                    "doctorMessage"
                ).innerText =
                "Something went wrong.";

            });

        }
    );

}


// ======================================================
// GET ONE DOCTOR FOR EDIT
// ======================================================

document.addEventListener(
    "click",
    function (event) {

        if (
            event.target.classList.contains(
                "edit-doctor-button"
            )
        ) {

            let doctorId =
            event.target.getAttribute(
                "data-id"
            );


            fetch(
                "../app/controllers/AdminController.php?action=getDoctor&id="
                + doctorId
            )
            .then(response => response.json())
            .then(data => {

                if (!data.success) {

                    alert(data.message);

                    return;

                }


                let doctor =
                data.doctor;


                document.getElementById(
                    "editDoctorSection"
                ).style.display =
                "block";


    document.getElementById(
        "editDoctorId"
    ).value =
    doctor.id;


    document.getElementById(
        "editDoctorName"
    ).value =
    doctor.name;


    document.getElementById(
        "editDoctorEmail"
    ).value =
    doctor.email;


    document.getElementById(
        "editDoctorSpecialization"
    ).value =
    doctor.specialization;


    document.getElementById(
        "editDoctorPhone"
    ).value =
    doctor.phone ?? "";


    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });

            })
            .catch(error => {

                console.log(
                    "Get doctor error:",
                    error
                );

            });

        }

    }
);


// ======================================================
// UPDATE DOCTOR
// ======================================================

let editDoctorForm =
document.getElementById("editDoctorForm");


if (editDoctorForm) {

    editDoctorForm.addEventListener(
        "submit",
        function (event) {

            event.preventDefault();


            let formData =
            new FormData(this);


            fetch(
                "../app/controllers/AdminController.php",
                {
                    method: "POST",
                    body: formData
                }
            )
            .then(response => response.json())
            .then(data => {

                document.getElementById(
                    "editDoctorMessage"
                ).innerText =
                data.message;


                if (data.success) {

                    loadDoctors();

                    editDoctorForm.reset();


                    document.getElementById(
                        "editDoctorSection"
                    ).style.display =
                    "none";

                }

            })
            .catch(error => {

                console.log(
                    "Update doctor error:",
                    error
                );

            });

        }
    );

}


// ======================================================
// CANCEL EDIT
// ======================================================

let cancelEditDoctor =
document.getElementById("cancelEditDoctor");


if (cancelEditDoctor) {

    cancelEditDoctor.addEventListener(
        "click",
        function () {

            document.getElementById(
                "editDoctorSection"
            ).style.display =
            "none";


        document.getElementById(
            "editDoctorForm"
        ).reset();

        }
    );

}


// ======================================================
// DEACTIVATE DOCTOR
// ======================================================

document.addEventListener(
    "click",
    function (event) {

        if (
            event.target.classList.contains(
                "remove-doctor-button"
            )
        ) {

            let doctorId =
            event.target.getAttribute(
                "data-id"
            );


            let confirmRemove =
            confirm(
                "Are you sure you want to deactivate this doctor?"
            );


            if (!confirmRemove) {

                return;

            }


            let formData =
            new FormData();


            formData.append(
                "action",
                "deactivateDoctor"
            );


            formData.append(
                "doctor_id",
                doctorId
            );


            fetch(
                "../app/controllers/AdminController.php",
                {
                    method: "POST",
                    body: formData
                }
            )
            .then(response => response.json())
            .then(data => {

                alert(data.message);


                if (data.success) {

                    loadDoctors();

                }

            })
            .catch(error => {

                console.log(
                    "Deactivate doctor error:",
                    error
                );

            });

        }

    }
);


// ======================================================
// DELETE INACTIVE DOCTOR
// ======================================================

document.addEventListener(
    "click",
    function (event) {

        if (
            event.target.classList.contains(
                "delete-doctor-button"
            )
        ) {

            let doctorId =
            event.target.getAttribute(
                "data-id"
            );


            let confirmDelete =
            confirm(
                "Permanently delete this inactive doctor?"
            );


            if (!confirmDelete) {

                return;

            }


            let formData =
            new FormData();


            formData.append(
                "action",
                "deleteDoctor"
            );


            formData.append(
                "doctor_id",
                doctorId
            );


            fetch(
                "../app/controllers/AdminController.php",
                {
                    method: "POST",
                    body: formData
                }
            )
            .then(response => response.json())
            .then(data => {

                alert(data.message);


                if (data.success) {

                    loadDoctors();

                }

            })
            .catch(error => {

                console.log(
                    "Delete doctor error:",
                    error
                );

            });

        }

    }
);


// ======================================================
// REVENUE MANAGEMENT
// ======================================================


// ======================================================
// LOAD REVENUE
// ======================================================

let revenueTableBody =
document.getElementById("revenueTableBody");

let revenueSearch =
document.getElementById("revenueSearch");


function loadRevenue(search = "")
{
    if (!revenueTableBody) {
        return;
    }


    let url =
    "../app/controllers/AdminController.php?action=getRevenue";


if (search !== "") {

    url +=
    "&search=" +
    encodeURIComponent(search);

}


fetch(url)
.then(response => response.json())
.then(data => {

    revenueTableBody.innerHTML = "";


    if (!data.success) {
        return;
    }


    data.revenues.forEach(function (revenue) {

        let row =
        document.createElement("tr");


        row.innerHTML = `

        <td>
        ${revenue.id}
        </td>

        <td>
        ${revenue.description}
        </td>

        <td>
        ৳${Number(revenue.amount).toLocaleString()}
        </td>

        <td>
        ${revenue.created_at}
        </td>

        <td>

        <button
        type="button"
        class="edit-revenue-button"
        data-id="${revenue.id}">
        Edit
        </button>

        <button
        type="button"
        class="delete-revenue-button"
        data-id="${revenue.id}">
        Delete
        </button>

        </td>

        `;


        revenueTableBody.appendChild(row);

    });

})
.catch(error => {

    console.log(
        "Revenue loading error:",
        error
    );

});
}


// Load all revenue when page opens

if (revenueTableBody) {

    loadRevenue();

}


// ======================================================
// SEARCH REVENUE
// ======================================================

if (revenueSearch) {

    revenueSearch.addEventListener(
        "input",
        function () {

            loadRevenue(
                this.value.trim()
            );

        }
    );

}


// ======================================================
// ADD REVENUE
// ======================================================

let revenueForm =
document.getElementById("revenueForm");


if (revenueForm) {

    revenueForm.addEventListener(
        "submit",
        function (event) {

            event.preventDefault();


            let amount =
            document.getElementById(
                "revenueAmount"
            ).value.trim();


            let description =
            document.getElementById(
                "revenueDescription"
            ).value.trim();


            if (amount === "" || description === "") {

                document.getElementById(
                    "revenueMessage"
                ).innerText =
                "Please fill in all fields.";

                    return;

            }


            if (Number(amount) <= 0) {

                document.getElementById(
                    "revenueMessage"
                ).innerText =
                "Amount must be greater than 0.";

                    return;

            }


            let formData =
            new FormData(revenueForm);


            fetch(
                "../app/controllers/AdminController.php",
                {
                    method: "POST",
                    body: formData
                }
            )

            .then(response => response.json())

            .then(data => {

                document.getElementById(
                    "revenueMessage"
                ).innerText =
                data.message;


                if (data.success) {

                    revenueForm.reset();

                    loadRevenue();

                    loadRevenueSummary();

                }

            })

            .catch(error => {

                console.log(
                    "Add revenue error:",
                    error
                );

                document.getElementById(
                    "revenueMessage"
                ).innerText =
                "Something went wrong.";

            });

        }
    );

}


// ======================================================
// EDIT REVENUE
// ======================================================

document.addEventListener(
    "click",
    function (event) {

        if (
            event.target.classList.contains(
                "edit-revenue-button"
            )
        ) {

            let revenueId =
            event.target.getAttribute(
                "data-id"
            );


            fetch(
                "../app/controllers/AdminController.php?action=getRevenueById&id="
                + revenueId
            )

            .then(response => response.json())

            .then(data => {

                if (!data.success) {

                    alert(data.message);

                    return;

                }


                let revenue =
                data.revenue;


                document.getElementById(
                    "editRevenueSection"
                ).style.display =
                "block";


    document.getElementById(
        "editRevenueId"
    ).value =
    revenue.id;


    document.getElementById(
        "editRevenueAmount"
    ).value =
    revenue.amount;


    document.getElementById(
        "editRevenueDescription"
    ).value =
    revenue.description;


    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });

            })

            .catch(error => {

                console.log(
                    "Get revenue error:",
                    error
                );

            });

        }

    }
);


// ======================================================
// UPDATE REVENUE
// ======================================================

let editRevenueForm =
document.getElementById("editRevenueForm");


if (editRevenueForm) {

    editRevenueForm.addEventListener(
        "submit",
        function (event) {

            event.preventDefault();


            let amount =
            document.getElementById(
                "editRevenueAmount"
            ).value.trim();


            let description =
            document.getElementById(
                "editRevenueDescription"
            ).value.trim();


            if (
                amount === "" ||
                description === ""
            ) {

                document.getElementById(
                    "editRevenueMessage"
                ).innerText =
                "Please fill in all fields.";

                    return;

            }


            if (Number(amount) <= 0) {

                document.getElementById(
                    "editRevenueMessage"
                ).innerText =
                "Amount must be greater than 0.";

                    return;

            }


            let formData =
            new FormData(editRevenueForm);


            fetch(
                "../app/controllers/AdminController.php",
                {
                    method: "POST",
                    body: formData
                }
            )

            .then(response => response.json())

            .then(data => {

                document.getElementById(
                    "editRevenueMessage"
                ).innerText =
                data.message;


                if (data.success) {

                    loadRevenue();

                    loadRevenueSummary();

                    editRevenueForm.reset();


                    document.getElementById(
                        "editRevenueSection"
                    ).style.display =
                    "none";

                }

            })

            .catch(error => {

                console.log(
                    "Update revenue error:",
                    error
                );

            });

        }
    );

}


// ======================================================
// CANCEL EDIT
// ======================================================

let cancelEditRevenue =
document.getElementById(
    "cancelEditRevenue"
);


if (cancelEditRevenue) {

    cancelEditRevenue.addEventListener(
        "click",
        function () {

            document.getElementById(
                "editRevenueSection"
            ).style.display =
            "none";


        document.getElementById(
            "editRevenueForm"
        ).reset();

        }
    );

}


// ======================================================
// DELETE REVENUE
// ======================================================

document.addEventListener(
    "click",
    function (event) {

        if (
            event.target.classList.contains(
                "delete-revenue-button"
            )
        ) {

            let revenueId =
            event.target.getAttribute(
                "data-id"
            );


            let confirmDelete =
            confirm(
                "Are you sure you want to delete this revenue record?"
            );


            if (!confirmDelete) {
                return;
            }


            let formData =
            new FormData();


            formData.append(
                "action",
                "deleteRevenue"
            );


            formData.append(
                "revenue_id",
                revenueId
            );


            fetch(
                "../app/controllers/AdminController.php",
                {
                    method: "POST",
                    body: formData
                }
            )

            .then(response => response.json())

            .then(data => {

                alert(data.message);


                if (data.success) {

                    loadRevenue();

                    loadRevenueSummary();

                }

            })

            .catch(error => {

                console.log(
                    "Delete revenue error:",
                    error
                );

            });

        }

    }
);


// ======================================================
// REVENUE SUMMARY
// ======================================================

function loadRevenueSummary()
{
    let totalElement =
    document.getElementById(
        "totalRevenuePage"
    );


    if (!totalElement) {
        return;
    }


    fetch(
        "../app/controllers/AdminController.php?action=revenueData"
    )

    .then(response => response.json())

    .then(data => {

        if (!data.success) {
            return;
        }


        document.getElementById(
            "totalRevenuePage"
        ).innerText =
        "৳" +
        Number(data.totalRevenue)
        .toLocaleString();


        document.getElementById(
            "todayRevenue"
        ).innerText =
        "৳" +
        Number(data.todayRevenue)
        .toLocaleString();


        document.getElementById(
            "monthRevenue"
        ).innerText =
        "৳" +
        Number(data.monthRevenue)
        .toLocaleString();

    })

    .catch(error => {

        console.log(
            "Revenue summary error:",
            error
        );

    });

}


if (
    document.getElementById(
        "totalRevenuePage"
    )
) {

    loadRevenueSummary();

}

// ======================================================
// REVIEW MANAGEMENT
// ======================================================


// ======================================================
// LOAD / SEARCH REVIEWS
// ======================================================

let reviewTableBody =
document.getElementById("reviewTableBody");

let reviewSearch =
document.getElementById("reviewSearch");


function loadReviews(search = "")
{
    if (!reviewTableBody) {
        return;
    }


    let url =
    "../app/controllers/AdminController.php?action=getReviews";


if (search !== "") {

    url +=
    "&search=" +
    encodeURIComponent(search);

}


fetch(url)

.then(response => response.json())

.then(data => {

    reviewTableBody.innerHTML = "";


    if (!data.success) {
        return;
    }


    data.reviews.forEach(function (review) {

        let row =
        document.createElement("tr");


        let rating =
        Number(review.rating);


        let stars =
        "★".repeat(rating) +
        "☆".repeat(5 - rating);


        row.innerHTML = `

        <td>
        ${review.id}
        </td>

        <td>
        ${review.patient_name}
        </td>

        <td>
        ${review.doctor_name}
        </td>

        <td>
        ${stars}
        </td>

        <td>
        ${review.comment}
        </td>

        <td>
        ${review.created_at}
        </td>

        <td>

        <button
        type="button"
        class="edit-review-button"
        data-id="${review.id}">
        Edit
        </button>

        <button
        type="button"
        class="delete-review-button"
        data-id="${review.id}">
        Delete
        </button>

        </td>

        `;


        reviewTableBody.appendChild(row);

    });

})

.catch(error => {

    console.log(
        "Review loading error:",
        error
    );

});

}


// Load reviews when page opens

if (reviewTableBody) {

    loadReviews();

}


// Search reviews

if (reviewSearch) {

    reviewSearch.addEventListener(
        "input",
        function () {

            loadReviews(
                this.value.trim()
            );

        }
    );

}


// ======================================================
// EDIT REVIEW
// ======================================================

document.addEventListener(
    "click",
    function (event) {

        if (
            event.target.classList.contains(
                "edit-review-button"
            )
        ) {

            let reviewId =
            event.target.getAttribute(
                "data-id"
            );


            fetch(
                "../app/controllers/AdminController.php?action=getReview&id="
                + reviewId
            )

            .then(response => response.json())

            .then(data => {

                if (!data.success) {

                    alert(data.message);

                    return;

                }


                let review =
                data.review;


                document.getElementById(
                    "editReviewSection"
                ).style.display =
                "block";


    document.getElementById(
        "editReviewId"
    ).value =
    review.id;


    document.getElementById(
        "editReviewRating"
    ).value =
    review.rating;


    document.getElementById(
        "editReviewComment"
    ).value =
    review.comment;


    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });

            })

            .catch(error => {

                console.log(
                    "Get review error:",
                    error
                );

            });

        }

    }
);


// ======================================================
// UPDATE REVIEW
// ======================================================

let editReviewForm =
document.getElementById("editReviewForm");


if (editReviewForm) {

    editReviewForm.addEventListener(
        "submit",
        function (event) {

            event.preventDefault();


            let formData =
            new FormData(this);


            fetch(
                "../app/controllers/AdminController.php",
                {
                    method: "POST",
                    body: formData
                }
            )

            .then(response => response.json())

            .then(data => {

                document.getElementById(
                    "editReviewMessage"
                ).innerText =
                data.message;


                if (data.success) {

                    loadReviews();

                    editReviewForm.reset();


                    document.getElementById(
                        "editReviewSection"
                    ).style.display =
                    "none";

                }

            })

            .catch(error => {

                console.log(
                    "Update review error:",
                    error
                );

            });

        }
    );

}


// ======================================================
// CANCEL EDIT REVIEW
// ======================================================

let cancelEditReview =
document.getElementById(
    "cancelEditReview"
);


if (cancelEditReview) {

    cancelEditReview.addEventListener(
        "click",
        function () {

            document.getElementById(
                "editReviewSection"
            ).style.display =
            "none";


        editReviewForm.reset();

        }
    );

}


// ======================================================
// DELETE REVIEW
// ======================================================

document.addEventListener(
    "click",
    function (event) {

        if (
            event.target.classList.contains(
                "delete-review-button"
            )
        ) {

            let reviewId =
            event.target.getAttribute(
                "data-id"
            );


            let confirmDelete =
            confirm(
                "Are you sure you want to delete this review?"
            );


            if (!confirmDelete) {
                return;
            }


            let formData =
            new FormData();


            formData.append(
                "action",
                "deleteReview"
            );


            formData.append(
                "review_id",
                reviewId
            );


            fetch(
                "../app/controllers/AdminController.php",
                {
                    method: "POST",
                    body: formData
                }
            )

            .then(response => response.json())

            .then(data => {

                alert(data.message);


                if (data.success) {

                    loadReviews();

                }

            })

            .catch(error => {

                console.log(
                    "Delete review error:",
                    error
                );

            });

        }

    }
);

// ======================================================
// ACTIVE SIDEBAR LINK
// ======================================================

let currentPage =
window.location.pathname
.split("/")
.pop();


let navLinks =
document.querySelectorAll(
    ".sidebar .nav-link"
);


navLinks.forEach(function (link) {

    let linkPage =
    link.getAttribute("href")
    .split("/")
    .pop();


    if (linkPage === currentPage) {

        link.classList.add("active");

    }

});
