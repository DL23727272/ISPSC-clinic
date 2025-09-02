-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 02, 2025 at 04:09 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `isps_clinica`
--

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(10) UNSIGNED NOT NULL,
  `employee_id` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `suffix` varchar(10) DEFAULT 'N/A',
  `age` int(11) DEFAULT NULL,
  `campus` varchar(50) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `sex` enum('Male','Female') DEFAULT NULL,
  `permanent_address` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `civil_status` enum('Single','Married','Divorced','Widowed') DEFAULT NULL,
  `religion` varchar(50) DEFAULT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `contact_address` varchar(255) DEFAULT NULL,
  `contact_no` varchar(20) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `employee_health_info`
--

CREATE TABLE `employee_health_info` (
  `id` int(11) NOT NULL,
  `employee_id` varchar(20) DEFAULT NULL,
  `blood_type` varchar(10) DEFAULT NULL,
  `allergy_alert` varchar(255) DEFAULT NULL,
  `disability` varchar(255) DEFAULT NULL,
  `chicken_pox` tinyint(1) DEFAULT 0,
  `hypertension` tinyint(1) DEFAULT 0,
  `thyroid_disease` tinyint(1) DEFAULT 0,
  `mumps` tinyint(1) DEFAULT 0,
  `diabetes` tinyint(1) DEFAULT 0,
  `heart_disease` tinyint(1) DEFAULT 0,
  `measles` tinyint(1) DEFAULT 0,
  `asthma` tinyint(1) DEFAULT 0,
  `blood_transfusion` tinyint(1) DEFAULT 0,
  `tuberculosis` tinyint(1) DEFAULT 0,
  `peptic_ulcer` tinyint(1) DEFAULT 0,
  `cancer` tinyint(1) DEFAULT 0,
  `cancer_type` varchar(255) DEFAULT NULL,
  `epilepsy` tinyint(1) DEFAULT 0,
  `hepatitis` tinyint(1) DEFAULT 0,
  `hepatitis_type` varchar(255) DEFAULT NULL,
  `anti_coagulants` tinyint(1) DEFAULT 0,
  `bone_fracture` tinyint(1) DEFAULT 0,
  `hospitalization_date` date DEFAULT NULL,
  `hospitalization_diagnosis` varchar(255) DEFAULT NULL,
  `hospitalization_hospital` varchar(255) DEFAULT NULL,
  `surgery` varchar(255) DEFAULT NULL,
  `accidents` varchar(255) DEFAULT NULL,
  `fam_hypertension` tinyint(1) DEFAULT 0,
  `fam_thyroid` tinyint(1) DEFAULT 0,
  `fam_autoimmune` tinyint(1) DEFAULT 0,
  `fam_others` varchar(255) DEFAULT NULL,
  `fam_diabetes` tinyint(1) DEFAULT 0,
  `fam_cancer` tinyint(1) DEFAULT 0,
  `fam_cancer_form` varchar(255) DEFAULT NULL,
  `fam_asthma` tinyint(1) DEFAULT 0,
  `fam_asthma_form` varchar(255) DEFAULT NULL,
  `fam_heart` tinyint(1) DEFAULT 0,
  `mmr_date` date DEFAULT NULL,
  `hepatitis_vaccine_date` date DEFAULT NULL,
  `flu_vaccine_date` date DEFAULT NULL,
  `anti_rabies_date` date DEFAULT NULL,
  `anti_tetanus_date` date DEFAULT NULL,
  `ppv23_date` date DEFAULT NULL,
  `covid_1st_dose` varchar(50) DEFAULT NULL,
  `covid_1st_date` date DEFAULT NULL,
  `covid_2nd_dose` varchar(50) DEFAULT NULL,
  `covid_2nd_date` date DEFAULT NULL,
  `covid_1st_booster` varchar(50) DEFAULT NULL,
  `covid_1st_booster_date` date DEFAULT NULL,
  `covid_2nd_booster` varchar(50) DEFAULT NULL,
  `covid_2nd_booster_date` date DEFAULT NULL,
  `smoker` tinyint(1) DEFAULT 0,
  `sticks_per_day` varchar(50) DEFAULT NULL,
  `years_smoking` varchar(50) DEFAULT NULL,
  `pack_years` varchar(50) DEFAULT NULL,
  `alcohol` tinyint(1) DEFAULT 0,
  `alcohol_type` varchar(50) DEFAULT NULL,
  `bottles_per_day` varchar(50) DEFAULT NULL,
  `alcohol_frequency` varchar(50) DEFAULT NULL,
  `illicit_drugs` tinyint(1) DEFAULT 0,
  `drug_type` varchar(50) DEFAULT NULL,
  `drug_quantity` varchar(50) DEFAULT NULL,
  `drug_frequency` varchar(50) DEFAULT NULL,
  `no_pregnancy` varchar(50) DEFAULT NULL,
  `no_alive` varchar(50) DEFAULT NULL,
  `no_stillbirth_abortion` varchar(50) DEFAULT NULL,
  `lmp` date DEFAULT NULL,
  `menarche` date DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `amount` varchar(50) DEFAULT NULL,
  `menstrual_interval` varchar(50) DEFAULT NULL,
  `symptoms` varchar(255) DEFAULT NULL,
  `gyne_pathology` varchar(255) DEFAULT NULL,
  `last_dental_visit` date DEFAULT NULL,
  `dental_procedure` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) DEFAULT NULL,
  `campus` varchar(50) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `course` varchar(100) DEFAULT NULL,
  `year` int(1) DEFAULT NULL,
  `major` varchar(50) DEFAULT NULL,
  `semester` varchar(50) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `course_year` varchar(100) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `sex` enum('Male','Female') DEFAULT NULL,
  `permanent_address` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `civil_status` varchar(50) DEFAULT NULL,
  `religion` varchar(100) DEFAULT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `contact_address` varchar(255) DEFAULT NULL,
  `contact_no` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `suffix` varchar(10) DEFAULT 'N/A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `student_health_info`
