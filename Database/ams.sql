-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Mar 04, 2025 at 08:56 AM
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
(4, 'nyfh', '646674', 'fhc@hj', 'nk', 'Seller', '2025-02-28 20:51:48', '2025-02-28 20:51:48', 'inactive'),
(5, 'test customer2', '78947484448', 'oba@gmail', 'heonrnj', 'Seller', '2025-03-01 10:29:41', '2025-03-01 10:29:41', 'inactive'),
(6, 'test customer32', '55789628592', 'fhch@1232', 'xf,fxhfzdjz', 'Seller', '2025-03-01 10:30:29', '2025-03-01 10:30:29', 'inactive'),
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
(2, 'Ani00', 'Ani001@gmail', '960758489', 'Ani00', 'Ani001', '2025-02-24 21:26:15', '2025-02-24 21:26:15', NULL, 'Admin'),
(3, 'Aniruddha023', 'Aniruddha@gmai', '9844721726', 'Aniruddha01122', 'Aniruddha4567', '2025-03-03 18:43:36', '2025-03-03 18:43:36', 'active', 'User');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `customer_tbl`
--
ALTER TABLE `customer_tbl`
  MODIFY `c_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_tbl`
--
ALTER TABLE `user_tbl`
  MODIFY `u_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
