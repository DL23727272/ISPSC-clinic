-- Create the database
CREATE DATABASE IF NOT EXISTS `isps_clinica` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `isps_clinica`;

-- Users table (students, employees, admin)
CREATE TABLE `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `email` VARCHAR(150) DEFAULT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('student','employee','admin') NOT NULL DEFAULT 'student',
  `first_name` VARCHAR(50) DEFAULT NULL,
  `last_name` VARCHAR(50) DEFAULT NULL,
  `department` VARCHAR(50) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `last_login` DATETIME DEFAULT NULL,
  `status` ENUM('active','inactive') DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Medical forms table
CREATE TABLE `medical_forms` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `form_type` VARCHAR(50) NOT NULL,
  `form_data` TEXT,
  `status` ENUM('pending','processed','rejected') DEFAULT 'pending',
  `submitted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `processed_at` DATETIME DEFAULT NULL,
  `processed_by` INT UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`processed_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Form types table
CREATE TABLE `form_types` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `fields` JSON NOT NULL,
  `required_role` ENUM('student','employee','admin') DEFAULT 'student',
  `is_active` BOOLEAN DEFAULT TRUE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Audit log table
CREATE TABLE `audit_log` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED DEFAULT NULL,
  `action` VARCHAR(50) NOT NULL,
  `table_name` VARCHAR(50) NOT NULL,
  `record_id` INT UNSIGNED DEFAULT NULL,
  `old_values` TEXT,
  `new_values` TEXT,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Initial Data Population

-- Create default admin account (password: Admin@123)
INSERT INTO `users` (`username`, `email`, `password_hash`, `role`, `first_name`, `last_name`, `status`) 
VALUES (
  'admin', 
  'admin@ispsc.edu.ph', 
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
  'admin', 
  'System', 
  'Administrator', 
  'active'
);

-- Create sample employee (password: Employee@123)
INSERT INTO `users` (`username`, `email`, `password_hash`, `role`, `first_name`, `last_name`, `department`, `status`) 
VALUES (
  'emp001', 
  'employee@ispsc.edu.ph', 
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
  'employee', 
  'Juan', 
  'Dela Cruz', 
  'Clinic', 
  'active'
);

-- Create sample form types
INSERT INTO `form_types` (`name`, `description`, `fields`, `required_role`) 
VALUES 
(
  'Health Declaration Form', 
  'Daily health status declaration', 
  '[
    {"name": "temperature", "type": "number", "label": "Body Temperature (°C)", "required": true},
    {"name": "symptoms", "type": "checkbox", "label": "Symptoms Experienced", "options": ["Fever", "Cough", "Shortness of breath", "Fatigue"], "required": false},
    {"name": "contact_history", "type": "radio", "label": "Had contact with COVID-19 case?", "options": ["Yes", "No"], "required": true}
  ]',
  'student'
),
(
  'Medical Consultation Form', 
  'Form for medical consultations', 
  '[
    {"name": "complaint", "type": "textarea", "label": "Chief Complaint", "required": true},
    {"name": "history", "type": "textarea", "label": "Medical History", "required": false},
    {"name": "allergies", "type": "text", "label": "Known Allergies", "required": false}
  ]',
  'employee'
);

-- Create sample medical form
INSERT INTO `medical_forms` (`user_id`, `form_type`, `form_data`, `status`) 
VALUES (
  2, 
  'Medical Consultation Form', 
  '{
    "complaint": "Headache and fever since yesterday",
    "history": "No significant medical history",
    "allergies": "None"
  }',
  'pending'
);