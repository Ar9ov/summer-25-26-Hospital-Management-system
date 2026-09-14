#Patient Portal Module (Member 1)
This is Member 1's part of the group web application: the Patient Portal, covering the patient-side features from the Hospital Management System project.
Features Implemented
1. Patient Registration & Login
Patients can create an account and securely log in to access the system.
Features include:
•	Patient Registration
•	Patient Login
•	Session-based Authentication
•	Logout Functionality
•	Form Validation (Client-side and Server-side)

2. Doctor Search
Patients can search and view available doctors before booking appointments.
Features include:
•	View Doctor List
•	Search Doctors by Name
•	Search Doctors by Department
•	Check Doctor Availability Status

3. Appointment Booking
Patients can schedule appointments with available doctors.
Features include:
•	Select Doctor
•	Choose Appointment Date
•	Submit Appointment Request
•	Appointment Status Tracking

4. Appointment History
Patients can view their previously booked appointments.
Information displayed:
•	Doctor Name
•	Appointment Date
•	Appointment Status

Setup on XAMPP
Step 1: Copy the Module
Copy the following folder into:
C:\xampp\htdocs</span>
Example:
C:\xampp\htdocs\hospital_project

Step 2: Start XAMPP
Start:
Apache
MySQL
from the XAMPP Control Panel.

Step 3: Create Database
Open:
http://localhost/phpmyadmin
Create a database named:
hospital_db

Step 4: Import Database
Import the provided:
hospital_db.sql
This creates:
patients
doctors
appointments
tables and inserts sample doctor data.

Step 5: Configure Database Connection
File:
model/db.php
Configuration:
localhost
root
(blank password)
hospital_db

Step 6: Run the Module
Registration Page:
http://localhost/hospital_project/controller/register.php
Login Page:
http://localhost/hospital_project/controller/login.php
Dashboard:
http://localhost/hospital_project/controller/dashboard.php
Appointment Page:
http://localhost/hospital_project/controller/appointment.php

Folder Structure (MVC)
hospital_project

├── assets
│ └── style.css

├── model
│ ├── db.php
│ ├── PatientModel.php
│ ├── DoctorModel.php
│ └── AppointmentModel.php

├── controller
│ ├── register.php
│ ├── login.php
│ ├── dashboard.php
│ ├── appointment.php
│ └── logout.php

├── view
│ ├── register.php
│ ├── login.php
│ ├── dashboard.php
│ └── appointment.php

└── hospital_db.sql

How This Maps to the Grading Criteria
Requirement	Where it lives
MVC Architecture	Separate model/, controller/, and view/ folders
MySQL Database	model/db.php
Prepared Statements	PatientModel.php and AppointmentModel.php
Authentication	login.php, logout.php, PHP Session
Session Management	Uses $_SESSION["patient_id"] after login
Form Validation	Registration and Login forms
Doctor Search	dashboard.php + DoctorModel.php
Appointment Booking	appointment.php + AppointmentModel.php
UI (HTML/CSS)	assets/style.css and view files
CRUD Operations	Registration (Create), Doctor View (Read), Appointment Booking (Create)
Basic Web Security	Prepared Statements and Session-based login

Technology Stack
Frontend
•	HTML
•	CSS
•	JavaScript Validation
Backend
•	PHP (Procedural)
Database
•	MySQL
Architecture
•	MVC (Model-View-Controller)
Environment
•	XAMPP

Assumptions for Team Integration
Doctor Panel (Member 2)
Doctor entries are stored in:
doctors
table.
Doctor panel should update:
availability
status when needed.

Reception & Billing (Member 3)
Appointments created by patients appear in:
appointments
``
table.
Receptionist can:
•	Approve Appointment
•	Cancel Appointment
•	Generate Bills

Admin Panel (Member 4)
Admin can:
•	Add Doctors
•	Update Doctors
•	Delete Doctors
•	Manage System Data
using the same shared database.

Merging with Team Project
Before final submission, all members should agree on:
•	One shared database: 
hospital_db
•	One database connection file: 
model/db.php
•	One session-based authentication system
•	Consistent table names:
patients
doctors
appointments
The Patient Portal module can then be merged into the main Hospital Management System with minimal code changes.


Doctor Panel Module (Member 2 Guide)
Core Features
Emergency Leave: Submit and cancel leave requests.

Prescription Writer: Select a patient, enter a diagnosis, add unlimited medicine rows (name, dosage, frequency, duration), and save/print.

Patient History: Searchable, chronological timeline per patient built from past prescriptions and free-form visit notes.

