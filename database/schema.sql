-- =====================================================================
-- Doctor Panel Module - Database Schema
-- Project: summer-25-26-hospital-management-system (adjust name as needed)
-- Engine: MySQL (procedural mysqli, prepared statements used in app code)
-- =====================================================================

CREATE DATABASE IF NOT EXISTS hospital_management_system
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hospital_management_system;

-- ---------------------------------------------------------------------
-- Shared users table (all 4 roles live here: admin, doctor, patient,
-- receptionist/visitor -- coordinate exact role names with your team so
-- everyone points at the same table).
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','doctor','patient','receptionist') NOT NULL,
  phone VARCHAR(30) DEFAULT NULL,
  specialization VARCHAR(120) DEFAULT NULL, -- used for doctor role only
  status ENUM('active','suspended') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Patients (medical record entity). Doctors perform Create/Read/Update/
-- Delete/Search on this table -- this satisfies the required CRUD+Search
-- on the doctor's own dashboard.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS patients (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  age INT NOT NULL,
  gender ENUM('male','female','other') NOT NULL,
  phone VARCHAR(30) DEFAULT NULL,
  address VARCHAR(255) DEFAULT NULL,
  blood_group VARCHAR(5) DEFAULT NULL,
  created_by INT NOT NULL,              -- doctor user id who added the record
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Feature 1 (unique to Doctor): Emergency Leave
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS doctor_leaves (
  id INT AUTO_INCREMENT PRIMARY KEY,
  doctor_id INT NOT NULL,
  leave_from DATE NOT NULL,
  leave_to DATE NOT NULL,
  reason VARCHAR(500) NOT NULL,
  status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Feature 2 (unique to Doctor): Prescription Writer
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS prescriptions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  patient_id INT NOT NULL,
  doctor_id INT NOT NULL,
  diagnosis VARCHAR(500) NOT NULL,
  notes VARCHAR(1000) DEFAULT NULL,
  visit_date DATE NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
  FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS prescription_medicines (
  id INT AUTO_INCREMENT PRIMARY KEY,
  prescription_id INT NOT NULL,
  medicine_name VARCHAR(150) NOT NULL,
  dosage VARCHAR(60) NOT NULL,        -- e.g. "500mg"
  frequency VARCHAR(60) NOT NULL,     -- e.g. "2x daily"
  duration VARCHAR(60) NOT NULL,      -- e.g. "5 days"
  FOREIGN KEY (prescription_id) REFERENCES prescriptions(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Feature 3 (unique to Doctor): Patient History
-- Read-only, chronological view built from prescriptions, plus optional
-- free-form visit notes a doctor can log without writing a prescription.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS patient_visit_notes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  patient_id INT NOT NULL,
  doctor_id INT NOT NULL,
  visit_date DATE NOT NULL,
  symptoms VARCHAR(500) DEFAULT NULL,
  treatment_notes VARCHAR(1000) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
  FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Seed: one doctor account for testing (password = "Doctor@123")
-- Replace password_hash value by running:
--   php -r "echo password_hash('Doctor@123', PASSWORD_DEFAULT);"
-- and pasting the result below.
-- ---------------------------------------------------------------------
-- INSERT INTO users (full_name, email, password_hash, role, specialization)
-- VALUES ('Dr. Jane Doe', 'doctor@example.com', '$2y$10$REPLACE_WITH_REAL_HASH', 'doctor', 'General Physician');
