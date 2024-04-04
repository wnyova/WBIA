-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 27, 2024 at 08:21 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `intern_plnsc`
--

-- --------------------------------------------------------

--
-- Table structure for table `assigned_registrar`
--

CREATE TABLE `assigned_registrar` (
  `id` int(30) NOT NULL,
  `event_id` int(30) NOT NULL,
  `user_id` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assigned_registrar`
--

INSERT INTO `assigned_registrar` (`id`, `event_id`, `user_id`) VALUES
(6, 1, 2),
(7, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `attendees`
--

CREATE TABLE `attendees` (
  `id` int(30) NOT NULL,
  `event_id` int(30) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `middlename` varchar(200) NOT NULL,
  `contact` varchar(200) NOT NULL,
  `gender` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `address` text NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '0=Awaiting/Absent=1=Present',
  `date_created` int(11) NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendees`
--

INSERT INTO `attendees` (`id`, `event_id`, `firstname`, `lastname`, `middlename`, `contact`, `gender`, `email`, `address`, `status`, `date_created`) VALUES
(2, 1, 'Mike', 'Williams', 'G', '+18456-5455-55', 'Male', 'mwilliams@sample.com', 'Sample Address', 1, 2147483647),
(3, 1, 'adsasd', 'asda', 'asda', '+14526-5455-44', 'Male', 'cblake@sample.com', 'asdasdasdasd', 1, 2147483647),
(4, 1, 'Yov Yiv Yuv', '', 'yiv', '01892831', 'Male', 'try1@gmail.com', '', 1, 2147483647);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(30) NOT NULL,
  `event_datetime` datetime NOT NULL,
  `event` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `venue` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 Pending, 1=Open,2=Done',
  `created_by` int(10) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `event_datetime`, `event`, `description`, `venue`, `status`, `created_by`, `date_created`) VALUES
(1, '2020-11-13 08:00:00', 'Absensi', '<p style=\"margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px;\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam vitae nunc eros. Etiam porttitor lacinia velit a fringilla. Vivamus molestie imperdiet nulla, quis varius ante finibus sed. In sit amet ex iaculis, vulputate diam laoreet, pulvinar velit. Donec accumsan risus vitae sapien vehicula, eget blandit nisi faucibus. Etiam placerat accumsan est, sit amet tempus erat vulputate ut. Suspendisse fermentum consectetur odio non auctor. Mauris sit amet imperdiet libero. Phasellus tempor, turpis vitae interdum blandit, nulla sem consectetur metus, in dictum est diam sed mi. Proin et vulputate neque, lacinia lacinia elit. Etiam elementum nunc nibh, gravida malesuada nisi varius nec. Integer at odio eu augue gravida vestibulum sed a risus. Cras volutpat ante sit amet vehicula convallis.</p><p style=\"margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px;\">Mauris eget metus sit amet ante facilisis accumsan. Suspendisse nunc quam, egestas at lorem quis, pretium fringilla elit. Ut nec elit urna. Etiam neque ante, semper nec turpis at, aliquet condimentum lectus. Etiam id nibh at est molestie porta. In non scelerisque massa. Cras bibendum venenatis est et mattis. Donec ante diam, mollis quis lectus eget, bibendum interdum est.</p>															', 'Sample Venue', 2, 0, '2020-11-13 10:04:10'),
(2, '2020-11-14 12:00:00', 'Event 2', '<p style=\"margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px;\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam vitae nunc eros. Etiam porttitor lacinia velit a fringilla. Vivamus molestie imperdiet nulla, quis varius ante finibus sed. In sit amet ex iaculis, vulputate diam laoreet, pulvinar velit. Donec accumsan risus vitae sapien vehicula, eget blandit nisi faucibus. Etiam placerat accumsan est, sit amet tempus erat vulputate ut. Suspendisse fermentum consectetur odio non auctor. Mauris sit amet imperdiet libero. Phasellus tempor, turpis vitae interdum blandit, nulla sem consectetur metus, in dictum est diam sed mi. Proin et vulputate neque, lacinia lacinia elit. Etiam elementum nunc nibh, gravida malesuada nisi varius nec. Integer at odio eu augue gravida vestibulum sed a risus. Cras volutpat ante sit amet vehicula convallis.</p><p style=\"margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px;\">Mauris eget metus sit amet ante facilisis accumsan. Suspendisse nunc quam, egestas at lorem quis, pretium fringilla elit. Ut nec elit urna. Etiam neque ante, semper nec turpis at, aliquet condimentum lectus. Etiam id nibh at est molestie porta. In non scelerisque massa. Cras bibendum venenatis est et mattis. Donec ante diam, mollis quis lectus eget, bibendum interdum est.</p>															', 'Venue 2', 0, 0, '2020-11-13 13:02:03'),
(3, '2024-01-30 11:00:00', 'Meeting  MeetLog', '															', 'Zooom', 0, 0, '2024-01-30 10:41:15');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `middlename` varchar(200) NOT NULL,
  `nama_kampus` varchar(60) NOT NULL,
  `divisi` varchar(50) NOT NULL,
  `contact` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 2 COMMENT '1=Admin,2= users',
  `avatar` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `middlename`, `nama_kampus`, `divisi`, `contact`, `address`, `email`, `password`, `type`, `avatar`, `date_created`) VALUES
