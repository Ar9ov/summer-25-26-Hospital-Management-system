CREATE DATABASE IF NOT EXISTS receptionist_billing;
USE receptionist_billing;
CREATE TABLE IF NOT EXISTS patients (
 id INT AUTO_INCREMENT PRIMARY KEY,
 patient_name VARCHAR(100) NOT NULL,
 phone VARCHAR(20) NOT NULL,
 age INT NOT NULL,
 gender ENUM('Male','Female','Other') NOT NULL,
 wheelchair TINYINT(1) NOT NULL DEFAULT 0,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS invoices (
 id INT AUTO_INCREMENT PRIMARY KEY,
 invoice_no VARCHAR(30) UNIQUE NOT NULL,
 patient_id INT NOT NULL,
 consultation_fee DECIMAL(10,2) NOT NULL,
 total_bill DECIMAL(10,2) NOT NULL,
 payment_status ENUM('Paid','Pending') NOT NULL DEFAULT 'Pending',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE
);
