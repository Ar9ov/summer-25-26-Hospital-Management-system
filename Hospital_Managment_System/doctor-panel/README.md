# Doctor Panel Module

This is **Member 2's** part of the group web app: the **Doctor Panel**, covering
the 3 features from the group's assignment sheet:

1. **Emergency Leave** — doctor submits/cancels leave requests.
2. **Prescription Writer** — pick a patient, type a diagnosis, add unlimited
   medicine rows (name, dosage, frequency, duration), save & print.
3. **Patient History** — a searchable, chronological timeline per patient
   built from past prescriptions + free-form visit notes.

Plus the required **Create / Read / Update / Delete / Search** actions on the
doctor's own dashboard, implemented on the **Patients** entity (add, edit,
delete, and live-search patient records).

## Setup on XAMPP

This module now points at the **shared `hospital_management` database**
owned by the Admin panel (Member 4) — not a standalone database. Use
`hospital_management_combined.sql` (teammate's dump + this module's tables
appended), not the older `database/schema.sql`, which is now just a
reference for the old standalone version.

1. Copy the `doctor-panel/` folder into the same `htdocs/` project folder
   your teammates are using (so it shares one codebase root eventually —
   see "Merging with your team" below).
2. Start Apache + MySQL in the XAMPP control panel.
3. Open `http://localhost/phpmyadmin` → **Import** tab → choose
   `hospital_management_combined.sql` → Go. This creates `users`, `doctors`,
   `revenue`, `reviews` (Admin's tables, with his existing test data) plus
   `patients`, `doctor_leaves`, `prescriptions`, `prescription_medicines`,
   `patient_visit_notes` (this module's tables).
4. `config/db.php` is already set to `DB_NAME = 'hospital_management'`.
5. Visit `http://localhost/.../doctor-panel/index.php?page=signup` and
   create a fresh doctor account through the form (writes into both
   `users` and `doctors` correctly) — don't try to log in with the
   existing seeded doctor accounts, since you don't know their plaintext
   passwords.
6. Log in and you should land on the doctor dashboard.

### Known integration gaps with the Admin panel's schema

- The Admin's `doctors.status` column (`active`/`inactive`) looks like an
  approval flag, but this module's login only checks `users.status`. If
  Admin wants to be able to deactivate a doctor from their panel, ask them
  to flip `users.status` too (or tell me and I'll wire the login check to
  also look at `doctors.status`).
- `doctors.specialization` is `NOT NULL` in the shared schema — the signup
  form defaults it to `"General"` if left blank.
- There's no `patients` table in the Admin's dump — "patients" there are
  just `users` with `role = 'patient'`. This module's `patients` table is
  a separate **medical record** entity (age, gender, blood group, etc.)
  that a doctor creates, not tied to a patient's login account. That's
  intentional (matches the CRUD+Search requirement on the doctor's own
  dashboard) but flag it with your team if they expect doctors to look up
  patients by their actual login account instead.

## Folder structure (MVC)

```
config/       -> db.php (mysqli connection)
models/       -> one file per entity, procedural mysqli, prepared statements
controllers/  -> request handling + validation calls, no HTML
views/        -> all HTML output, split into auth/, doctor/, partials/
ajax/         -> JSON endpoints used by the live search
public/       -> css/js served to the browser
index.php     -> front controller / router (?page=...)
```

## How this maps to the grading criteria

| Requirement | Where it lives |
|---|---|
| MVC | models/, controllers/, views/ are cleanly separated; index.php is the only router |
| DB (MySQL procedural) | every query in `models/*.php` uses `mysqli_prepare` + `mysqli_stmt_bind_param` |
| Auth (session/cookie) | `AuthController.php` (login/signup/logout), `includes/auth_check.php` guards every doctor page |
| Basic Web Security | prepared statements everywhere, `password_hash`/`password_verify`, CSRF token on every POST form, `htmlspecialchars` via the `h()` helper on all output, `session_regenerate_id()` on login |
| JS validation | `public/js/validate.js` — client-side, UX only |
| PHP Validation | `includes/functions.php` — `validate_*` functions, always run server-side regardless of JS |
| Ajax/JSON | `ajax/search_patients.php` + `public/js/ajax.js` — live patient search returns JSON |
| UI (HTML/CSS) | `public/css/style.css`, no framework dependency, works standalone |
| Feature Completeness | CRUD+Search on Patients, plus the 3 unique features above, all fully wired end-to-end |

## Assumptions to confirm with your team / group leader

- Shared `users` table with a `role` ENUM covering all 4 panels — align the
  exact role names (`admin`, `doctor`, `patient`, `receptionist`, etc.) with
  whoever owns the Admin panel, since login redirects by role.
- Doctors can self-register via Sign Up here; if your team decides doctor
  accounts should only be created by Admin, delete `views/auth/signup.php`
  and the `signup` route, and ask the Admin-panel member to add doctor
  creation to their CRUD instead.
- Table/column names in `database/schema.sql` should be merged with the
  rest of the team's schema so there's one shared database, not four.

## Merging with your team

Since the group leader owns the repo, don't just dump this folder in —
agree on one top-level `config/db.php`, one `includes/auth_check.php`
pattern, and one shared `users` table so all 4 panels' logins work through
the same session mechanism. This module's `models/`, `controllers/`, and
`views/doctor/` files can otherwise drop in largely as-is.
