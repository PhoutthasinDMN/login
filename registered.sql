-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 16, 2025 at 10:58 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `registered`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `action` text NOT NULL,
  `level` enum('info','warning','error') DEFAULT 'info',
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `page` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `level`, `ip_address`, `user_agent`, `page`, `created_at`) VALUES
(1, 10, 'ผู้ใช้ admin ออกจากระบบ', 'info', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '/login/logout.php', '2025-05-16 10:00:36'),
(2, 10, 'ผู้ใช้ admin เข้าสู่ระบบ', 'info', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '/login/login.php', '2025-05-16 10:02:02'),
(3, 10, 'ผู้ใช้เข้าถึงหน้า Dashboard', 'info', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '/login/dashboard.php', '2025-05-16 10:02:02'),
(4, 10, 'ผู้ใช้เข้าถึงหน้า Dashboard', 'info', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '/login/dashboard.php', '2025-05-16 10:02:07'),
(5, 10, 'ผู้ใช้เข้าถึงหน้า Dashboard', 'info', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '/login/dashboard.php', '2025-05-16 10:03:25'),
(6, 10, 'ผู้ใช้เข้าถึงหน้า Dashboard', 'info', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '/login/dashboard.php', '2025-05-16 10:03:25'),
(7, 10, 'ผู้ใช้เข้าถึงหน้า Dashboard', 'info', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '/login/dashboard.php', '2025-05-16 10:03:25'),
(8, 10, 'ผู้ใช้เข้าถึงหน้า Dashboard', 'info', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '/login/dashboard.php', '2025-05-16 10:03:25'),
(9, 10, 'ผู้ใช้เข้าถึงหน้ารายการผู้ป่วย', 'info', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '/login/patients.php', '2025-05-16 10:03:26'),
(10, 10, 'เพิ่มผู้ป่วยใหม่: mino (HN: 0001)', 'info', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '/login/patients.php', '2025-05-16 10:04:10'),
(11, 10, 'ผู้ใช้เข้าถึงหน้ารายการผู้ป่วย', 'info', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '/login/patients.php', '2025-05-16 10:04:10'),
(12, 10, 'ผู้ใช้เข้าถึงหน้ารายการผู้ป่วย', 'info', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '/login/patients.php', '2025-05-16 10:16:13');

-- --------------------------------------------------------

--
-- Table structure for table `lab_categories`
--

