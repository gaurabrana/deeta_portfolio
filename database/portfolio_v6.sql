-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2025 at 05:58 PM
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
(15, 4, 'boys_scout', 'Boys Scout', 1),
(16, 5, 'research', 'Research', 1),
(17, 6, 'moment_of_truth', 'Moment Of Truth', 1),
(18, 7, 'giving_back_to_my_school', 'Giving Back To My School', 1),
(19, 7, 'giving_back_to_my_community', 'Giving Back To My Community', 1),
(20, 8, 'gallery_image', 'Gallery Image', 1),
(21, 8, 'gallery_video', 'Gallery Video', 1);

-- --------------------------------------------------------

--
-- Table structure for table `section_upload`
--

CREATE TABLE `section_upload` (
  `id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `upload_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE `uploads` (
  `id` int(11) NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `caption` text DEFAULT NULL,
  `position` varchar(50) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `media_type` enum('image','video','pdf') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL,
  `joined_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `role`, `joined_date`) VALUES
(1, 'admin', '$2y$10$QgwJYEKkeUohTDJpVr/HFOfdKs6DoGNDsg5Jux.kkrLhme9wAAD1u', 'admin', '2025-06-23 18:24:41');

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
-- Indexes for table `section_upload`
--
ALTER TABLE `section_upload`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sectionupload_section` (`section_id`),
  ADD KEY `fk_sectionupload_upload` (`upload_id`);

--
-- Indexes for table `uploads`
--
ALTER TABLE `uploads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `section_upload`
--
ALTER TABLE `section_upload`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `sections_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`);

--
-- Constraints for table `section_upload`
--
ALTER TABLE `section_upload`
  ADD CONSTRAINT `fk_sectionupload_section` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_sectionupload_upload` FOREIGN KEY (`upload_id`) REFERENCES `uploads` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
