-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 16, 2025 at 08:06 PM
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
-- Database: `portfolio`
--

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `slug`, `title`) VALUES
(1, 'about_me', 'About me'),
(2, 'schools', 'Schools Attended'),
(3, 'sports', 'Sports'),
(4, 'scouting', 'Scouting'),
(5, 'research', 'Research'),
(6, 'moment_of_truth', 'Moment Of Truth'),
(7, 'givingback', 'Giving Back'),
(8, 'gallery', 'Gallery');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `page_id` int(11) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `page_id`, `slug`, `title`, `visible`) VALUES
(1, 1, 'introduction', 'Introduction', 1),
(2, 1, 'leader', 'Am I A Leader', 1),
(3, 1, 'resilient', 'Am I Resilient', 1),
(4, 1, 'empathy', 'Do I Have An Empathy', 1),
(5, 1, 'resume', 'Resume', 1),
(6, 2, 'morning_side_elementary_school', 'Morning Side Elementary School', 1),
(7, 2, 'pearson_middle_school', 'Pearson Middle School', 1),
(8, 2, 'reedy_high_school', 'Reedy High School', 1),
(9, 3, 'soccer', 'Soccer', 1),
(10, 3, 'swimming', 'Swimming', 1),
(11, 3, 'basketball', 'Basketball', 1),
(12, 3, 'volleyball', 'Volleyball', 1),
(13, 3, 'track', 'Track', 1),
(14, 4, 'girls_scout', 'Girls Scout', 1),
(15, 4, 'boys_scout', 'Boys Scout', 1);

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE `uploads` (
  `id` int(11) NOT NULL,
  `section_id` int(11) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `caption` text DEFAULT NULL,
  `position` varchar(50) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `media_type` enum('image','video') NOT NULL DEFAULT 'image'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uploads`
--

INSERT INTO `uploads` (`id`, `section_id`, `path`, `caption`, `position`, `uploaded_at`, `media_type`) VALUES
(1, 10, '1750095881_fed42032.jpg', 'asd', 'left', '2025-06-16 17:44:41', 'image'),
(2, 11, '1750096038_fbcfab31.jpeg', 'asdasdasd', 'left', '2025-06-16 17:47:18', 'image'),
(5, 12, '1750096263_7fa159f7.jpeg', 'volley is a great sport', 'left', '2025-06-16 17:51:03', 'image'),
(6, 13, '1750096501_7eadb0af.png', 'asdasd', 'left', '2025-06-16 17:55:01', 'image'),
(7, 9, '1750096632_8b178945.jpg', 'scoer', 'left', '2025-06-16 17:57:12', 'image'),
(8, 6, '1750097105_0cff300f.jpeg', 'dsadas', 'left', '2025-06-16 18:05:05', 'image');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_id` (`page_id`);

--
-- Indexes for table `uploads`
--
ALTER TABLE `uploads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section_id` (`section_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `sections_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`);

--
-- Constraints for table `uploads`
--
ALTER TABLE `uploads`
  ADD CONSTRAINT `uploads_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
