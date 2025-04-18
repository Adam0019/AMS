-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Apr 18, 2025 at 04:51 PM
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
(1, 'Axis', '6445649659', 'active', '200000', 'savings', 'bank', '2025-03-29 18:37:39', '2025-03-29 18:37:39', 1),
(2, '', '', NULL, '2510', 'savings', 'Cash', '2025-04-05 14:46:30', '2025-04-05 14:46:30', 1);

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
(3, 'Ani.exe', '584646428', 'ani.exe@gmail.com', 'burdwan', 'Seller', '2025-02-25 19:14:47', '2025-02-25 19:14:47', 'inactive'),
(4, 'Ani.exe22012', '22222555342', 'Ani@99082', 'nk', 'Seller', '2025-02-28 20:51:48', '2025-02-28 20:51:48', 'inactive'),
(5, 'test customer2', '78947484448', 'oba@gmail', 'heonrnj', 'Seller', '2025-03-01 10:29:41', '2025-03-01 10:29:41', 'inactive'),
(7, 'test 6', '498564894856', 'jgcrykyrrry@225', 'fh', 'Buyer', '2025-03-01 14:59:29', '2025-03-01 14:59:29', NULL),
(8, 'test 7', '85166485484', 'gtansd@tdegn', 'tu,t,ttu,u', 'Buyer', '2025-03-03 15:13:39', '2025-03-03 15:13:39', 'active'),
(9, 'test 8', '646674', 'fhch@1232', 'burdwan2', 'Buyer', '2025-04-06 16:36:38', '2025-04-06 16:36:38', NULL),
(10, 'test 9', '58748685', 'Ani@9902', 'mhh', 'Buyer', '2025-04-06 16:37:05', '2025-04-06 16:37:05', NULL),
(11, 'test 10', '953685552', 'Ani@99026', 'bugugu', 'Buyer', '2025-04-06 16:37:34', '2025-04-06 16:37:34', NULL),
(12, 'test 11', '798956', 'fhch@123', 'nkh', 'Buyer', '2025-04-06 16:38:02', '2025-04-06 16:38:02', NULL),
(13, 'test 12', '89964848', 'hkvi@nk', 'fhm', 'Buyer', '2025-04-06 16:38:30', '2025-04-06 16:38:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `gl_tbl`
--

CREATE TABLE `gl_tbl` (
  `gl_id` int(200) NOT NULL,
  `gl_name` varchar(450) NOT NULL,
  `gl_descript` varchar(450) NOT NULL,
  `gl_type` varchar(450) NOT NULL,
  `gl_status` varchar(450) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gl_tbl`
--

INSERT INTO `gl_tbl` (`gl_id`, `gl_name`, `gl_descript`, `gl_type`, `gl_status`, `created_at`, `updated_at`) VALUES
(38, 'Fixed Assets', 'Assets', 'Credit', 'Active', '2025-04-06 21:50:14', '2025-04-06 21:50:14'),
(39, 'Investments', 'Assets', 'Credit', NULL, '2025-04-06 21:54:06', '2025-04-06 21:54:06'),
(40, 'Current Assets', 'Assets', 'Credit', NULL, '2025-04-06 21:54:36', '2025-04-06 21:54:36'),
(41, 'Loans (Liability)', 'Liabilities', 'Debit', NULL, '2025-04-06 21:55:08', '2025-04-06 21:55:08'),
(42, 'Current Liabilities', 'Liabilities', 'Debit', NULL, '2025-04-06 21:55:31', '2025-04-06 21:55:31'),
(43, 'Suspense A/c', 'Liabilities', 'Debit', NULL, '2025-04-06 21:56:39', '2025-04-06 21:56:39'),
(44, 'Sales Account', 'Income', 'Credit', NULL, '2025-04-06 21:57:05', '2025-04-06 21:57:05'),
(45, 'Direct Income', 'Income', 'Credit', NULL, '2025-04-06 21:57:30', '2025-04-06 21:57:30'),
(46, 'Indirect Income', 'Income', 'Credit', NULL, '2025-04-06 21:57:48', '2025-04-06 21:57:48'),
(47, 'Purchase Account', 'Expenses', 'Debit', NULL, '2025-04-06 22:00:49', '2025-04-06 22:00:49'),
(48, 'Direct Expenses', 'Expenses', 'Debit', NULL, '2025-04-06 22:01:18', '2025-04-06 22:01:18'),
(49, 'Rent Paid', 'Expenses', 'Debit', NULL, '2025-04-07 12:37:52', '2025-04-07 12:37:52'),
(50, 'Branch / Divisions', 'Assets', 'Credit', NULL, '2025-04-07 12:38:46', '2025-04-07 12:38:46'),
(51, 'Misc. Expenses (ASSET)', 'Assets', 'Credit', NULL, '2025-04-07 12:39:04', '2025-04-07 12:39:04'),
(52, 'Prepaid Expenses', 'Assets', 'Credit', NULL, '2025-04-07 12:39:20', '2025-04-07 12:39:20'),
(53, 'Accrued Income', 'Assets', 'Credit', NULL, '2025-04-07 12:40:02', '2025-04-07 12:40:02'),
(54, 'Cash-in-Hand', 'Assets', 'Credit', NULL, '2025-04-07 12:40:17', '2025-04-07 12:40:17'),
(55, 'Bank Account', 'Assets', 'Credit', NULL, '2025-04-07 12:40:34', '2025-04-07 12:40:34'),
(56, 'Deposits (Asset)', 'Assets', 'Credit', NULL, '2025-04-07 12:40:48', '2025-04-07 12:40:48'),
(57, 'Advances (Asset)', 'Assets', 'Credit', NULL, '2025-04-07 12:41:03', '2025-04-07 12:41:03'),
(58, 'Inventory / Stock-in-Hand', 'Assets', 'Credit', NULL, '2025-04-07 12:41:17', '2025-04-07 12:41:17'),
(59, 'Furniture & Fixtures', 'Assets', 'Credit', NULL, '2025-04-07 12:41:32', '2025-04-07 12:41:32'),
(60, 'Office Equipment', 'Assets', 'Credit', NULL, '2025-04-07 12:41:47', '2025-04-07 12:41:47'),
(61, 'Vehicles', 'Assets', 'Credit', NULL, '2025-04-07 12:42:08', '2025-04-07 12:42:08'),
(62, 'Outstanding Expenses', 'Liabilities', 'Debit', NULL, '2025-04-07 12:42:34', '2025-04-07 12:42:34'),
(63, 'Accrued Liabilities', 'Liabilities', 'Debit', NULL, '2025-04-07 12:42:47', '2025-04-07 12:42:47'),
(64, 'Bills Payable', 'Liabilities', 'Debit', NULL, '2025-04-07 12:43:01', '2025-04-07 12:43:01'),
(65, 'Creditors', 'Liabilities', 'Debit', NULL, '2025-04-07 12:43:20', '2025-04-07 12:43:20'),
(66, 'Duties & Taxes', 'Liabilities', 'Debit', NULL, '2025-04-07 12:43:48', '2025-04-07 12:43:48'),
(67, 'Provision for Taxation', 'Liabilities', 'Debit', NULL, '2025-04-07 12:44:04', '2025-04-07 12:44:04'),
(68, 'Unearned Revenue', 'Liabilities', 'Debit', NULL, '2025-04-07 12:44:27', '2025-04-07 12:44:27'),
(69, 'Long-Term Loans', 'Liabilities', 'Debit', NULL, '2025-04-07 12:44:41', '2025-04-07 12:44:41'),
(70, 'Short-Term Loans', 'Liabilities', 'Debit', NULL, '2025-04-07 12:45:00', '2025-04-07 12:45:00'),
(71, 'Commission Received', 'Income', 'Credit', NULL, '2025-04-07 12:53:00', '2025-04-07 12:53:00'),
(72, 'Interest Received', 'Income', 'Credit', NULL, '2025-04-07 12:53:27', '2025-04-07 12:53:27'),
(73, 'Rent Received', 'Income', 'Credit', NULL, '2025-04-07 12:53:44', '2025-04-07 12:53:44'),
(74, 'Profit on Sale of Asset', 'Income', 'Credit', NULL, '2025-04-07 12:53:58', '2025-04-07 12:53:58'),
(75, 'Miscellaneous Income', 'Income', 'Credit', NULL, '2025-04-07 12:54:15', '2025-04-07 12:54:15'),
(76, 'Discount Received', 'Income', 'Credit', NULL, '2025-04-07 12:54:27', '2025-04-07 12:54:27'),
(77, 'Salaries & Wages', 'Expenses', 'Debit', NULL, '2025-04-07 12:55:04', '2025-04-07 12:55:04'),
(78, 'Utilities (Electricity, Water)', 'Expenses', 'Debit', NULL, '2025-04-07 12:55:18', '2025-04-07 12:55:18'),
(79, 'Postage & Courier', 'Expenses', 'Debit', NULL, '2025-04-07 12:55:30', '2025-04-07 12:55:30'),
(80, 'Printing & Stationery', 'Expenses', 'Debit', NULL, '2025-04-07 12:55:46', '2025-04-07 12:55:46'),
(81, 'Repairs & Maintenance', 'Expenses', 'Debit', NULL, '2025-04-07 12:55:58', '2025-04-07 12:55:58'),
(82, 'Advertising & Marketing', 'Expenses', 'Debit', NULL, '2025-04-07 12:56:11', '2025-04-07 12:56:11'),
(83, 'Travel Expenses', 'Expenses', 'Debit', NULL, '2025-04-07 12:56:22', '2025-04-07 12:56:22'),
(84, 'Insurance Premium', 'Expenses', 'Debit', NULL, '2025-04-07 12:56:38', '2025-04-07 12:56:38'),
(85, 'Depreciation', 'Expenses', 'Debit', NULL, '2025-04-07 12:56:49', '2025-04-07 12:56:49');

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
(6, 'radha', 'radheradhe@gm', '54646846', 'radha', 'radhekrishna', '2025-03-12 07:02:31', '2025-03-12 07:02:31', NULL, 'Admin');

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
-- Indexes for table `gl_tbl`
--
ALTER TABLE `gl_tbl`
  ADD PRIMARY KEY (`gl_id`);

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
  MODIFY `acc_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customer_tbl`
--
ALTER TABLE `customer_tbl`
  MODIFY `c_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `gl_tbl`
--
ALTER TABLE `gl_tbl`
  MODIFY `gl_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

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
