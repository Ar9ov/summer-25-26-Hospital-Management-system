-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 13, 2026 at 06:06 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hospital_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `specialization` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `user_id`, `specialization`, `phone`, `status`, `joined_at`) VALUES
(1, 4, 'Cardiology', '017000000', 'active', '2026-08-27 12:43:04'),
(2, 7, 'Diabetes', '0180000000', 'inactive', '2026-08-27 13:22:44'),
(3, 8, 'Heart', '01700000000', 'inactive', '2026-08-27 14:16:48'),
(4, 10, 'Medicine', '019888878', 'active', '2026-09-13 03:46:41'),
(5, 11, 'Eye', '01956677888', 'active', '2026-09-13 03:49:36');

-- --------------------------------------------------------

--
-- Table structure for table `revenue`
--

CREATE TABLE `revenue` (
  `id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `revenue`
--

INSERT INTO `revenue` (`id`, `amount`, `description`, `created_at`) VALUES
(1, 500.00, 'Consultation', '2026-08-27 13:05:28'),
(2, 1000.00, 'Test', '2026-08-27 13:05:59'),
(3, 500.00, 'doctor', '2026-09-13 03:29:29'),
(4, 1000.00, 'Report', '2026-09-13 03:47:30'),
(5, 700.00, 'Service Charge', '2026-09-13 03:50:14');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `doctor_id`, `rating`, `comment`, `created_at`) VALUES
(1, 1, 3, 3, 'very good', '2026-08-27 15:15:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'patient',
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `status`, `created_at`) VALUES
(1, 'test33', 'test33@gmail.com', '$2y$10$zMnSW05egRYuJogSHKWSg.pn.Sp1KhwCb8YJJHEXRq1VTKW9u73mq', 'patient', 'active', '2026-08-27 06:58:59'),
(2, 'a2', 'a2@gmail.com', '$2y$10$Yq7kG7AoO1MHv3HuqE0yxOrI7gd0aJYUUx2yctDps3FW0UG0gX5dq', 'patient', 'active', '2026-08-27 11:53:56'),
(3, 'admin', 'ad@gmail.com', '$2y$10$VjlyxElJjlpnkRFXqc.OGeDMA1sgPm9IqrPYqgWssRa/FX81sCja2', 'admin', 'active', '2026-08-27 12:13:16'),
(4, 'doc', 'doc@gmail.com', '$2y$10$8CXXZjJ4.dKpylQPkX0PX.b0mlbgcFyI2vyLQpFkBnzgO4H/kpRj6', 'patient', 'active', '2026-08-27 12:41:24'),
(5, 'a3', 'a3@gmail.com', '$2y$10$ZWHdxTCOJVpV2uSYsKhhmeSqqAdDm6dCOjATkACbE0A4qzhKnH2Da', 'patient', 'active', '2026-08-27 13:04:08'),
(6, 'a4', 'a4@gmail.com', '$2y$10$07kcsiDvRPhbo5D7v55T2udBKZdkMKinsN5X.ecrF1UUkfPVTGsJq', 'patient', 'active', '2026-08-27 13:04:23'),
(7, 'Dr. Ramim', 'ramim@gmail.com', '$2y$10$sw2BrM/woCtLpot6Y3vaaOUC7dJldcqCOGbZy0.lyEPKMKT0PJrqi', 'doctor', 'active', '2026-08-27 13:22:44'),
(8, 'Dr. Zero', 'zero@gmail.com', '$2y$10$4owHBFWrGudDSZFU.16oOO95VPp687BimNI8c/GwzBChsdWlnYCtm', 'doctor', 'active', '2026-08-27 14:16:48'),
(9, 'arnob', 'ad2@gmail.com', '$2y$10$dwmjKJPPzR8UXUvAIz/HOOGuGDU8LLU2J74QS6nASvZunV3etEDB6', 'admin', 'active', '2026-09-12 19:33:47'),
(10, 'Wahid', 'wahid@gmail.com', '$2y$10$qsLQHfEMajEivVVoLV66AupWO8yGyMlSTa2Z14/8V8RpDZH9ano1q', 'doctor', 'active', '2026-09-13 03:46:41'),
(11, 'Dr. Tamim', 'tamim@gmail.com', '$2y$10$Us.ESlWrqoC/AvUVH3PxjOUXN25mghDMZVylVy7nwv..CmveH/4fS', 'doctor', 'active', '2026-09-13 03:49:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `revenue`
--
ALTER TABLE `revenue`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `revenue`
--
ALTER TABLE `revenue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`);

-- =====================================================================
-- Doctor Panel additions (Member 2) -- appended onto the shared
-- `hospital_management` database created above by the Admin panel.
-- These tables reference the existing `users` table for doctor_id.
-- =====================================================================

--
-- Table structure for table `patients`
-- (Doctor's own medical-record entity -- separate from login accounts,
-- since the shared `users` table has no age/gender/blood group fields.
-- This is what satisfies the Doctor's required Create/Read/Update/
-- Delete/Search dashboard action.)
--
CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `full_name` varchar(120) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `blood_group` varchar(5) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `patients`
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_leaves`  (Feature: Emergency Leave)
--
CREATE TABLE `doctor_leaves` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `leave_from` date NOT NULL,
  `leave_to` date NOT NULL,
  `reason` varchar(500) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `doctor_leaves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`);

ALTER TABLE `doctor_leaves`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `doctor_leaves`
  ADD CONSTRAINT `doctor_leaves_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`  (Feature: Prescription Writer)
--
CREATE TABLE `prescriptions` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `diagnosis` varchar(500) NOT NULL,
  `notes` varchar(1000) DEFAULT NULL,
  `visit_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

ALTER TABLE `prescriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `prescriptions`
  ADD CONSTRAINT `prescriptions_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescriptions_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

-- --------------------------------------------------------

--
-- Table structure for table `prescription_medicines`
--
CREATE TABLE `prescription_medicines` (
  `id` int(11) NOT NULL,
  `prescription_id` int(11) NOT NULL,
  `medicine_name` varchar(150) NOT NULL,
  `dosage` varchar(60) NOT NULL,
  `frequency` varchar(60) NOT NULL,
  `duration` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `prescription_medicines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prescription_id` (`prescription_id`);

ALTER TABLE `prescription_medicines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `prescription_medicines`
  ADD CONSTRAINT `prescription_medicines_ibfk_1` FOREIGN KEY (`prescription_id`) REFERENCES `prescriptions` (`id`) ON DELETE CASCADE;

-- --------------------------------------------------------

--
-- Table structure for table `patient_visit_notes`  (Feature: Patient History)
--
CREATE TABLE `patient_visit_notes` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `visit_date` date NOT NULL,
  `symptoms` varchar(500) DEFAULT NULL,
  `treatment_notes` varchar(1000) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `patient_visit_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

ALTER TABLE `patient_visit_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `patient_visit_notes`
  ADD CONSTRAINT `patient_visit_notes_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `patient_visit_notes_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;


COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