Patient Management (CRUD + Search): Add, edit, delete, and live-search patient records directly from the doctor's dashboard.

XAMPP & Database Setup
Directory Placement: Copy the doctor-panel/ folder into your shared htdocs/ project folder.

Database Configuration: This module uses the shared hospital_management database (Admin's database). config/db.php is already configured with DB_NAME = 'hospital_management'.

Database Import:

Start Apache and MySQL in the XAMPP control panel.

Open http://localhost/phpmyadmin.

Go to the Import tab.

Choose hospital_management_combined.sql (use this combined file, not the older schema.sql reference file) and click Go.

Note: This imports Admin's tables (users, doctors, revenue, reviews) and this module's tables (patients, doctor_leaves, prescriptions, prescription_medicines, patient_visit_notes).

Account Creation & Login:

Open http://localhost/.../doctor-panel/index.php?page=signup and create a fresh doctor account through the form. (Do not use seeded doctor accounts since plaintext passwords are unknown).

Log in with the newly created account to access the doctor dashboard. (Note: The signup form defaults doctors.specialization to "General" if left blank).

Known Integration Notes & Gaps
Doctor Status: The Admin's doctors.status column (active/inactive) acts as an approval flag, but this module's login currently checks users.status. Coordinate with the Admin panel to sync status changes if needed.

Patients Entity: The patients table in this module is a separate medical record entity managed by the doctor (tracking age, gender, blood group, etc.) and is not tied directly to user login accounts.

Folder structure (MVC)
hospital_project/
├── assets/
│   └── style.css[cite: 1]
├── model/
│   ├── db.php[cite: 1]
│   ├── PatientModel.php[cite: 1]
│   ├── HistoryModel.php[cite: 1]
│   ├── LeaveModel.php[cite: 1]
│   ├── PrescriptionModel.php[cite: 1]
│   └── UserModel.php[cite: 1]
├── controller/
│   ├── AuthController.php[cite: 1]
│   ├── HistoryController.php[cite: 1]
│   ├── LeaveController.php[cite: 1]
│   ├── PatientController.php[cite: 1]
│   └── PrescriptionController.php[cite: 1]
├── view/
│   ├── login.php[cite: 1]
│   ├── signup.php[cite: 1]
│   ├── dashboard.php[cite: 1]
│   ├── emergency_leave.php[cite: 1]
│   ├── patients_list.php[cite: 1]
│   ├── patient_form.php[cite: 1]
│   ├── patient_history.php[cite: 1]
│   ├── prescriptions_list.php[cite: 1]
│   ├── prescription_view.php[cite: 1]
│   └── prescription_writer.php[cite: 1]
└── hospital_db.sql[cite: 1]

How this maps to the grading criteria
Requirement	Where it lives
MVC	models/, controllers/, views/ are cleanly separated; index.php is the only router
DB (MySQL procedural)	every query in `models/*.php` uses `mysqli_prepare` + `mysqli_stmt_bind_param`
Auth (session/cookie)	`AuthController.php` (login/signup/logout), `includes/auth_check.php` guards every doctor page
Basic Web Security	prepared statements everywhere, `password_hash`/`password_verify`, CSRF token on every POST form, `htmlspecialchars` via the `h()` helper on all output, `session_regenerate_id()` on login
JS validation	`public/js/validate.js` — client-side, UX only
PHP Validation	`includes/functions.php` — `validate_*` functions, always run server-side regardless of JS
Ajax/JSON	`ajax/search_patients.php` + `public/js/ajax.js` — live patient search returns JSON
UI (HTML/CSS)	`public/css/style.css`, no framework dependency, works standalone
Feature Completeness	CRUD+Search on Patients, plus the 3 unique features above, all fully wired end-to-end
Assumptions to confirm with your team / group leader
Shared `users` table with a `role` ENUM covering all 4 panels — align the
exact role names (`admin`, `doctor`, `patient`, `receptionist`, etc.) with
whoever owns the Admin panel, since login redirects by role.
Doctors can self-register via Sign Up here; if your team decides doctor
accounts should only be created by Admin, delete `views/auth/signup.php`
and the `signup` route, and ask the Admin-panel member to add doctor
creation to their CRUD instead.
Table/column names in `database/schema.sql` should be merged with the
rest of the team's schema so there's one shared database, not four.
Merging with your team
Since the group leader owns the repo, don't just dump this folder in —
agree on one top-level `config/db.php`, one `includes/auth_check.php`
pattern, and one shared `users` table so all 4 panels' logins work through
the same session mechanism. This module's `models/`, `controllers/`, and
`views/doctor/` files can otherwise drop in largely as-is.
