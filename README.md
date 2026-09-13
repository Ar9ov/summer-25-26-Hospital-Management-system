# Receptionist and Billing (MVC + Ajax/JSON)

## Features
- Counter R – Walk-in Patient (Create, Read, Update, Delete, Search)
- Invoice Creation – Create invoice for patient
- Payment Status – Mark Paid / Pending (Ajax)
- Print Invoice – Open printable invoice

## MVC Structure
```
rb_complete/
├── api/                    → API entry (Ajax/JSON)
├── assets/
│   ├── css/style.css
│   └── js/app.js
├── config/config.php
├── controllers/
│   └── reception_controller.php
├── helpers/helpers.php
├── models/
│   ├── patient_model.php
│   └── invoice_model.php
├── views/
│   ├── header.php
│   ├── home.php
│   └── footer.php
├── database.sql
├── index.php
└── README.md
```

## Setup (XAMPP)
1. Import `database.sql` in phpMyAdmin.
2. Put this folder inside `xampp/htdocs/`.
3. Open `http://localhost/rb_complete/`.
4. Counter R, Invoice Creation and Payment Status use AJAX/JSON.
