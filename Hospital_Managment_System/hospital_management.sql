-- =====================================================================
-- Hospital Management System -- consolidated database
-- =====================================================================
-- This is the ONE database shared by all 4 panels:
--   Admin control, Doctor panel, Patient login, Receptionist and Billing
--
-- None of the 4 modules shipped with a working SQL file (their READMEs
-- referenced files like `hospital_management_combined.sql` that were
-- never actually included). This schema was reverse-engineered from
-- every query in the codebase so each panel's queries resolve correctly
-- against real tables/columns.
--
-- HOW TO IMPORT (XAMPP / phpMyAdmin):
--   1. Start Apache + MySQL in the XAMPP control panel.
--   2. Open http://localhost/phpmyadmin
--   3. Click "Import" -> choose this file -> Go.
--   (Or from a terminal: mysql -u root -p < hospital_management.sql)
-- =====================================================================

CREATE DATABASE IF NOT EXISTS hospital_management
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE hospital_management;

SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- users
-- One login table shared by every panel (admin / doctor / patient /
-- receptionist). Admin & Doctor panels created this table's core shape;
-- `phone` was added so the Patient panel can store a mobile number
-- without needing its own separate login table.
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  phone VARCHAR(30) DEFAULT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','doctor','patient','receptionist') NOT NULL DEFAULT 'patient',
  status ENUM('active','inactive') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- doctors
