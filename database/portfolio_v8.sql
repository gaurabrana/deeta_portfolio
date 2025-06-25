-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2025 at 02:43 PM
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
  `title` varchar(255) DEFAULT NULL,
  `page_url` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `slug`, `title`, `page_url`) VALUES
(1, 'about_me', 'About me', 'about_me.php'),
(2, 'schools', 'Schools Attended', 'schools.php'),
(3, 'sports', 'Sports', 'sports.php'),
(4, 'scouting', 'Scouting', 'scouting.php'),
(5, 'research', 'Research', 'research.php'),
(6, 'moment_of_truth', 'Moment Of Truth', 'moment_of_truth.php'),
(7, 'givingback', 'Giving Back', 'givingback.php'),
(8, 'gallery', 'Gallery', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `page_id` int(11) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT 1,
  `section_url` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `page_id`, `slug`, `title`, `visible`, `section_url`) VALUES
(1, 1, 'who_am_i', 'Who Am I', 1, NULL),
(2, 1, 'resume', 'Resume', 1, NULL),
(3, 2, 'lakeside_montessori', 'Lakeside Montessori', 1, NULL),
(4, 2, 'morning_side_elementary_school', 'Morning Side Elementary School', 1, NULL),
(5, 2, 'pearson_middle_school', 'Pearson Middle School', 1, NULL),
(6, 2, 'reedy_high_school', 'Reedy High School', 1, NULL),
(7, 3, 'soccer', 'Soccer', 1, NULL),
(8, 3, 'swimming', 'Swimming', 1, NULL),
(9, 3, 'basketball', 'Basketball', 1, NULL),
(10, 3, 'volleyball', 'Volleyball', 1, NULL),
(11, 3, 'track', 'Track', 1, NULL),
(12, 4, 'girls_scout', 'Girls Scout', 1, NULL),
(13, 4, 'boys_scout', 'Boys Scout', 1, NULL),
(14, 5, 'research', 'Research', 1, NULL),
(15, 6, 'moment_of_truth', 'Moment Of Truth', 1, NULL),
(16, 7, 'giving_back_to_my_school', 'Giving Back To My School', 1, NULL),
(17, 7, 'giving_back_to_my_community', 'Giving Back To My Community', 1, NULL),
(18, 8, 'gallery_image', 'Gallery Image', 1, 'gallery_image.php'),
(19, 8, 'gallery_video', 'Gallery Video', 1, 'gallery_video.php');

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
  `heading` varchar(100) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `media_type` enum('image','video','pdf') DEFAULT NULL
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
(1, 'admin', '$2y$10$/nIb0qknj6NcS87ABk5YOOtCccUGVPAiX9KD4cs/ygvzLbHTbOT5O', 'admin', '2025-06-23 18:24:41');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=223;

--
-- AUTO_INCREMENT for table `section_upload`
--
ALTER TABLE `section_upload`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

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
