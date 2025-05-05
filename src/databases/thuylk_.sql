-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2025 at 07:55 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `thuylk`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `type` enum('student','teacher','admin') NOT NULL,
  `avatar` varchar(255) DEFAULT 'uploads/default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`id`, `username`, `password`, `fullname`, `email`, `phone`, `type`, `avatar`) VALUES
(1, 'teacher1', '$2y$10$X3lZ4RlS./Zm3wF0T.1aveHoKVQk2QOLnudXw65YRuyCBeYPKgCcu', 'Teacher 1', 'teacher1@vcs.com', '0308070654', 'teacher', 'uploads/b1c2384a-474d-fb87-6f1d-f1d59b974c89.jpg'),
(2, 'teacher2', '$2y$10$NIZWCEeURkgVJ0xhqe0A5eCb7aqw4s7Bb1HTwLwypF9b4abeIxv/e', 'Teacher 2', 'teacher2@vcs.com', '0123456789', 'teacher', 'uploads/ea2dd89e-31a8-fc4c-3ac1-ac109591c4ff.jpg'),
(3, 'student1', '$2y$10$RlxM.ZpQO5np803oQx168.K3hKqahqIpSQ9MmJxmj5EqGQJaffU.6', 'Student 1', 'student1@vcs.com', '0909900111', 'student', 'uploads/69d7c11e-0555-0df8-8482-482aa1ae22cc.jpg'),
(4, 'student2', '$2y$10$DptkWZ6rmfQ/LuKMWi/SwefHgaHR8yh8R7xYZmVFnDHJN0.DQ6oWy', 'Student 2', 'student2@vcs.com', '0909900009', 'student', 'uploads/4f958698-9c3d-0122-e901-901788590475.jpg'),
(5, 'admin', '$2y$10$NIZWCEeURkgVJ0xhqe0A5eCb7aqw4s7Bb1HTwLwypF9b4abeIxv/e', 'Administrator', 'admin@vcs.com', '0909999001', 'admin', 'uploads/d2323fc6-a318-9e19-f4a3-4a3bc89cb15b.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `exercise`
--

CREATE TABLE `exercise` (
  `id` int(11) NOT NULL,
  `authorId` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `url` varchar(200) NOT NULL,
  `created` datetime NOT NULL,
  `deadline` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `exercise`
--

INSERT INTO `exercise` (`id`, `authorId`, `title`, `description`, `url`, `created`, `deadline`) VALUES
(6, 1, 'test0', 'Bài tập test0', '/uploads/works/test0.txt', '2025-05-04 13:52:13', '2025-05-11 13:52:13');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `from_username` varchar(50) NOT NULL,
  `to_username` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `from_username`, `to_username`, `content`, `created_at`, `updated_at`) VALUES
(1, 'teacher1', 'student1', 'hi student1', '2025-05-04 07:54:24', '2025-05-04 08:14:08'),
(3, 'student1', 'teacher1', 'hello teacher 1', '2025-05-04 08:14:59', '2025-05-04 08:14:59'),
(4, 'admin', 'teacher1', 'test from admin', '2025-05-04 08:16:53', '2025-05-04 08:16:53');

-- --------------------------------------------------------

--
-- Table structure for table `submit_exercise`
--

CREATE TABLE `submit_exercise` (
  `id` int(11) NOT NULL,
  `exerciseId` int(11) NOT NULL,
  `url` varchar(200) NOT NULL,
  `created` datetime NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `submit_exercise`
--

INSERT INTO `submit_exercise` (`id`, `exerciseId`, `url`, `created`, `userId`) VALUES
(1, 1, '/uploads/submit_works/123_295lhl.txt', '2022-02-28 06:14:42', 3),
(19, 2, '/uploads/submit_works/271382984_320127416673769_2336783466851981571_n_jumlae.jpg', '2022-02-28 13:44:04', 3),
(20, 3, '/uploads/submit_works/Danh sÃ¡ch bÃ i táº­p thá»±c hÃ nh_lppn7m.docx', '2022-02-28 14:11:41', 3),
(21, 2, '/uploads/submit_works/a_tour_of_sage_7gx86c.pdf', '2022-02-28 14:12:49', 4),
(22, 5, '/uploads/submit_works/images_b9eovz.png', '2022-02-28 15:25:40', 3),
(23, 5, '/uploads/submit_works/271382984_320127416673769_2336783466851981571_n_7rwmlr.jpg', '2022-02-28 15:26:05', 4),
(24, 6, '/uploads/submit_works/solve0_9yhwpz.txt', '2025-05-04 13:53:05', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username_UNIQUE` (`username`);

--
-- Indexes for table `exercise`
--
ALTER TABLE `exercise`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `from_username` (`from_username`),
  ADD KEY `to_username` (`to_username`);

--
-- Indexes for table `submit_exercise`
--
ALTER TABLE `submit_exercise`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `exercise`
--
ALTER TABLE `exercise`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `submit_exercise`
--
ALTER TABLE `submit_exercise`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`from_username`) REFERENCES `account` (`username`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`to_username`) REFERENCES `account` (`username`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