CREATE TABLE `lab_categories` (
  `id` int NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `order_number` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lab_items`
--

CREATE TABLE `lab_items` (
  `id` int NOT NULL,
  `category_id` int NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `item_code` varchar(20) DEFAULT NULL,
  `unit` varchar(20) DEFAULT NULL,
  `normal_range` varchar(50) DEFAULT NULL,
  `order_number` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lab_item_fields`
--

CREATE TABLE `lab_item_fields` (
  `id` int NOT NULL,
  `item_id` int NOT NULL,
  `field_name` varchar(50) NOT NULL,
  `field_label` varchar(100) NOT NULL,
  `field_type` enum('text','number','select','radio','checkbox') NOT NULL DEFAULT 'text',
  `unit` varchar(20) DEFAULT NULL,
  `normal_range` varchar(100) DEFAULT NULL,
  `options` text COMMENT 'JSON format for select/radio/checkbox options',
  `is_required` tinyint(1) DEFAULT '0',
  `order_number` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lab_item_fields`
--

INSERT INTO `lab_item_fields` (`id`, `item_id`, `field_name`, `field_label`, `field_type`, `unit`, `normal_range`, `options`, `is_required`, `order_number`) VALUES
(1, 1, 'wbc', 'WBC', 'number', 'x10^3/μL', '4.0-10.0', NULL, 1, 1),
(2, 1, 'rbc', 'RBC', 'number', 'x10^6/μL', '4.2-5.4', NULL, 1, 2),
(3, 1, 'hgb', 'Hemoglobin', 'number', 'g/dL', '13.0-17.0', NULL, 1, 3),
(4, 1, 'hct', 'Hematocrit', 'number', '%', '40.0-50.0', NULL, 1, 4),
(5, 1, 'plt', 'Platelets', 'number', 'x10^3/μL', '150-450', NULL, 1, 5),
(6, 2, 'abo', 'ABO Group', 'select', NULL, NULL, '[\"A\",\"B\",\"AB\",\"O\"]', 1, 1),
(7, 2, 'rh', 'Rh Factor', 'select', NULL, NULL, '[\"Positive\",\"Negative\"]', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `lab_results`
--

CREATE TABLE `lab_results` (
  `id` int NOT NULL,
  `patient_id` int NOT NULL,
  `lab_date` date NOT NULL,
  `lab_type` varchar(100) NOT NULL,
  `lab_details` text,
  `blood_pressure` varchar(20) DEFAULT NULL,
  `pulse` decimal(5,2) DEFAULT NULL,
  `temperature` decimal(5,2) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `bmi` decimal(5,2) DEFAULT NULL,
  `glucose` decimal(5,2) DEFAULT NULL,
  `cholesterol` decimal(5,2) DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `note` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lab_results`
--

INSERT INTO `lab_results` (`id`, `patient_id`, `lab_date`, `lab_type`, `lab_details`, `blood_pressure`, `pulse`, `temperature`, `weight`, `height`, `bmi`, `glucose`, `cholesterol`, `created_by`, `note`, `created_at`) VALUES
(1, 1, '2025-05-16', 'ตรวจติดตามโรคประจำตัว', 'test1', '120/80', 120.00, 35.00, 86.00, 178.00, 27.14, 120.00, 128.00, 10, 'test1', '2025-05-16 10:57:06');

-- --------------------------------------------------------

--
-- Table structure for table `lab_result_details`
--

CREATE TABLE `lab_result_details` (
  `id` int NOT NULL,
  `lab_result_id` int NOT NULL,
  `lab_item_id` int NOT NULL,
  `result_value` varchar(50) NOT NULL,
  `is_abnormal` tinyint(1) DEFAULT '0',
  `note` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int NOT NULL,
  `hn` varchar(20) NOT NULL,
  `patient_name` varchar(100) NOT NULL,
  `age` int NOT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `address` text,
  `phone` varchar(20) DEFAULT NULL,
  `diagnosis` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `hn`, `patient_name`, `age`, `gender`, `address`, `phone`, `diagnosis`, `created_at`) VALUES
(1, '0001', 'mino', 31, 'ชาย', 'โนนค้อใต้1', '02078888789', 'TB', '2025-05-16 10:04:10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reg_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `reg_date`) VALUES
(10, 'admin', 'mino@gmail.com', '$2y$10$L6.JniQWdpE/sd6QfPlWPuuEiTEjQRYKJZ8WpWmD2Y2zBaRUFclEi', '2025-05-16 13:34:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `lab_categories`
--
ALTER TABLE `lab_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lab_items`
--
ALTER TABLE `lab_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `lab_item_fields`
--
ALTER TABLE `lab_item_fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `lab_results`
--
ALTER TABLE `lab_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `lab_result_details`
--
ALTER TABLE `lab_result_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lab_result_id` (`lab_result_id`),
  ADD KEY `lab_item_id` (`lab_item_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hn` (`hn`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `lab_categories`
--
ALTER TABLE `lab_categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lab_items`
--
ALTER TABLE `lab_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lab_item_fields`
--
ALTER TABLE `lab_item_fields`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `lab_results`
--
ALTER TABLE `lab_results`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lab_result_details`
--
ALTER TABLE `lab_result_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `lab_items`
--
ALTER TABLE `lab_items`
  ADD CONSTRAINT `lab_items_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `lab_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lab_results`
--
ALTER TABLE `lab_results`
  ADD CONSTRAINT `lab_results_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lab_result_details`
--
ALTER TABLE `lab_result_details`
  ADD CONSTRAINT `lab_result_details_ibfk_1` FOREIGN KEY (`lab_result_id`) REFERENCES `lab_results` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lab_result_details_ibfk_2` FOREIGN KEY (`lab_item_id`) REFERENCES `lab_items` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
