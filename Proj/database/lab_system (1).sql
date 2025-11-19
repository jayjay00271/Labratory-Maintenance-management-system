-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 18, 2025 at 07:54 AM
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
-- Database: `lab_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `lab_equipment`
--

CREATE TABLE `lab_equipment` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `status` enum('Working','Needs Repair','Out of Service') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_equipment`
--

INSERT INTO `lab_equipment` (`id`, `name`, `type`, `status`, `created_at`) VALUES
(15, 'we', 'we', 'Working', '2025-11-13 07:09:44'),
(16, 'we', 'we', 'Working', '2025-11-13 07:09:49'),
(17, 'we', 'we', 'Needs Repair', '2025-11-13 07:09:52'),
(18, 'we', 'we', 'Working', '2025-11-13 07:17:56'),
(19, 'we', 'we', 'Working', '2025-11-13 07:17:58'),
(20, 'we', 'we', 'Working', '2025-11-13 07:18:05'),
(21, 'we', 'we', 'Working', '2025-11-13 07:18:11'),
(22, 'we', 'we', 'Working', '2025-11-13 07:20:00'),
(23, 'we', 'we', 'Working', '2025-11-13 07:21:07'),
(24, 'we', 'we', 'Working', '2025-11-13 07:21:13'),
(25, 'we', 'we', 'Working', '2025-11-13 07:21:53'),
(26, 'we', 'we', 'Working', '2025-11-13 07:22:14'),
(27, 'we', 'we', 'Working', '2025-11-13 07:22:21'),
(32, 'we', 'Mouse', 'Needs Repair', '2025-11-14 10:52:56'),
(33, 'RAMOJ', 'we', 'Working', '2025-11-14 11:15:31'),
(34, 'RAMO2', 'Mouse', 'Out of Service', '2025-11-14 11:15:35'),
(35, 'marita', 'Mouse', 'Out of Service', '2025-11-14 12:26:25');

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_requests`
--

CREATE TABLE `maintenance_requests` (
  `id` int(11) NOT NULL,
  `staff_name` varchar(100) NOT NULL,
  `equipment_name` varchar(100) NOT NULL,
  `issue_description` text NOT NULL,
  `status` enum('Pending','In Progress','Resolved') DEFAULT 'Pending',
  `request_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maintenance_requests`
--

INSERT INTO `maintenance_requests` (`id`, `staff_name`, `equipment_name`, `issue_description`, `status`, `request_date`) VALUES
(1, 'we', 'we', 'we', 'In Progress', '2025-11-13 06:15:37'),
(18, 'staff', 'we', 'sdw', 'Pending', '2025-11-14 11:33:47'),
(19, 'admin', 'Keyboard', 'Keys missing', 'In Progress', '2025-11-14 12:27:37'),
(20, 'wers', 'Keyboard', 'no money', 'Pending', '2025-11-14 12:32:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `role` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', '123', 'admin', '2025-11-14 10:57:29'),
(2, 'staff', '123', 'staff', '2025-11-14 10:57:29'),
(3, 'jj', '123', 'staff', '2025-11-14 10:59:59'),
(4, 'jj', '123', 'staff', '2025-11-14 11:00:01'),
(12, 'wweers', '$2y$10$flJciW4e3oegbbnSeuB2a.D7smL/kZWtsmBtRrTpdmBW3W3xVphHa', 'staff', '2025-11-14 12:31:41'),
(13, 'wers', '$2y$10$KkaGuYSYBki.F1i6HNngcu3qM5YmuCxPCkzpk5YIa66s/kToeRJw6', 'staff', '2025-11-14 12:31:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lab_equipment`
--
ALTER TABLE `lab_equipment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lab_equipment`
--
ALTER TABLE `lab_equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
