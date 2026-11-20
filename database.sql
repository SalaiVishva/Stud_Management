-- ============================================================
-- Student Management System Database Schema
-- Database Name: student_db
-- ============================================================

-- 1. Create database if it doesn't already exist
CREATE DATABASE IF NOT EXISTS `student_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 2. Use the database
USE `student_db`;

-- 3. Create the 'students' table
CREATE TABLE IF NOT EXISTS `students` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `reg_no` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `department` VARCHAR(100) NOT NULL,
    `year` VARCHAR(20) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Insert sample data for quick testing
INSERT INTO `students` (`name`, `reg_no`, `email`, `phone`, `department`, `year`) VALUES
('John Doe', 'REG2024001', 'john.doe@example.com', '9876543210', 'Computer Science', '3rd Year'),
('Jane Smith', 'REG2024002', 'jane.smith@example.com', '9876543211', 'Information Technology', '2nd Year'),
('Michael Brown', 'REG2024003', 'michael.b@example.com', '9876543212', 'Mechanical Engineering', '4th Year'),
('Emily Davis', 'REG2024004', 'emily.d@example.com', '9876543213', 'Electrical Engineering', '1st Year');
