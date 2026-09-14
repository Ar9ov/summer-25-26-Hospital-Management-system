# Hospital Management System — Merged Setup Guide

This project is 4 independently-built panels that share one MySQL database:

- **Admin control** — dashboard, doctor management, revenue, reviews
- **Doctor panel** — patients (medical records), prescriptions, leave, patient history
- **Patient login** — patient signup/login, browse doctors, book appointments
- **Receptionist and Billing** — walk-in patient registration, invoices

## 1. Import the database

1. Start **Apache** and **MySQL** in the XAMPP control panel.
2. Open `http://localhost/phpmyadmin`.
3. Click **Import** → choose **`hospital_management.sql`** (in this folder) → **Go**.

This creates the `hospital_management` database with every table all 4 panels
need, plus a few seeded accounts (see credentials below).

## 2. Copy the folders into `htdocs`

Copy this whole `Hospital_Managment_System` folder into your XAMPP `htdocs`
directory, then **rename the 4 module folders exactly as below** — the panels
link to each other using these names (this is what makes "login once, land in
the right panel" work):

```
htdocs/
└── Hospital_Managment_System/
    ├── admin/                     <- rename "Admin control" to this
    ├── doctor-panel/              <- rename "Doctor panel" to this
    ├── patient-login/             <- rename "Patient login" to this
    ├── receptionist-billing/      <- rename "Receptionist and Billing" to this
    └── hospital_management.sql
```

If you'd rather keep the original folder names, that's fine too — just open
`admin/public/assets/js/app.js` and `receptionist-billing/index.php` /
`api/index.php` and update the relative paths (`../doctor-panel/...`, etc.)
to match whatever names you use.

## 3. Log in

Open `http://localhost/Hospital_Managment_System/admin/public/login.php` —
this is the shared login for **every** role (admin, doctor, patient,
receptionist all log in here and get redirected to their own panel
automatically). Seeded accounts (all use the same password):

| Role         | Email                          | Password    |
|--------------|---------------------------------|-------------|
| Admin        | admin@hospital.test             | Passw0rd!   |
| Receptionist | reception@hospital.test         | Passw0rd!   |
| Doctor       | ayesha.doctor@hospital.test     | Passw0rd!   |
| Doctor       | kamal.doctor@hospital.test      | Passw0rd!   |
| Patient      | rafiq.patient@hospital.test     | Passw0rd!   |

New **patients** can also self-register at
`patient-login/controller/register.php` (or via the shared signup page,
which also defaults new accounts to the patient role). New **doctors** can
self-register with extra profile fields at
`doctor-panel/index.php?page=signup`. Admin and receptionist accounts are
meant to be provisioned directly (seed more of them in the SQL, or add rows
via phpMyAdmin) — there's no public signup form for those roles, which is
intentional for a hospital system.

## 4. What was fixed to make this actually run

The zip had never been through an actual merge — each of the 4 panels was
built and tested against its own separate, incompatible database, and no SQL
file was ever included despite the READMEs mentioning one. Specifically:

- **No SQL files at all.** Every table/column in `hospital_management.sql`
  was reverse-engineered from the actual queries in the PHP code.
- **4 different database names** — `hospital_management`, `hospital_db`,
  `receptionist_billing`, and Admin's own copy. All 4 panels' config files
  now point at the single `hospital_management` database.
- **3 incompatible "patients" tables** with different columns (medical
  record vs. login account vs. walk-in registration). Kept the Doctor
  panel's `patients` table as-is (its intended meaning), pointed the
  Patient login panel at the shared `users` table instead of its own,
  and renamed the Receptionist panel's table to `reception_patients` to
  remove the name collision.
- **Plaintext passwords** in the Patient login panel — now uses
  `password_hash()` / `password_verify()`, consistent with the other panels.
- **A fatal bug**: `AdminController::reviewData()` called
  `Review::getRecentReviews()`, which didn't exist — added it.
- **Broken queries** in the Patient login panel that joined on columns
  (`doctors.doctor_id`, `doctors.full_name`) that don't exist in the real
  shared schema — fixed to join through `users` correctly (existing views
  didn't need to change; the fix aliases columns back to what they expect).
- **Hardcoded absolute paths** (`/hospital_management/public/...`) in the
  Admin panel that only worked if deployed at that exact web root — now
  relative, so the folder can live anywhere.
- **No authentication at all** on the Receptionist/Billing panel — added a
  session guard so only a logged-in `receptionist` account can use it.
- **Missing `exit` after a failed-auth redirect** in the patient dashboard
  controller — the page kept rendering even after telling the browser to
  redirect away.
- Aligned session variable names (`user_id`, `role`) across all 4 panels.
  Because PHP sessions are shared by default across sibling folders on the
  same host, this means logging in once (via the Admin panel's login page)
  now correctly carries your session into whichever panel matches your role.

## 5. Known limitation to be aware of

The Admin panel's `reviews.doctor_id` refers to `doctors.id`, while the
Doctor panel's `prescriptions.doctor_id` / `doctor_leaves.doctor_id` refer
directly to `users.id` (the doctor's own login id). Both conventions were
kept exactly as each original module's code expects, since they never
query each other's tables — but if you extend the code later to cross-link
them, keep this distinction in mind.
