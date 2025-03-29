-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Mar 29, 2025 at 07:39 PM
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
-- Database: `ams`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_tbl`
--

CREATE TABLE `account_tbl` (
  `acc_id` int(20) NOT NULL,
  `ab_name` varchar(450) NOT NULL,
  `acc_num` varchar(400) NOT NULL,
  `acc_status` varchar(400) DEFAULT NULL,
  `acc_ammo` varchar(450) NOT NULL,
  `acc_type` varchar(450) NOT NULL,
  `a_type` varchar(450) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `u_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account_tbl`
--

INSERT INTO `account_tbl` (`acc_id`, `ab_name`, `acc_num`, `acc_status`, `acc_ammo`, `acc_type`, `a_type`, `created_at`, `updated_at`, `u_id`) VALUES
(1, 'Axis', '6445649659', NULL, '200000', 'savings', 'bank', '2025-03-29 18:37:39', '2025-03-29 18:37:39', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customer_tbl`
--

CREATE TABLE `customer_tbl` (
  `c_id` int(200) NOT NULL,
  `c_name` varchar(450) NOT NULL,
  `c_phone` varchar(450) NOT NULL,
  `c_email` varchar(450) DEFAULT NULL,
  `c_address` text DEFAULT NULL,
  `c_role` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `c_status` varchar(400) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_tbl`
--

INSERT INTO `customer_tbl` (`c_id`, `c_name`, `c_phone`, `c_email`, `c_address`, `c_role`, `created_at`, `updated_at`, `c_status`) VALUES
(1, 'test customer1235', '9609687686', 'test@1234', 'test112', 'Buyer', '2025-02-22 12:26:50', '2025-02-22 12:26:50', 'inactive'),
(2, 'poly', '584646428', 'fhuu@gmail', 'fhm', 'Buyer', '2025-02-24 21:01:39', '2025-02-24 21:01:39', 'inactive'),
(3, 'Ani.exe', '584646428', 'ani.exe@gmail.com', 'burdwan', 'Seller', '2025-02-25 19:14:47', '2025-02-25 19:14:47', 'inactive'),
(4, 'Ani.exe22012', '22222555342', 'Ani@99082', 'nk', 'Seller', '2025-02-28 20:51:48', '2025-02-28 20:51:48', 'inactive'),
(5, 'test customer2', '78947484448', 'oba@gmail', 'heonrnj', 'Seller', '2025-03-01 10:29:41', '2025-03-01 10:29:41', 'inactive'),
(6, 'test customer321', '557896285926', 'fhch@12324', 'xf,fxhfzdjz', 'Buyer', '2025-03-01 10:30:29', '2025-03-01 10:30:29', 'inactive'),
(7, 'test 6', '498564894856', 'jgcrykyrrry@225', 'fh', 'Buyer', '2025-03-01 14:59:29', '2025-03-01 14:59:29', NULL),
(8, 'test 7', '85166485484', 'gtansd@tdegn', 'tu,t,ttu,u', 'Buyer', '2025-03-03 15:13:39', '2025-03-03 15:13:39', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `user_tbl`
--

CREATE TABLE `user_tbl` (
  `u_id` int(20) NOT NULL,
  `u_name` varchar(255) NOT NULL,
  `u_email` varchar(255) DEFAULT NULL,
  `u_phone` varchar(40) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `u_status` varchar(400) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_tbl`
--

INSERT INTO `user_tbl` (`u_id`, `u_name`, `u_email`, `u_phone`, `username`, `password`, `created_at`, `updated_at`, `u_status`, `role`) VALUES
(1, 'Kaustab', 'kay@gmail.com', '960758488', 'Kaustab01', 'Admin001', '2025-02-12 12:24:17', '2025-02-12 12:24:17', 'inactive', 'Admin'),
(2, 'Ani0025', 'Ani00210@gmail', '960758489519', 'Ani00', 'Ani00190', '2025-02-24 21:26:15', '2025-02-24 21:26:15', NULL, 'Admin'),
(3, 'Aniruddha0234', 'Aniruddha@gmail', '98447217265', 'Aniruddha01122', 'Aniruddha45670', '2025-03-03 18:43:36', '2025-03-03 18:43:36', 'inactive', 'Admin'),
(4, 'krishna', 'krishna08@gmail', '89795855', 'krishna', 'radheradhe', '2025-03-12 06:53:34', '2025-03-12 06:53:34', NULL, 'Admin'),
(5, 'krishna2', 'krishna085@gmail', '897958552', 'krishna2', 'radheradhe2', '2025-03-12 07:00:05', '2025-03-12 07:00:05', NULL, 'Admin'),
(6, 'radha', 'radheradhe@gm', '54646846', 'radha', 'radhekrishna', '2025-03-12 07:02:31', '2025-03-12 07:02:31', NULL, 'Admin'),
(18, 'test2800', 'fyjjyyff@gamil', '02454875', 'test2', 'kgygxkygyhyc', '2025-03-18 19:29:15', '2025-03-18 19:29:15', NULL, 'User');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_tbl`
--
ALTER TABLE `account_tbl`
  ADD PRIMARY KEY (`acc_id`),
  ADD KEY `u_id` (`u_id`);

--
-- Indexes for table `customer_tbl`
--
ALTER TABLE `customer_tbl`
  ADD PRIMARY KEY (`c_id`);

--
-- Indexes for table `user_tbl`
--
ALTER TABLE `user_tbl`
  ADD PRIMARY KEY (`u_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_tbl`
--
ALTER TABLE `account_tbl`
  MODIFY `acc_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer_tbl`
--
ALTER TABLE `customer_tbl`
  MODIFY `c_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_tbl`
--
ALTER TABLE `user_tbl`
  MODIFY `u_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account_tbl`
--
ALTER TABLE `account_tbl`
  ADD CONSTRAINT `u_id` FOREIGN KEY (`u_id`) REFERENCES `user_tbl` (`u_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