--

CREATE TABLE `student_health_info` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) DEFAULT NULL,
  `blood_type` varchar(10) DEFAULT NULL,
  `allergy_alert` varchar(255) DEFAULT NULL,
  `disability` varchar(255) DEFAULT NULL,
  `chicken_pox` tinyint(1) DEFAULT 0,
  `hypertension` tinyint(1) DEFAULT 0,
  `thyroid_disease` tinyint(1) DEFAULT 0,
  `mumps` tinyint(1) DEFAULT 0,
  `diabetes` tinyint(1) DEFAULT 0,
  `heart_disease` tinyint(1) DEFAULT 0,
  `measles` tinyint(1) DEFAULT 0,
  `asthma` tinyint(1) DEFAULT 0,
  `blood_transfusion` tinyint(1) DEFAULT 0,
  `tuberculosis` tinyint(1) DEFAULT 0,
  `peptic_ulcer` tinyint(1) DEFAULT 0,
  `cancer` tinyint(1) DEFAULT 0,
  `cancer_type` varchar(255) DEFAULT NULL,
  `epilepsy` tinyint(1) DEFAULT 0,
  `hepatitis` tinyint(1) DEFAULT 0,
  `hepatitis_type` varchar(255) DEFAULT NULL,
  `anti_coagulants` tinyint(1) DEFAULT 0,
  `bone_fracture` tinyint(1) DEFAULT 0,
  `hospitalization_date` date DEFAULT NULL,
  `hospitalization_diagnosis` varchar(255) DEFAULT NULL,
  `hospitalization_hospital` varchar(255) DEFAULT NULL,
  `surgery` varchar(255) DEFAULT NULL,
  `accidents` varchar(255) DEFAULT NULL,
  `fam_hypertension` tinyint(1) DEFAULT 0,
  `fam_thyroid` tinyint(1) DEFAULT 0,
  `fam_autoimmune` tinyint(1) DEFAULT 0,
  `fam_others` varchar(255) DEFAULT NULL,
  `fam_diabetes` tinyint(1) DEFAULT 0,
  `fam_cancer` tinyint(1) DEFAULT 0,
  `fam_cancer_form` varchar(255) DEFAULT NULL,
  `fam_asthma` tinyint(1) DEFAULT 0,
  `fam_asthma_form` varchar(255) DEFAULT NULL,
  `fam_heart` tinyint(1) DEFAULT 0,
  `mmr_date` date DEFAULT NULL,
  `hepatitis_vaccine_date` date DEFAULT NULL,
  `flu_vaccine_date` date DEFAULT NULL,
  `anti_rabies_date` date DEFAULT NULL,
  `anti_tetanus_date` date DEFAULT NULL,
  `ppv23_date` date DEFAULT NULL,
  `covid_1st_dose` varchar(50) DEFAULT NULL,
  `covid_1st_date` date DEFAULT NULL,
  `covid_2nd_dose` varchar(50) DEFAULT NULL,
  `covid_2nd_date` date DEFAULT NULL,
  `covid_1st_booster` varchar(50) DEFAULT NULL,
  `covid_1st_booster_date` date DEFAULT NULL,
  `covid_2nd_booster` varchar(50) DEFAULT NULL,
  `covid_2nd_booster_date` date DEFAULT NULL,
  `smoker` tinyint(1) DEFAULT 0,
  `sticks_per_day` varchar(50) DEFAULT NULL,
  `years_smoking` varchar(50) DEFAULT NULL,
  `pack_years` varchar(50) DEFAULT NULL,
  `alcohol` tinyint(1) DEFAULT 0,
  `alcohol_type` varchar(50) DEFAULT NULL,
  `bottles_per_day` varchar(50) DEFAULT NULL,
  `alcohol_frequency` varchar(50) DEFAULT NULL,
  `illicit_drugs` tinyint(1) DEFAULT 0,
  `drug_type` varchar(50) DEFAULT NULL,
  `drug_quantity` varchar(50) DEFAULT NULL,
  `drug_frequency` varchar(50) DEFAULT NULL,
  `no_pregnancy` varchar(50) DEFAULT NULL,
  `no_alive` varchar(50) DEFAULT NULL,
  `no_stillbirth_abortion` varchar(50) DEFAULT NULL,
  `lmp` date DEFAULT NULL,
  `menarche` date DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `amount` varchar(50) DEFAULT NULL,
  `menstrual_interval` varchar(50) DEFAULT NULL,
  `symptoms` varchar(255) DEFAULT NULL,
  `gyne_pathology` varchar(255) DEFAULT NULL,
  `last_dental_visit` date DEFAULT NULL,
  `dental_procedure` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `employee_id` varchar(50) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','super_admin') NOT NULL DEFAULT 'admin',
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `campus` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` datetime DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `employee_id`, `username`, `email`, `password_hash`, `role`, `first_name`, `last_name`, `campus`, `created_at`, `last_login`, `status`) VALUES
(32, 'EMP001', 'admin_sm', 'admin_sm@gmail.com', '$2y$10$EGWvYb/.VG4zsZTHo2UKDeFeUOS7F9Emp96bwSLWESMPj.XQC3Ix6', 'admin', NULL, NULL, 'SANTA MARIA', '2025-08-27 06:00:20', '2025-08-31 14:01:20', 'active'),
(33, 'EMP002', 'admin_nv', 'admin_nv@gmail.com', '$2y$10$EGWvYb/.VG4zsZTHo2UKDeFeUOS7F9Emp96bwSLWESMPj.XQC3Ix6', 'admin', NULL, NULL, 'NARVACAN', '2025-08-27 06:00:20', NULL, 'active'),
(34, 'EMP003', 'admin_cd', 'admin_cd@gmail.com', '$2y$10$EGWvYb/.VG4zsZTHo2UKDeFeUOS7F9Emp96bwSLWESMPj.XQC3Ix6', 'admin', NULL, NULL, 'CANDON', '2025-08-27 06:00:20', NULL, 'active'),
(35, 'EMP004', 'admin_mc', 'admin_mc@gmail.com', '$2y$10$EGWvYb/.VG4zsZTHo2UKDeFeUOS7F9Emp96bwSLWESMPj.XQC3Ix6', 'admin', NULL, NULL, 'MAIN CAMPUS', '2025-08-27 06:00:20', NULL, 'active'),
(36, 'EMP005', 'admin_tg', 'admin_tg@gmail.com', '$2y$10$EGWvYb/.VG4zsZTHo2UKDeFeUOS7F9Emp96bwSLWESMPj.XQC3Ix6', 'admin', NULL, NULL, 'TAGUDIN', '2025-08-27 06:00:20', NULL, 'active'),
(37, 'EMP006', 'admin_cv', 'admin_cv@gmail.com', '$2y$10$EGWvYb/.VG4zsZTHo2UKDeFeUOS7F9Emp96bwSLWESMPj.XQC3Ix6', 'admin', NULL, NULL, 'CERVANTES', '2025-08-27 06:00:20', NULL, 'active'),
(38, 'EMP007', 'admin_st', 'admin_st@gmail.com', '$2y$10$EGWvYb/.VG4zsZTHo2UKDeFeUOS7F9Emp96bwSLWESMPj.XQC3Ix6', 'admin', NULL, NULL, 'SANTIAGO', '2025-08-27 06:00:20', '2025-08-27 14:18:11', 'active'),
(39, 'EMP000', 'superadmin', 'superadmin@gmail.com', '$2y$10$uzV3HKVaL/h7V6AoZ2u0b.MbGfM8IafYwbzbP7.XSXJoXQNBb26Na', 'super_admin', NULL, NULL, 'ALL', '2025-08-27 06:00:20', '2025-09-01 12:05:52', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `employee_health_info`
--
ALTER TABLE `employee_health_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `student_health_info`
--
ALTER TABLE `student_health_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `student_id` (`employee_id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_health_info`
--
ALTER TABLE `employee_health_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_health_info`
--
ALTER TABLE `student_health_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `student_health_info`
--
ALTER TABLE `student_health_info`
  ADD CONSTRAINT `fk_student_health_info_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
