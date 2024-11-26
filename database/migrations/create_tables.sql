-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2024 at 01:51 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `excitel`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Monthly'),
(68, 'Quarterly'),
(53, 'Weekly');

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `id` int(11) NOT NULL,
  `external_id` varchar(255) NOT NULL,
  `guid` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `type` enum('Lan','Fiber') DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`id`, `external_id`, `guid`, `name`, `status`, `price`, `type`, `category_id`, `deleted_at`, `updated_at`, `created_at`) VALUES
(1, '12333456', 'abc123', 'Test 1', 'Active', '100.00', 'Fiber', 1, NULL, '2024-11-25 12:55:12', '2024-11-22 18:21:09'),
(2, '1233345633', 'abc123', 'Sample Plan test', 'Active', '100.00', 'Fiber', 1, '2024-11-25 12:55:12', '2024-11-25 12:55:12', '2024-11-22 18:22:14'),
(3, '123334633', 'abc123', 'Test 2', 'Inactive', '100.00', 'Fiber', 53, '2024-11-25 13:06:21', '2024-11-25 13:06:21', '2024-11-25 11:24:07'),
(7, '1233345622', 'abc123dsadsa', 'Test 1', 'Active', '100.00', 'Fiber', 68, NULL, '2024-11-25 13:08:46', '2024-11-25 13:08:46');

-- --------------------------------------------------------

--
-- Table structure for table `plan_tags`
--

CREATE TABLE `plan_tags` (
  `plan_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `plan_tags`
--

INSERT INTO `plan_tags` (`plan_id`, `tag_id`) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 2),
(3, 1),
(3, 2),
(7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`) VALUES
(1, 'tag1'),
(2, 'tag2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `external_id` (`external_id`);

--
-- Indexes for table `plan_tags`
--
ALTER TABLE `plan_tags`
  ADD PRIMARY KEY (`plan_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `plan_tags`
--
ALTER TABLE `plan_tags`
  ADD CONSTRAINT `plan_tags_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`),
  ADD CONSTRAINT `plan_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
