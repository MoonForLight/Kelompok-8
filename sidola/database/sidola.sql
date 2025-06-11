-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 03, 2025 at 04:32 AM
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
-- Database: `sidola`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `user_id`, `comment`, `created_at`) VALUES
(30, 21, 19, 'cool', '2025-06-03 02:19:18');

-- --------------------------------------------------------

--
-- Table structure for table `follows`
--

CREATE TABLE `follows` (
  `id` int(11) NOT NULL,
  `fan_id` int(11) NOT NULL,
  `idol_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `follows`
--

INSERT INTO `follows` (`id`, `fan_id`, `idol_id`, `created_at`) VALUES
(14, 21, 20, '2025-06-03 02:22:53'),
(15, 21, 19, '2025-06-03 02:23:06'),
(16, 21, 18, '2025-06-03 02:23:11');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `post_id`, `user_id`, `created_at`) VALUES
(49, 15, 20, '2025-06-03 02:13:51'),
(50, 17, 20, '2025-06-03 02:14:13'),
(51, 18, 20, '2025-06-03 02:14:50'),
(52, 20, 20, '2025-06-03 02:15:36'),
(53, 21, 18, '2025-06-03 02:17:24'),
(54, 20, 18, '2025-06-03 02:17:25'),
(55, 18, 18, '2025-06-03 02:17:28'),
(56, 22, 19, '2025-06-03 02:18:35'),
(57, 23, 19, '2025-06-03 02:18:50'),
(58, 21, 19, '2025-06-03 02:18:54'),
(59, 23, 21, '2025-06-03 02:22:36'),
(60, 22, 21, '2025-06-03 02:22:41'),
(61, 21, 21, '2025-06-03 02:22:44'),
(62, 20, 21, '2025-06-03 02:22:47'),
(63, 19, 21, '2025-06-03 02:22:49'),
(64, 18, 21, '2025-06-03 02:22:50');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `idol_id` int(11) DEFAULT NULL,
  `to_staff_id` int(11) DEFAULT NULL,
  `sender_role` enum('penggemar','staff') DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `idol_id`, `to_staff_id`, `sender_role`, `message`, `created_at`) VALUES
(12, 21, NULL, 20, 22, 'penggemar', 'hy', '2025-06-03 10:22:58'),
(13, 21, NULL, 18, 22, 'penggemar', 'ay', '2025-06-03 10:23:15'),
(14, 22, 21, 18, 22, 'staff', 'halo', '2025-06-03 10:24:14'),
(15, 22, 21, 20, 22, 'staff', 'alo', '2025-06-03 10:24:29');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `media` varchar(255) DEFAULT NULL,
  `media_type` enum('image','video','none') DEFAULT 'none',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `content`, `media`, `media_type`, `created_at`) VALUES
(15, 20, '<3', '1748916827_seol inah.jpeg', 'image', '2025-06-03 02:13:47'),
(16, 20, '', '', 'none', '2025-06-03 02:13:55'),
(17, 20, 'dinner', '1748916850_🐈.jpeg', 'image', '2025-06-03 02:14:10'),
(18, 20, 'smile!', '1748916886_seol in ah (1).jpeg', 'image', '2025-06-03 02:14:46'),
(19, 20, '', '', 'none', '2025-06-03 02:15:01'),
(20, 20, 'hy', '1748916933_Video by seorina insta.mp4', 'video', '2025-06-03 02:15:33'),
(21, 18, 'last night', '1748917040_231010.jpeg', 'image', '2025-06-03 02:17:20'),
(22, 19, 'new album!', '1748917111_download (3).jpeg', 'image', '2025-06-03 02:18:31'),
(23, 19, 'kinda tired', '', 'none', '2025-06-03 02:18:49');

-- --------------------------------------------------------

--
-- Table structure for table `staff_idol`
--

CREATE TABLE `staff_idol` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `idol_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff_idol`
--

INSERT INTO `staff_idol` (`id`, `staff_id`, `idol_id`) VALUES
(2, 22, 18),
(3, 22, 20);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','idol','penggemar','staff') NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `email`, `profile_pic`) VALUES
(17, 'admin', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', 'admin', NULL, NULL),
(18, 'Ryujin', 'ab615610983e7663a786efee48c16357f67dd08455690780602f7e22dbdc6855', 'idol', 'Ryujin@gmail.com', '1748917005_𝙍𝙮𝙪𝙟𝙞𝙣.jpeg'),
(19, 'Billie Eilish', '8524d5a2ec0175fe9f5bcdf13db89d4dac912791e01667d33a58b7a183eded5f', 'idol', 'BillieEilish@gmail.com', '1748917089_billie eilish at the grammys 2022.jpeg'),
(20, 'Seol In Ah', '6edd0256befbd74650d702e566c5b7e974c7ed373cb6eae41dbc09c0942fac83', 'idol', 'SeolInAh@gmail.com', '1748916792_Twinkling Watermelon.jpeg'),
(21, 'Aldi', 'e05152db2f48aa236c991bbf07018023097310da3ec19cd26b5b675595d8298f', 'penggemar', 'Aldi@gmail.com', '1748917430_Wind Breaker (518).jpeg'),
(22, 'Kpop', '286b53ffd51e9c501b58e85e6fafa1f4ac40c68bb0f8f2d942d6a4d81ff57a96', 'staff', 'Kpop@gmail.com', '1748917338_4556791fbb9fc543ee9bf9e2321d62d2.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `follows`
--
ALTER TABLE `follows`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_idol`
--
ALTER TABLE `staff_idol`
  ADD PRIMARY KEY (`id`),
  ADD KEY `staff_id` (`staff_id`),
  ADD KEY `idol_id` (`idol_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `follows`
--
ALTER TABLE `follows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `staff_idol`
--
ALTER TABLE `staff_idol`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `staff_idol`
--
ALTER TABLE `staff_idol`
  ADD CONSTRAINT `staff_idol_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `staff_idol_ibfk_2` FOREIGN KEY (`idol_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