(1, 'Admin', 'Admin', '', '', '', '+12354654787', 'Sample', 'admin@admin.com', '0192023a7bbd73250516f069df18b500', 1, '', '2020-11-11 15:35:19'),
(2, 'John', 'Smith', 'C', 'President University', '', '+18456-5455-55', 'Sample Address', 'user@test.com', 'e10adc3949ba59abbe56e057f20f883e', 2, '1605246720_avatar.jpg', '2020-11-13 13:40:15'),
(3, 'George', 'Wilson', 'D', '', '', '+6948 8542 623', 'Sample', 'test@sample.com', 'e10adc3949ba59abbe56e057f20f883e', 2, '1605249300_no-image-available.png', '2020-11-13 14:35:06'),
(4, 'Mochamad Hilmy', 'Cahyadi', 'Febrian Eka', 'President University', 'Perencanaan dan Pengembangan Bisnis', '+681226532882', '', 'hilmy@plnsc.co.id', 'e10adc3949ba59abbe56e057f20f883e', 2, '1605249300_no-image-available.png', '2024-02-26 10:59:26'),
(8, 'Yova', 'Yuv', 'Yiv', 'President University', 'Perencanaan dan Pengembangan Bisnis', '+62895412202137', 'Cikarang', 'yova@plnsc.co.id', 'e10adc3949ba59abbe56e057f20f883e', 2, '', '2024-02-27 08:46:38');

-- --------------------------------------------------------

--
-- Table structure for table `user_attendance`
--

CREATE TABLE `user_attendance` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `event_id` int(2) NOT NULL,
  `login_time` datetime DEFAULT NULL,
  `logout_time` datetime DEFAULT NULL,
  `created_user` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_attendance`
--

INSERT INTO `user_attendance` (`id`, `user_id`, `event_id`, `login_time`, `logout_time`, `created_user`, `created_at`) VALUES
(1, 2, 1, '2024-02-19 10:20:18', '2024-02-19 10:22:33', 'John', '2024-02-19 03:20:18'),
(2, 2, 1, '2024-02-19 10:21:46', '2024-02-19 10:22:33', 'John', '2024-02-19 03:21:46'),
(3, 3, 1, '2024-02-19 11:36:43', '2024-02-19 13:15:23', 'George', '2024-02-19 04:36:43'),
(4, 3, 1, '2024-02-20 08:27:30', '2024-02-20 09:59:02', 'George', '2024-02-20 01:27:30'),
(5, 3, 1, '2024-02-21 08:59:09', '2024-02-21 09:31:02', 'George', '2024-02-21 01:59:09'),
(9, 2, 1, '2024-02-21 15:48:55', '2024-02-21 15:49:26', 'John', '2024-02-21 08:48:55'),
(10, 2, 1, '2024-02-22 11:54:25', NULL, 'John', '2024-02-22 04:54:25'),
(20, 3, 1, '2024-02-26 09:29:32', '2024-02-26 09:29:44', 'George', '2024-02-26 02:29:32'),
(21, 4, 1, '2024-02-26 11:08:48', '2024-02-26 17:05:06', 'Mochamad Hilmy', '2024-02-26 04:08:48'),
(22, 4, 1, '2024-02-27 08:16:41', NULL, 'Mochamad Hilmy', '2024-02-27 01:16:41'),
(23, 8, 1, '2024-02-27 08:47:02', NULL, 'Yova', '2024-02-27 01:47:02'),
(24, 3, 1, '2024-02-27 09:00:12', NULL, 'George', '2024-02-27 02:00:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assigned_registrar`
--
ALTER TABLE `assigned_registrar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendees`
--
ALTER TABLE `attendees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_attendance`
--
ALTER TABLE `user_attendance`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assigned_registrar`
--
ALTER TABLE `assigned_registrar`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `attendees`
--
ALTER TABLE `attendees`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_attendance`
--
ALTER TABLE `user_attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