-- Doctor-specific profile data, one row per doctor user.
-- NOTE: the Admin panel's own reviews.doctor_id refers to doctors.id,
-- while the Doctor panel's own prescriptions/leaves tables key doctor_id
-- off users.id directly (the doctor's own login id). Both conventions
-- are kept exactly as each module's original code expects -- they never
-- cross-query each other, so this does not cause a runtime conflict,
-- just something to be aware of if you extend the code later.
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS doctors;
CREATE TABLE doctors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  specialization VARCHAR(150) NOT NULL,
  phone VARCHAR(30) DEFAULT NULL,
  status ENUM('active','inactive') NOT NULL DEFAULT 'active',
  joined_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_doctors_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- revenue (Admin panel)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS revenue;
CREATE TABLE revenue (
  id INT AUTO_INCREMENT PRIMARY KEY,
  amount DECIMAL(12,2) NOT NULL,
  description VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- reviews (Admin panel) -- doctor_id references doctors.id
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS reviews;
CREATE TABLE reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  doctor_id INT NOT NULL,
  rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
  comment TEXT,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_reviews_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_reviews_doctor FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- patients (Doctor panel's medical-record entity)
-- Intentionally separate from `users` -- a doctor can create a medical
-- record for a walk-in / phone patient who has no login account.
-- created_by / doctor_id columns in the tables below store the doctor's
-- users.id (this module's own convention).
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS patients;
CREATE TABLE patients (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(150) NOT NULL,
  age INT NOT NULL,
  gender ENUM('male','female','other') NOT NULL,
  phone VARCHAR(30) DEFAULT NULL,
  address VARCHAR(255) DEFAULT NULL,
  blood_group VARCHAR(5) DEFAULT NULL,
  created_by INT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_patients_doctor FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- prescriptions + prescription_medicines (Doctor panel)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS prescription_medicines;
DROP TABLE IF EXISTS prescriptions;
CREATE TABLE prescriptions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  patient_id INT NOT NULL,
  doctor_id INT NOT NULL,
  diagnosis VARCHAR(255) NOT NULL,
  notes TEXT,
  visit_date DATE NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_presc_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
  CONSTRAINT fk_presc_doctor FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE prescription_medicines (
  id INT AUTO_INCREMENT PRIMARY KEY,
  prescription_id INT NOT NULL,
  medicine_name VARCHAR(150) NOT NULL,
  dosage VARCHAR(100) NOT NULL,
  frequency VARCHAR(100) NOT NULL,
  duration VARCHAR(100) NOT NULL,
  CONSTRAINT fk_med_prescription FOREIGN KEY (prescription_id) REFERENCES prescriptions(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- doctor_leaves (Doctor panel -- Emergency Leave feature)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS doctor_leaves;
CREATE TABLE doctor_leaves (
  id INT AUTO_INCREMENT PRIMARY KEY,
  doctor_id INT NOT NULL,
  leave_from DATE NOT NULL,
  leave_to DATE NOT NULL,
  reason VARCHAR(255) NOT NULL,
  status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_leave_doctor FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- patient_visit_notes (Doctor panel -- Patient History feature)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS patient_visit_notes;
CREATE TABLE patient_visit_notes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  patient_id INT NOT NULL,
  doctor_id INT NOT NULL,
  visit_date DATE NOT NULL,
  symptoms VARCHAR(255) NOT NULL,
  treatment_notes TEXT,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_visit_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
  CONSTRAINT fk_visit_doctor FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- appointments (Patient login panel)
-- patient_id -> users.id (the logged-in patient)
-- doctor_id  -> doctors.id
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS appointments;
CREATE TABLE appointments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  patient_id INT NOT NULL,
  doctor_id INT NOT NULL,
  appointment_date DATE NOT NULL,
  status ENUM('Pending','Confirmed','Cancelled','Completed') NOT NULL DEFAULT 'Pending',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_appt_patient FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_appt_doctor FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- reception_patients (Receptionist and Billing panel)
-- Renamed from the original "patients" to avoid colliding with the
-- Doctor panel's own "patients" table -- these are separate, unrelated
-- entities (walk-in front-desk registration vs. a doctor's medical
-- record) that happened to share a name before the merge.
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS reception_patients;
CREATE TABLE reception_patients (
  id INT AUTO_INCREMENT PRIMARY KEY,
  patient_name VARCHAR(150) NOT NULL,
  phone VARCHAR(30) NOT NULL,
  age INT NOT NULL,
  gender ENUM('Male','Female','Other') NOT NULL,
  wheelchair TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- invoices (Receptionist and Billing panel)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS invoices;
CREATE TABLE invoices (
  id INT AUTO_INCREMENT PRIMARY KEY,
  invoice_no VARCHAR(50) NOT NULL UNIQUE,
  patient_id INT NOT NULL,
  consultation_fee DECIMAL(10,2) NOT NULL,
  total_bill DECIMAL(10,2) NOT NULL,
  payment_status ENUM('Paid','Pending') NOT NULL DEFAULT 'Pending',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_invoice_patient FOREIGN KEY (patient_id) REFERENCES reception_patients(id) ON DELETE CASCADE
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================================
-- SEED DATA
-- All seeded passwords are: Passw0rd!
-- (bcrypt hash below via PHP's password_hash(..., PASSWORD_DEFAULT))
-- =====================================================================

-- 1 admin account (Admin panel has no self-service admin signup by
-- design -- admins are provisioned directly, so we seed one here)
INSERT INTO users (name, email, phone, password, role, status) VALUES
('System Admin', 'admin@hospital.test', NULL,
 '$2b$12$Ejv5G5Alxku0QmL7AQNixeUhy533IdUVYmYzGSQNCtRqRJ96H0DIG', 'admin', 'active');

-- 1 receptionist account (Receptionist panel also has no self-service
-- signup -- provisioned directly)
INSERT INTO users (name, email, phone, password, role, status) VALUES
('Front Desk', 'reception@hospital.test', NULL,
 '$2b$12$Ejv5G5Alxku0QmL7AQNixeUhy533IdUVYmYzGSQNCtRqRJ96H0DIG', 'receptionist', 'active');

-- 2 sample doctors (users + doctors profile rows)
INSERT INTO users (name, email, phone, password, role, status) VALUES
('Dr. Ayesha Rahman', 'ayesha.doctor@hospital.test', '01710000001',
 '$2b$12$Ejv5G5Alxku0QmL7AQNixeUhy533IdUVYmYzGSQNCtRqRJ96H0DIG', 'doctor', 'active'),
('Dr. Kamal Hossain', 'kamal.doctor@hospital.test', '01710000002',
 '$2b$12$Ejv5G5Alxku0QmL7AQNixeUhy533IdUVYmYzGSQNCtRqRJ96H0DIG', 'doctor', 'active');

INSERT INTO doctors (user_id, specialization, phone, status) VALUES
((SELECT id FROM users WHERE email = 'ayesha.doctor@hospital.test'), 'Cardiology', '01710000001', 'active'),
((SELECT id FROM users WHERE email = 'kamal.doctor@hospital.test'), 'General Physician', '01710000002', 'active');

-- 1 sample patient (users row, role = patient)
INSERT INTO users (name, email, phone, password, role, status) VALUES
('Rafiq Islam', 'rafiq.patient@hospital.test', '01810000001',
 '$2b$12$Ejv5G5Alxku0QmL7AQNixeUhy533IdUVYmYzGSQNCtRqRJ96H0DIG', 'patient', 'active');

-- A sample review of Dr. Ayesha by Rafiq (Admin panel dashboard data)
INSERT INTO reviews (user_id, doctor_id, rating, comment) VALUES
((SELECT id FROM users WHERE email = 'rafiq.patient@hospital.test'),
 (SELECT id FROM doctors WHERE phone = '01710000001'),
 5, 'Very good service and helpful doctor.');

-- A sample revenue entry
INSERT INTO revenue (amount, description) VALUES
(1500.00, 'Consultation - Cardiology');

-- A sample medical-record patient created by Dr. Ayesha (Doctor panel)
INSERT INTO patients (full_name, age, gender, phone, address, blood_group, created_by) VALUES
('Sadia Karim', 34, 'female', '01910000001', 'Dhaka, Bangladesh', 'O+',
 (SELECT id FROM users WHERE email = 'ayesha.doctor@hospital.test'));

-- A sample walk-in reception patient + invoice (Receptionist panel)
INSERT INTO reception_patients (patient_name, phone, age, gender, wheelchair) VALUES
('Nusrat Jahan', '01555000000', 45, 'Female', 0);

INSERT INTO invoices (invoice_no, patient_id, consultation_fee, total_bill, payment_status) VALUES
('INV-SEED0001', (SELECT id FROM reception_patients WHERE patient_name = 'Nusrat Jahan'), 800.00, 800.00, 'Pending');
