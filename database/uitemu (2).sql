-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 15, 2026 at 12:45 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uitemu`
--

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `found_item_id` int UNSIGNED DEFAULT NULL,
  `certificate_no` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT 'Certificate of Appreciation',
  `description` text,
  `issue_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Issued',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `certificates`
--

INSERT INTO `certificates` (`id`, `user_id`, `found_item_id`, `certificate_no`, `title`, `description`, `issue_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, 3, 'CERT-20260619-001', 'Certificate of Appreciation', NULL, '2026-06-13', 'Issued', '2026-06-13 10:17:49', '2026-06-19 11:47:38');

-- --------------------------------------------------------

--
-- Table structure for table `claims`
--

CREATE TABLE `claims` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `found_item_id` int UNSIGNED DEFAULT NULL,
  `lost_item_id` int UNSIGNED DEFAULT NULL,
  `claim_reason` text,
  `lost_location` varchar(255) DEFAULT NULL,
  `lost_date` date DEFAULT NULL,
  `proof_image` varchar(255) DEFAULT NULL,
  `claim_status` varchar(255) DEFAULT 'Pending',
  `admin_notes` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `handover_status` varchar(50) NOT NULL DEFAULT 'Not Arranged',
  `meeting_location` varchar(255) DEFAULT NULL,
  `meeting_datetime` datetime DEFAULT NULL,
  `pickup_code` varchar(50) DEFAULT NULL,
  `finder_confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `claimant_confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `handover_notes` text,
  `handed_over_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `claims`
--

INSERT INTO `claims` (`id`, `user_id`, `found_item_id`, `lost_item_id`, `claim_reason`, `lost_location`, `lost_date`, `proof_image`, `claim_status`, `admin_notes`, `created_at`, `updated_at`, `handover_status`, `meeting_location`, `meeting_datetime`, `pickup_code`, `finder_confirmed`, `claimant_confirmed`, `handover_notes`, `handed_over_at`) VALUES
(1, 5, 3, NULL, NULL, NULL, NULL, NULL, 'Approved', NULL, '2026-06-13 10:17:24', '2026-06-14 15:21:42', 'Not Arranged', NULL, NULL, NULL, 0, 0, NULL, NULL),
(5, 5, 7, 3, 'it look like mine', 'cafe', '2026-06-19', NULL, '', '', '2026-06-19 13:38:18', '2026-06-22 18:02:32', 'Disputed', NULL, NULL, NULL, 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `found_items`
--

CREATE TABLE `found_items` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `description` text,
  `private_details` text,
  `location` varchar(255) DEFAULT NULL,
  `date_found` date DEFAULT NULL,
  `status` varchar(255) DEFAULT 'Available',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `found_items`
--

INSERT INTO `found_items` (`id`, `user_id`, `item_name`, `image`, `category`, `description`, `private_details`, `location`, `date_found`, `status`, `created_at`, `updated_at`) VALUES
(3, 5, 'laptop', '1781440085_laptop.jpg', 'electornics', 'silver hp laptop', NULL, 'ptar', '2026-06-14', 'Claimed', '2026-06-14 12:28:05', '2026-06-14 15:28:08'),
(5, 5, 'wallet', '1781543981_wallet.jpg', 'personal belonging', 'cream', NULL, 'lab 7 ', '2026-06-16', 'Available', '2026-06-15 17:19:41', NULL),
(7, 6, 'iphone 15', '1781870648_iphone.jpg', 'electronics', 'pink', '', 'cafe', '2026-06-19', 'Claimed', '2026-06-19 12:04:08', '2026-06-19 14:40:02');

-- --------------------------------------------------------

--
-- Table structure for table `lost_items`
--

CREATE TABLE `lost_items` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `description` text,
  `private_details` text,
  `location` varchar(255) DEFAULT NULL,
  `date_lost` date DEFAULT NULL,
  `status` varchar(255) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lost_items`
--

INSERT INTO `lost_items` (`id`, `user_id`, `item_name`, `image`, `category`, `description`, `private_details`, `location`, `date_lost`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, 'montigo bottle', '1781441806_bottle.jpg', 'personal belonging', 'cream', NULL, 'cafe', NULL, 'Pending', '2026-06-14 12:43:08', NULL),
(2, 5, 'wallet', '1781543784_wallet.jpg', 'personal belonging', 'cream colour', NULL, 'lab 7 ', NULL, 'Pending', '2026-06-15 17:13:47', NULL),
(3, 5, 'pink iphone 15', '1781875833_iphone.jpg', 'electronics', 'pink', 'the lock screem is a picture of hello kitty ', 'cafe', '2026-06-19', 'Found', '2026-06-19 13:30:33', '2026-06-19 14:40:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(4, 'admin', 'admin@uitemu.com', '$2y$10$ddIGlmSz61LQ9vJkDz4COeOQnZniINvPbUeTE8KcjWWtlL.vdy3Bm', 'admin', '2026-06-14 11:17:24', NULL),
(5, 'Nurin Irdina', 'student@uitemu.com', '$2y$10$/uw25Zsp1mmMuJOK608EDOFTnglZqQE6QuCcQpBSyHgHIZ84JMjtm', 'user', '2026-06-14 11:22:46', NULL),
(6, 'ilham hakim', 'ilhamkemm@gmail.com', '$2y$10$gSOal3KFAtqip1jhBb3BgOmaAaSJn.Hr39vGr0akY8aMcWa.k15fi', 'user', '2026-06-18 18:46:33', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_certificates_user_id` (`user_id`),
  ADD KEY `idx_certificates_found_item_id` (`found_item_id`);

--
-- Indexes for table `claims`
--
ALTER TABLE `claims`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_claims_user_id` (`user_id`),
  ADD KEY `idx_claims_found_item_id` (`found_item_id`),
  ADD KEY `idx_claims_lost_item_id` (`lost_item_id`);

--
-- Indexes for table `found_items`
--
ALTER TABLE `found_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_found_items_user_id` (`user_id`);

--
-- Indexes for table `lost_items`
--
ALTER TABLE `lost_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lost_items_user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `claims`
--
ALTER TABLE `claims`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `found_items`
--
ALTER TABLE `found_items`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `lost_items`
--
ALTER TABLE `lost_items`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `fk_certificates_found_item` FOREIGN KEY (`found_item_id`) REFERENCES `found_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_certificates_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `claims`
--
ALTER TABLE `claims`
  ADD CONSTRAINT `fk_claims_found_item` FOREIGN KEY (`found_item_id`) REFERENCES `found_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_claims_lost_item` FOREIGN KEY (`lost_item_id`) REFERENCES `lost_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_claims_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `found_items`
--
ALTER TABLE `found_items`
  ADD CONSTRAINT `fk_found_items_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lost_items`
--
ALTER TABLE `lost_items`
  ADD CONSTRAINT `fk_lost_items_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
