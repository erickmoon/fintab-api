-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 30, 2023 at 02:12 PM
-- Server version: 5.7.41
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shukloco_fintab`
--

-- --------------------------------------------------------

--
-- Table structure for table `app_settings`
--

CREATE TABLE `app_settings` (
  `id` int(11) NOT NULL,
  `savings_interest_rate` varchar(100) DEFAULT NULL,
  `savings_interest_days` varchar(100) DEFAULT NULL,
  `loans_interest_rate_per_day` varchar(100) DEFAULT NULL,
  `goods_interest_rate_per_day` varchar(100) DEFAULT NULL,
  `minimum_withdrawable` varchar(100) DEFAULT NULL,
  `maximum_withdrawable` varchar(100) DEFAULT NULL,
  `b2c_remarks_withdraw_from_savings` varchar(100) DEFAULT NULL,
  `maximum_loan_amount` varchar(100) DEFAULT NULL,
  `max_days_to_end_date` varchar(100) DEFAULT NULL,
  `maxium_loan_days` varchar(100) DEFAULT NULL,
  `maxium_goods_loan_days` varchar(100) DEFAULT NULL,
  `loan_sales_factor` varchar(100) DEFAULT NULL,
  `minumum_loan_amount` varchar(100) DEFAULT NULL,
  `duration_of_sales_days_for_loan_calculation` varchar(100) DEFAULT NULL,
  `agrovets_user_id` varchar(100) DEFAULT NULL,
  `b2c_remarks` varchar(100) DEFAULT NULL,
  `savings_enabled` varchar(100) DEFAULT NULL,
  `base_app_name` varchar(100) DEFAULT NULL,
  `paybill_shorcode_user` varchar(100) DEFAULT NULL,
  `paybill_shorcode` varchar(100) DEFAULT NULL,
  `deposit_paybill_shortcode` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `app_settings`
--

INSERT INTO `app_settings` (`id`, `savings_interest_rate`, `savings_interest_days`, `loans_interest_rate_per_day`, `goods_interest_rate_per_day`, `minimum_withdrawable`, `maximum_withdrawable`, `b2c_remarks_withdraw_from_savings`, `maximum_loan_amount`, `max_days_to_end_date`, `maxium_loan_days`, `maxium_goods_loan_days`, `loan_sales_factor`, `minumum_loan_amount`, `duration_of_sales_days_for_loan_calculation`, `agrovets_user_id`, `b2c_remarks`, `savings_enabled`, `base_app_name`, `paybill_shorcode_user`, `paybill_shorcode`, `deposit_paybill_shortcode`) VALUES
(1, '0.01', '30', '0.01', '0.01', '500', '50000', 'withdraw savings', '10000', '5', '30', '30', '10', '500', '61', '2', 'Loan disbursement', 'yes', 'FINTAB', '539100 (QUOUGAR)', '539100', '539100');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `time_in` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time_out` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cooperatives`
--

CREATE TABLE `cooperatives` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location_id` int(10) UNSIGNED DEFAULT NULL,
  `products_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cooperatives`
--

INSERT INTO `cooperatives` (`id`, `name`, `location_id`, `products_id`, `created_at`, `created_by`) VALUES
(1, 'Cooperative ABC', 1, 1, '2022-12-09 17:02:56', '1');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `disbursed_goods_from_agrovets`
--

CREATE TABLE `disbursed_goods_from_agrovets` (
  `id` int(11) NOT NULL,
  `farmer_id` varchar(100) DEFAULT NULL,
  `agrovet_id` varchar(100) DEFAULT NULL,
  `amount_disbursed` varchar(100) DEFAULT NULL,
  `amount_disbursed_with_interest` varchar(100) DEFAULT NULL,
  `date_initiated` varchar(100) NOT NULL,
  `date_disbursed` varchar(100) DEFAULT NULL,
  `due_date` varchar(100) NOT NULL,
  `last_interest_charge_date` varchar(100) DEFAULT NULL,
  `otp` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `disbursed_goods_from_agrovets`
--

INSERT INTO `disbursed_goods_from_agrovets` (`id`, `farmer_id`, `agrovet_id`, `amount_disbursed`, `amount_disbursed_with_interest`, `date_initiated`, `date_disbursed`, `due_date`, `last_interest_charge_date`, `otp`, `status`) VALUES
(1, '1', '2', '300', '320', '', '2023-01-01', '', NULL, NULL, 'active'),
(2, '1', '3', '2500', '2525', '2023-01-04 07:02:30', NULL, '2023-02-02', '2023-01-04', 'E15CA5', 'waiting_verification'),
(3, '1', '2', '2000', '2020', '2023-01-05 01:18:51', NULL, '2023-02-03', '2023-01-05', '7EBE0E', 'waiting_verification'),
(4, '1', '2', '2000', '2020', '2023-01-05 01:20:02', NULL, '2023-02-03', '2023-01-05', 'C0D4BA', 'waiting_verification'),
(5, NULL, NULL, NULL, '0', '2023-01-05 01:21:20', NULL, '2023-02-03', '2023-01-05', '6D1014', 'waiting_verification'),
(6, '1', '3', '2000', '2020', '2023-01-05 01:24:54', NULL, '2023-02-03', '2023-01-05', '9874BE', 'waiting_verification'),
(7, '1', '3', '2000', '2020', '2023-01-05 01:27:30', NULL, '2023-02-03', '2023-01-05', '869392', 'active'),
(9, '1', '5', '500', '505', '2023-01-05 01:33:29', NULL, '2023-02-03', '2023-01-05', '292256', 'active'),
(10, '1', '2', '1000', '1010', '2023-01-18 12:25:57', NULL, '2023-02-16', '2023-01-18', '825317', 'waiting_verification'),
(11, '1', '2', '2000', '2020', '2023-01-18 12:33:41', NULL, '2023-02-16', '2023-01-18', '739736', 'waiting_verification'),
(12, '1', '2', '2000', '2020', '2023-01-18 12:36:08', NULL, '2023-02-16', '2023-01-18', '706451', 'active'),
(13, '1', '2', '1000', '1010', '2023-01-18 06:22:06', NULL, '2023-02-16', '2023-01-18', '508957', 'waiting_verification'),
(14, '1', '2', '600', '606', '2023-01-22 04:32:59', NULL, '2023-02-20', '2023-01-22', '767739', 'waiting_verification');

-- --------------------------------------------------------

--
-- Table structure for table `disbursed_goods_from_agrovets_repayments`
--

CREATE TABLE `disbursed_goods_from_agrovets_repayments` (
  `id` int(11) NOT NULL,
  `cooperative_id` varchar(109) DEFAULT NULL,
  `farmer_id` varchar(109) DEFAULT NULL,
  `agrovet_id` varchar(109) DEFAULT NULL,
  `amount` varchar(109) DEFAULT NULL,
  `date_time` varchar(109) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `disbursed_goods_from_agrovets_repayments`
--

INSERT INTO `disbursed_goods_from_agrovets_repayments` (`id`, `cooperative_id`, `farmer_id`, `agrovet_id`, `amount`, `date_time`) VALUES
(1, '1', '1', '2', '700', '2022-01-01 12:34:12');

-- --------------------------------------------------------

--
-- Table structure for table `disbursed_loans`
--

CREATE TABLE `disbursed_loans` (
  `id` int(11) NOT NULL,
  `user_id` varchar(100) DEFAULT NULL,
  `disbursed_amount` text,
  `amount_with_interest` varchar(100) DEFAULT NULL,
  `date_issued` varchar(100) DEFAULT NULL,
  `date_due` varchar(100) DEFAULT NULL,
  `last_interest_charge_date` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `conversation_id` varchar(100) DEFAULT NULL,
  `response_code` text,
  `originator_conversation_id` text,
  `response_description` varchar(250) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(100) DEFAULT 'android'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `disbursed_loans`
--

INSERT INTO `disbursed_loans` (`id`, `user_id`, `disbursed_amount`, `amount_with_interest`, `date_issued`, `date_due`, `last_interest_charge_date`, `status`, `conversation_id`, `response_code`, `originator_conversation_id`, `response_description`, `transaction_id`, `created_at`, `created_by`) VALUES
(2, '1', '1500', '1515', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-04 23:14:03', 'android'),
(3, '1', '1500', '1515', '2023-01-05 02:15:23', '2023-02-03', '2023-01-05', 'inactive', NULL, '0', NULL, NULL, NULL, '2023-01-04 23:15:23', 'android_app'),
(5, '1', '1500', '1515', '2023-01-05 02:17:32', '2023-02-03', '2023-01-05', 'inactive', 'AG_20230105_20107c7a70f79674ba9e', '0', NULL, NULL, NULL, '2023-01-04 23:17:32', 'android_app'),
(7, '1', '1500', '1515', '2023-01-05 02:20:02', '2023-02-03', '2023-01-05', 'inactive', 'AG_20230105_202027c795fb41b6a047', '0', NULL, 'Accept the service request successfully.', NULL, '2023-01-04 23:20:03', 'android_app'),
(8, '1', '1500', '1515', '2023-01-05 02:34:02', '2023-02-03', '2023-01-05', 'inactive', 'AG_20230105_20403bbdcc9116a45b42', '0', 'Test', 'Accept the service request successfully.', NULL, '2023-01-04 23:34:02', 'android_app'),
(9, '1', '1500', '1515', '2023-01-05 02:34:47', '2023-02-03', '2023-01-05', 'inactive', 'AG_20230105_205018bccfe6a89766e1', '0', 'Test', 'Accept the service request successfully.', NULL, '2023-01-04 23:34:47', 'android_app'),
(10, '1', '1500', '1515', '2023-01-05 02:35:23', '2023-02-03', '2023-01-05', 'inactive', 'AG_20230105_2030719d22fcaa5b015c', '0', 'Test', 'Accept the service request successfully.', NULL, '2023-01-04 23:35:23', 'android_app'),
(16, '1', '1500', '1515', '2023-01-05 02:44:34', '2023-02-03', '2023-01-05', 'inactive', 'AG_20230105_205022c4a2b342f1bc83', '0', '', 'Accept the service request successfully.', NULL, '2023-01-04 23:44:34', 'android_app'),
(17, '1', '1500', '1515', '2023-01-05 01:25:21', '2023-02-03', '2023-01-05', 'inactive', 'AG_20230105_20404991ae439682cb24', '0', '', 'Accept the service request successfully.', NULL, '2023-01-05 10:25:21', 'android_app'),
(18, '1', '1500', '1515', '2023-01-05 01:26:44', '2023-02-03', '2023-01-05', 'inactive', 'AG_20230105_201051571ee34231c378', '0', 'id-118500-149082882-1', 'Accept the service request successfully.', NULL, '2023-01-05 10:26:44', 'android_app'),
(21, '1', '1500', '1515', '2023-01-05 01:35:28', '2023-02-03', '2023-01-05', 'inactive', 'AG_20230105_20706cf29464c6546d24', '0', 'id-', 'Accept the service request successfully.', NULL, '2023-01-05 10:35:28', 'android_app'),
(23, '1', '1500', '1515', '2023-01-05 01:43:36', '2023-02-03', '2023-01-05', 'active', 'AG_20230105_201054661ad036a291ac', '0', '10750-88792445-1', 'Accept the service request successfully.', NULL, '2023-01-05 10:43:36', 'android_app'),
(24, '1', '1500', '1515', '2023-01-05 02:44:27', '2023-02-03', '2023-01-05', 'inactive', 'AG_20230105_20503ddccf209c095479', '0', '73198-128590275-1', 'Accept the service request successfully.', NULL, '2023-01-05 11:44:27', 'android_app'),
(26, '1', '500', '505', '2023-01-18 12:49:37', '2023-02-03', '2023-01-18', 'inactive', 'AG_20230118_20702e7c84f95d712236', '0', '32497-140317413-1', 'Accept the service request successfully.', NULL, '2023-01-18 09:49:40', 'android_app'),
(39, '1', '1200', '1212', '2023-01-22 12:24:47', '2023-02-03', '2023-01-22', 'inactive', 'AG_20230122_204025f1bd9d0a66b664', '0', '42561-62394-1', 'Accept the service request successfully.', NULL, '2023-01-21 21:24:47', 'android_app'),
(40, '1', '1200', '1212', '2023-01-22 12:26:03', '2023-02-03', '2023-01-22', 'inactive', 'AG_20230122_20600eab19db47c8c14a', '0', '42558-63883-1', 'Accept the service request successfully.', NULL, '2023-01-21 21:26:03', 'android_app'),
(41, '1', '1200', '1212', '2023-01-22 12:43:58', '2023-02-03', '2023-01-22', 'inactive', 'AG_20230122_206013c785720b1d3374', '0', '59548-201349617-1', 'Accept the service request successfully.', NULL, '2023-01-21 21:43:59', 'android_app'),
(42, '1', '1200', '1212', '2023-01-22 12:46:22', '2023-02-03', '2023-01-22', 'inactive', 'AG_20230122_20504d7bb0f0f0ee568e', '0', '42569-86282-1', 'Accept the service request successfully.', NULL, '2023-01-21 21:46:22', 'android_app'),
(43, '1', '1200', '1212', '2023-01-22 12:49:29', '2023-02-03', '2023-01-22', 'inactive', 'AG_20230122_205019be4762cc0b627f', '0', '115208-198004472-1', 'Accept the service request successfully.', NULL, '2023-01-21 21:49:29', 'android_app'),
(44, '1', '1200', '1212', '2023-01-22 12:52:09', '2023-02-03', '2023-01-22', 'inactive', 'AG_20230122_20105ed763ebda2d50da', '0', '32503-152493247-1', 'Accept the service request successfully.', NULL, '2023-01-21 21:52:09', 'android_app'),
(45, '1', '1200', '1212', '2023-01-22 12:55:27', '2023-02-03', '2023-01-22', 'inactive', 'AG_20230122_20205fe7c182ee3022ac', '0', '120621-5574610-1', 'Accept the service request successfully.', NULL, '2023-01-21 21:55:27', 'android_app'),
(46, '1', '1200', '1212', '2023-01-22 01:00:39', '2023-02-03', '2023-01-22', 'active', 'AG_20230122_207009a1ce088aae9ac8', '0', '103173-164895507-1', 'The balance is insufficient for the transaction.', 'RAM3WE8JTB', '2023-01-21 22:00:39', 'android_app'),
(47, '1', '400', '404', '2023-01-22 01:04:57', '2023-02-03', '2023-01-22', 'active', 'AG_20230122_20207b93dd840ef2bf08', '0', '120611-5584297-1', 'The balance is insufficient for the transaction.', 'RAM2WEAHV8', '2023-01-21 22:04:57', 'android_app');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_role` int(10) UNSIGNED DEFAULT NULL,
  `location_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(10) UNSIGNED NOT NULL,
  `expense_item_id` int(10) UNSIGNED NOT NULL,
  `amount` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses_item`
--

CREATE TABLE `expenses_item` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan_repayment`
--

CREATE TABLE `loan_repayment` (
  `id` int(10) UNSIGNED NOT NULL,
  `loan_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loan_repayment`
--

INSERT INTO `loan_repayment` (`id`, `loan_id`, `user_id`, `amount`, `transaction_id`, `date`, `created_at`, `created_by`) VALUES
(1, '', '1', '1.00', 'RAM7WEQE8N', '2023-01-22 01:57:53', '2023-01-21 22:57:53', '');

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `measurements`
--

CREATE TABLE `measurements` (
  `id` int(10) UNSIGNED NOT NULL,
  `porter_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `amount` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `measurements`
--

INSERT INTO `measurements` (`id`, `porter_id`, `product_id`, `user_id`, `amount`, `date`, `created_at`, `created_by`) VALUES
(1, 1, 9, 1, '741', '2022-11-06 11:27:05', '2023-01-02 09:18:27', 'Android'),
(2, 1, 3, 1, '734', '2022-03-28 11:27:05', '2023-01-02 09:18:27', 'Android'),
(3, 1, 3, 1, '981', '2022-01-22 11:27:05', '2023-01-02 09:18:27', 'Android'),
(4, 1, 7, 1, '576', '2022-05-20 11:27:05', '2023-01-02 09:18:27', 'Android'),
(5, 1, 6, 1, '603', '2022-08-18 11:27:05', '2023-01-02 09:18:27', 'Android'),
(6, 1, 3, 1, '93', '2023-05-16 11:27:05', '2023-01-02 09:18:27', 'Android'),
(7, 1, 1, 1, '521', '2022-02-29 11:27:05', '2023-01-02 09:18:27', 'Android'),
(8, 1, 7, 1, '830', '2023-04-13 11:27:05', '2023-01-02 09:18:27', 'Android'),
(9, 1, 3, 1, '772', '2022-10-10 11:27:05', '2023-01-02 09:18:27', 'Android'),
(10, 1, 4, 1, '172', '2023-03-10 11:27:05', '2023-01-02 09:18:28', 'Android'),
(11, 1, 7, 1, '678', '2022-03-28 11:27:05', '2023-01-02 09:18:28', 'Android'),
(12, 1, 12, 1, '810', '2022-11-24 11:27:05', '2023-01-02 09:18:28', 'Android'),
(13, 1, 5, 1, '350', '2023-03-22 11:27:05', '2023-01-02 09:18:28', 'Android'),
(14, 1, 4, 1, '324', '2023-06-15 11:27:05', '2023-01-02 09:18:28', 'Android'),
(15, 1, 12, 1, '808', '2022-11-01 11:27:05', '2023-01-02 09:18:28', 'Android'),
(16, 1, 2, 1, '286', '2022-06-10 11:27:05', '2023-01-02 09:18:28', 'Android'),
(17, 1, 1, 1, '78', '2023-11-14 11:27:05', '2023-01-02 09:18:28', 'Android'),
(18, 1, 5, 1, '436', '2022-08-28 11:27:05', '2023-01-02 09:18:28', 'Android'),
(19, 1, 10, 1, '560', '2023-10-16 11:27:05', '2023-01-02 09:18:28', 'Android'),
(20, 1, 8, 1, '637', '2022-12-15 11:27:05', '2023-01-02 09:18:28', 'Android'),
(21, 1, 4, 1, '764', '2022-08-26 11:27:05', '2023-01-02 09:18:28', 'Android'),
(22, 1, 3, 1, '389', '2023-06-28 11:27:05', '2023-01-02 09:18:28', 'Android'),
(23, 1, 3, 1, '307', '2022-11-26 11:27:05', '2023-01-02 09:18:28', 'Android'),
(24, 1, 4, 1, '567', '2023-10-15 11:27:05', '2023-01-02 09:18:28', 'Android'),
(25, 1, 4, 1, '674', '2023-05-02 11:27:05', '2023-01-02 09:18:28', 'Android'),
(26, 1, 4, 1, '734', '2022-10-25 11:27:05', '2023-01-02 09:18:28', 'Android'),
(27, 1, 3, 1, '61', '2023-10-21 11:27:05', '2023-01-02 09:18:28', 'Android'),
(28, 1, 4, 1, '726', '2023-03-09 11:27:05', '2023-01-02 09:18:28', 'Android'),
(29, 1, 5, 1, '301', '2022-06-02 11:27:05', '2023-01-02 09:18:28', 'Android'),
(30, 1, 10, 1, '890', '2022-09-13 11:27:05', '2023-01-02 09:18:28', 'Android'),
(31, 1, 8, 1, '586', '2023-09-16 11:27:05', '2023-01-02 09:18:28', 'Android'),
(32, 1, 6, 1, '612', '2023-06-15 11:27:05', '2023-01-02 09:18:28', 'Android'),
(33, 1, 2, 1, '638', '2022-09-06 11:27:05', '2023-01-02 09:18:28', 'Android'),
(34, 1, 5, 1, '421', '2023-09-17 11:27:05', '2023-01-02 09:18:28', 'Android'),
(35, 1, 3, 1, '395', '2022-08-04 11:27:05', '2023-01-02 09:18:28', 'Android'),
(36, 1, 1, 1, '198', '2022-08-26 11:27:05', '2023-01-02 09:18:28', 'Android'),
(37, 1, 2, 1, '333', '2023-01-14 11:27:05', '2023-01-02 09:18:28', 'Android'),
(38, 1, 7, 1, '30', '2023-11-27 11:27:05', '2023-01-02 09:18:28', 'Android'),
(39, 1, 2, 1, '936', '2022-09-22 11:27:05', '2023-01-02 09:18:28', 'Android'),
(40, 1, 1, 1, '513', '2023-08-29 11:27:05', '2023-01-02 09:18:28', 'Android'),
(41, 1, 10, 1, '744', '2022-05-19 11:27:05', '2023-01-02 09:18:28', 'Android'),
(42, 1, 9, 1, '50', '2023-08-15 11:27:05', '2023-01-02 09:18:28', 'Android'),
(43, 1, 6, 1, '476', '2022-07-14 11:27:05', '2023-01-02 09:18:28', 'Android'),
(44, 1, 1, 1, '856', '2023-12-16 11:27:05', '2023-01-02 09:18:28', 'Android'),
(45, 1, 9, 1, '26', '2022-03-15 11:27:05', '2023-01-02 09:18:28', 'Android'),
(46, 1, 6, 1, '112', '2023-06-26 11:27:05', '2023-01-02 09:18:28', 'Android'),
(47, 1, 6, 1, '376', '2022-06-19 11:27:05', '2023-01-02 09:18:28', 'Android'),
(48, 1, 8, 1, '634', '2022-05-09 11:27:05', '2023-01-02 09:18:28', 'Android'),
(49, 1, 4, 1, '630', '2023-01-11 11:27:05', '2023-01-02 09:18:28', 'Android'),
(50, 1, 6, 1, '210', '2022-09-09 11:27:05', '2023-01-02 09:18:28', 'Android'),
(51, 1, 11, 1, '98', '2023-05-15 11:27:05', '2023-01-02 09:18:28', 'Android'),
(52, 1, 10, 1, '681', '2023-02-17 11:27:05', '2023-01-02 09:18:28', 'Android'),
(53, 1, 4, 1, '911', '2022-07-02 11:27:05', '2023-01-02 09:18:28', 'Android'),
(54, 1, 7, 1, '459', '2022-05-23 11:27:05', '2023-01-02 09:18:28', 'Android'),
(55, 1, 11, 1, '138', '2023-02-12 11:27:05', '2023-01-02 09:18:29', 'Android'),
(56, 1, 11, 1, '934', '2022-06-01 11:27:05', '2023-01-02 09:18:29', 'Android'),
(57, 1, 5, 1, '23', '2022-07-27 11:27:05', '2023-01-02 09:18:29', 'Android'),
(58, 1, 4, 1, '262', '2022-04-27 11:27:05', '2023-01-02 09:18:29', 'Android'),
(59, 1, 3, 1, '974', '2022-01-08 11:27:05', '2023-01-02 09:18:29', 'Android'),
(60, 1, 2, 1, '144', '2022-03-22 11:27:05', '2023-01-02 09:18:29', 'Android'),
(61, 1, 12, 1, '780', '2022-02-04 11:27:05', '2023-01-02 09:18:29', 'Android'),
(62, 1, 9, 1, '924', '2023-05-20 11:27:05', '2023-01-02 09:18:29', 'Android'),
(63, 1, 10, 1, '812', '2023-04-04 11:27:05', '2023-01-02 09:18:29', 'Android'),
(64, 1, 3, 1, '556', '2022-02-18 11:27:05', '2023-01-02 09:18:29', 'Android'),
(65, 1, 12, 1, '546', '2023-11-29 11:27:05', '2023-01-02 09:18:29', 'Android'),
(66, 1, 10, 1, '187', '2022-07-05 11:27:05', '2023-01-02 09:18:29', 'Android'),
(67, 1, 9, 1, '529', '2022-12-20 11:27:05', '2023-01-02 09:18:29', 'Android'),
(68, 1, 5, 1, '782', '2023-11-29 11:27:05', '2023-01-02 09:18:29', 'Android'),
(69, 1, 2, 1, '967', '2023-02-25 11:27:05', '2023-01-02 09:18:29', 'Android'),
(70, 1, 7, 1, '800', '2022-10-27 11:27:05', '2023-01-02 09:18:29', 'Android'),
(71, 1, 4, 1, '515', '2022-06-26 11:27:05', '2023-01-02 09:18:29', 'Android'),
(72, 1, 4, 1, '458', '2022-03-17 11:27:05', '2023-01-02 09:18:29', 'Android'),
(73, 1, 7, 1, '776', '2023-04-12 11:27:05', '2023-01-02 09:18:29', 'Android'),
(74, 1, 2, 1, '141', '2023-08-22 11:27:05', '2023-01-02 09:18:29', 'Android'),
(75, 1, 6, 1, '957', '2022-11-12 11:27:05', '2023-01-02 09:18:29', 'Android'),
(76, 1, 11, 1, '570', '2023-09-30 11:27:05', '2023-01-02 09:18:29', 'Android'),
(77, 1, 8, 1, '50', '2022-04-14 11:27:05', '2023-01-02 09:18:29', 'Android'),
(78, 1, 5, 1, '783', '2022-11-18 11:27:05', '2023-01-02 09:18:29', 'Android'),
(79, 1, 9, 1, '800', '2023-03-12 11:27:05', '2023-01-02 09:18:29', 'Android'),
(80, 1, 9, 1, '786', '2022-07-15 11:27:05', '2023-01-02 09:18:29', 'Android'),
(81, 1, 11, 1, '236', '2023-12-23 11:27:05', '2023-01-02 09:18:29', 'Android'),
(82, 1, 2, 1, '722', '2022-07-13 11:27:05', '2023-01-02 09:18:29', 'Android'),
(83, 1, 8, 1, '594', '2022-08-04 11:27:05', '2023-01-02 09:18:29', 'Android'),
(84, 1, 8, 1, '614', '2023-03-06 11:27:05', '2023-01-02 09:18:29', 'Android'),
(85, 1, 10, 1, '459', '2022-12-08 11:27:05', '2023-01-02 09:18:29', 'Android'),
(86, 1, 2, 1, '500', '2023-03-23 11:27:05', '2023-01-02 09:18:29', 'Android'),
(87, 1, 2, 1, '288', '2023-04-03 11:27:05', '2023-01-02 09:18:29', 'Android'),
(88, 1, 5, 1, '985', '2022-03-06 11:27:05', '2023-01-02 09:18:29', 'Android'),
(89, 1, 9, 1, '589', '2022-10-17 11:27:05', '2023-01-02 09:18:29', 'Android'),
(90, 1, 8, 1, '991', '2022-05-09 11:27:05', '2023-01-02 09:18:29', 'Android'),
(91, 1, 3, 1, '887', '2022-08-21 11:27:05', '2023-01-02 09:18:29', 'Android'),
(92, 1, 1, 1, '918', '2023-02-28 11:27:05', '2023-01-02 09:18:29', 'Android'),
(93, 1, 3, 1, '808', '2023-07-13 11:27:05', '2023-01-02 09:18:29', 'Android'),
(94, 1, 9, 1, '509', '2023-01-07 11:27:05', '2023-01-02 09:18:29', 'Android'),
(95, 1, 9, 1, '57', '2023-03-12 11:27:05', '2023-01-02 09:18:29', 'Android'),
(96, 1, 11, 1, '783', '2023-02-01 11:27:05', '2023-01-02 09:18:29', 'Android'),
(97, 1, 7, 1, '889', '2023-04-07 11:27:05', '2023-01-02 09:18:29', 'Android'),
(98, 1, 6, 1, '450', '2023-07-27 11:27:05', '2023-01-02 09:18:29', 'Android'),
(99, 1, 8, 1, '610', '2023-10-29 11:27:05', '2023-01-02 09:18:30', 'Android'),
(100, 1, 2, 1, '841', '2023-10-04 11:27:05', '2023-01-02 09:18:30', 'Android'),
(101, 1, 3, 1, '796', '2023-08-07 11:27:05', '2023-01-02 09:18:30', 'Android'),
(102, 1, 10, 1, '301', '2023-03-26 11:27:05', '2023-01-02 09:18:30', 'Android'),
(103, 1, 9, 1, '898', '2023-10-03 11:27:05', '2023-01-02 09:18:30', 'Android'),
(104, 1, 10, 1, '274', '2023-09-22 11:27:05', '2023-01-02 09:18:30', 'Android'),
(105, 1, 9, 1, '243', '2022-12-25 11:27:05', '2023-01-02 09:18:30', 'Android'),
(106, 1, 6, 1, '149', '2022-04-26 11:27:05', '2023-01-02 09:18:30', 'Android'),
(107, 1, 2, 1, '656', '2023-03-03 11:27:05', '2023-01-02 09:18:30', 'Android'),
(108, 1, 7, 1, '530', '2022-02-25 11:27:05', '2023-01-02 09:18:30', 'Android'),
(109, 1, 6, 1, '886', '2023-07-06 11:27:05', '2023-01-02 09:18:30', 'Android'),
(110, 1, 8, 1, '650', '2022-03-27 11:27:05', '2023-01-02 09:18:30', 'Android'),
(111, 1, 11, 1, '924', '2022-07-27 11:27:05', '2023-01-02 09:18:30', 'Android'),
(112, 1, 7, 1, '488', '2022-04-19 11:27:05', '2023-01-02 09:18:30', 'Android'),
(113, 1, 4, 1, '727', '2022-10-20 11:27:05', '2023-01-02 09:18:30', 'Android'),
(114, 1, 11, 1, '822', '2022-05-22 11:27:05', '2023-01-02 09:18:30', 'Android'),
(115, 1, 9, 1, '26', '2022-01-27 11:27:05', '2023-01-02 09:18:30', 'Android'),
(116, 1, 6, 1, '800', '2022-11-17 11:27:05', '2023-01-02 09:18:30', 'Android'),
(117, 1, 12, 1, '172', '2023-05-25 11:27:05', '2023-01-02 09:18:30', 'Android'),
(118, 1, 4, 1, '655', '2023-07-29 11:27:05', '2023-01-02 09:18:30', 'Android'),
(119, 1, 2, 1, '773', '2022-03-25 11:27:05', '2023-01-02 09:18:30', 'Android'),
(120, 1, 2, 1, '760', '2023-01-15 11:27:05', '2023-01-02 09:18:30', 'Android'),
(121, 1, 1, 1, '207', '2022-05-24 11:27:05', '2023-01-02 09:18:30', 'Android'),
(122, 1, 11, 1, '979', '2023-05-04 11:27:05', '2023-01-02 09:18:30', 'Android'),
(123, 1, 10, 1, '765', '2022-03-24 11:27:05', '2023-01-02 09:18:30', 'Android'),
(124, 1, 6, 1, '770', '2022-08-03 11:27:05', '2023-01-02 09:18:30', 'Android'),
(125, 1, 11, 1, '783', '2023-05-24 11:27:05', '2023-01-02 09:18:30', 'Android'),
(126, 1, 1, 1, '564', '2023-05-15 11:27:05', '2023-01-02 09:18:30', 'Android'),
(127, 1, 11, 1, '659', '2023-05-08 11:27:05', '2023-01-02 09:18:30', 'Android'),
(128, 1, 12, 1, '229', '2022-11-16 11:27:05', '2023-01-02 09:18:30', 'Android'),
(129, 1, 12, 1, '337', '2022-02-16 11:27:05', '2023-01-02 09:18:30', 'Android'),
(130, 1, 12, 1, '27', '2022-03-19 11:27:05', '2023-01-02 09:18:30', 'Android'),
(131, 1, 10, 1, '318', '2022-11-07 11:27:05', '2023-01-02 09:18:30', 'Android'),
(132, 1, 10, 1, '120', '2023-08-02 11:27:05', '2023-01-02 09:18:30', 'Android'),
(133, 1, 2, 1, '460', '2023-03-01 11:27:05', '2023-01-02 09:18:30', 'Android'),
(134, 1, 6, 1, '248', '2022-10-03 11:27:05', '2023-01-02 09:18:30', 'Android'),
(135, 1, 7, 1, '671', '2023-04-13 11:27:05', '2023-01-02 09:18:30', 'Android'),
(136, 1, 3, 1, '576', '2022-12-06 11:27:05', '2023-01-02 09:18:30', 'Android'),
(137, 1, 2, 1, '546', '2023-06-26 11:27:05', '2023-01-02 09:18:30', 'Android'),
(138, 1, 7, 1, '992', '2022-07-15 11:27:05', '2023-01-02 09:18:30', 'Android'),
(139, 1, 11, 1, '219', '2023-08-09 11:27:05', '2023-01-02 09:18:31', 'Android'),
(140, 1, 1, 1, '218', '2023-05-04 11:27:05', '2023-01-02 09:18:31', 'Android'),
(141, 1, 1, 1, '702', '2023-11-03 11:27:05', '2023-01-02 09:18:31', 'Android'),
(142, 1, 7, 1, '351', '2022-03-05 11:27:05', '2023-01-02 09:18:31', 'Android'),
(143, 1, 6, 1, '160', '2022-12-30 11:27:05', '2023-01-02 09:18:31', 'Android'),
(144, 1, 5, 1, '995', '2023-09-05 11:27:05', '2023-01-02 09:18:31', 'Android'),
(145, 1, 8, 1, '484', '2023-03-02 11:27:05', '2023-01-02 09:18:31', 'Android'),
(146, 1, 6, 1, '765', '2023-07-26 11:27:05', '2023-01-02 09:18:31', 'Android'),
(147, 1, 12, 1, '332', '2022-04-17 11:27:05', '2023-01-02 09:18:31', 'Android'),
(148, 1, 7, 1, '663', '2023-05-26 11:27:05', '2023-01-02 09:18:31', 'Android'),
(149, 1, 2, 1, '379', '2022-06-23 11:27:05', '2023-01-02 09:18:31', 'Android'),
(150, 1, 12, 1, '519', '2022-01-26 11:27:05', '2023-01-02 09:18:31', 'Android'),
(151, 1, 6, 1, '506', '2023-07-30 11:27:05', '2023-01-02 09:18:31', 'Android'),
(152, 1, 12, 1, '934', '2022-05-19 11:27:05', '2023-01-02 09:18:31', 'Android'),
(153, 1, 6, 1, '746', '2022-02-27 11:27:05', '2023-01-02 09:18:32', 'Android'),
(154, 1, 7, 1, '44', '2023-06-20 11:27:05', '2023-01-02 09:18:32', 'Android'),
(155, 1, 7, 1, '606', '2022-10-09 11:27:05', '2023-01-02 09:18:32', 'Android'),
(156, 1, 3, 1, '644', '2023-04-27 11:27:05', '2023-01-02 09:18:32', 'Android'),
(157, 1, 3, 1, '344', '2023-12-02 11:27:05', '2023-01-02 09:18:32', 'Android'),
(158, 1, 7, 1, '166', '2023-11-01 11:27:05', '2023-01-02 09:18:32', 'Android'),
(159, 1, 4, 1, '181', '2022-10-15 11:27:05', '2023-01-02 09:18:32', 'Android'),
(160, 1, 3, 1, '420', '2022-08-15 11:27:05', '2023-01-02 09:18:32', 'Android'),
(161, 1, 3, 1, '522', '2023-11-25 11:27:05', '2023-01-02 09:18:32', 'Android'),
(162, 1, 6, 1, '130', '2023-07-16 11:27:05', '2023-01-02 09:18:32', 'Android'),
(163, 1, 10, 1, '356', '2023-01-15 11:27:05', '2023-01-02 09:18:32', 'Android'),
(164, 1, 3, 1, '872', '2023-09-03 11:27:05', '2023-01-02 09:18:32', 'Android'),
(165, 1, 9, 1, '362', '2023-02-07 11:27:05', '2023-01-02 09:18:32', 'Android'),
(166, 1, 4, 1, '234', '2022-04-10 11:27:05', '2023-01-02 09:18:32', 'Android'),
(167, 1, 3, 1, '906', '2022-03-30 11:27:05', '2023-01-02 09:18:32', 'Android'),
(168, 1, 7, 1, '13', '2022-09-11 11:27:05', '2023-01-02 09:18:32', 'Android'),
(169, 1, 11, 1, '507', '2023-03-25 11:27:05', '2023-01-02 09:18:32', 'Android'),
(170, 1, 10, 1, '503', '2022-09-10 11:27:05', '2023-01-02 09:18:32', 'Android'),
(171, 1, 10, 1, '47', '2022-10-28 11:27:05', '2023-01-02 09:18:32', 'Android'),
(172, 1, 9, 1, '713', '2023-09-20 11:27:05', '2023-01-02 09:18:33', 'Android'),
(173, 1, 1, 1, '665', '2023-06-16 11:27:05', '2023-01-02 09:18:33', 'Android'),
(174, 1, 2, 1, '313', '2022-01-05 11:27:05', '2023-01-02 09:18:33', 'Android'),
(175, 1, 7, 1, '487', '2023-12-29 11:27:05', '2023-01-02 09:18:33', 'Android'),
(176, 1, 3, 1, '153', '2023-12-11 11:27:05', '2023-01-02 09:18:33', 'Android'),
(177, 1, 12, 1, '717', '2023-09-06 11:27:05', '2023-01-02 09:18:33', 'Android'),
(178, 1, 7, 1, '560', '2022-10-09 11:27:05', '2023-01-02 09:18:33', 'Android'),
(179, 1, 9, 1, '702', '2023-10-11 11:27:05', '2023-01-02 09:18:33', 'Android'),
(180, 1, 8, 1, '772', '2022-02-01 11:27:05', '2023-01-02 09:18:33', 'Android'),
(181, 1, 5, 1, '544', '2022-03-29 11:27:05', '2023-01-02 09:18:33', 'Android'),
(182, 1, 11, 1, '719', '2022-06-18 11:27:05', '2023-01-02 09:18:33', 'Android'),
(183, 1, 3, 1, '396', '2022-11-13 11:27:05', '2023-01-02 09:18:33', 'Android'),
(184, 1, 3, 1, '473', '2022-04-08 11:27:05', '2023-01-02 09:18:33', 'Android'),
(185, 1, 12, 1, '406', '2023-06-21 11:27:05', '2023-01-02 09:18:33', 'Android'),
(186, 1, 1, 1, '198', '2023-06-13 11:27:05', '2023-01-02 09:18:33', 'Android'),
(187, 1, 3, 1, '872', '2022-02-24 11:27:05', '2023-01-02 09:18:33', 'Android'),
(188, 1, 3, 1, '641', '2022-02-15 11:27:05', '2023-01-02 09:18:33', 'Android'),
(189, 1, 9, 1, '900', '2022-07-28 11:27:05', '2023-01-02 09:18:33', 'Android'),
(190, 1, 9, 1, '169', '2023-11-19 11:27:05', '2023-01-02 09:18:34', 'Android'),
(191, 1, 3, 1, '681', '2023-04-16 11:27:05', '2023-01-02 09:18:34', 'Android'),
(192, 1, 9, 1, '974', '2023-11-04 11:27:05', '2023-01-02 09:18:34', 'Android'),
(193, 1, 4, 1, '491', '2022-10-16 11:27:05', '2023-01-02 09:18:34', 'Android'),
(194, 1, 2, 1, '343', '2023-10-04 11:27:05', '2023-01-02 09:18:34', 'Android'),
(195, 1, 5, 1, '374', '2022-11-05 11:27:05', '2023-01-02 09:18:34', 'Android'),
(196, 1, 9, 1, '827', '2023-10-28 11:27:05', '2023-01-02 09:18:34', 'Android'),
(197, 1, 10, 1, '440', '2022-02-17 11:27:05', '2023-01-02 09:18:34', 'Android'),
(198, 1, 10, 1, '588', '2023-08-13 11:27:05', '2023-01-02 09:18:34', 'Android'),
(199, 1, 6, 1, '282', '2022-07-27 11:27:05', '2023-01-02 09:18:34', 'Android'),
(200, 1, 12, 1, '805', '2023-10-28 11:27:05', '2023-01-02 09:18:34', 'Android'),
(201, 1, 3, 1, '17', '2022-05-11 11:27:05', '2023-01-02 09:18:34', 'Android'),
(202, 1, 9, 1, '281', '2023-04-20 11:27:05', '2023-01-02 09:18:34', 'Android'),
(203, 1, 5, 1, '714', '2022-08-06 11:27:05', '2023-01-02 09:18:34', 'Android'),
(204, 1, 10, 1, '835', '2023-02-22 11:27:05', '2023-01-02 09:18:34', 'Android'),
(205, 1, 11, 1, '71', '2023-07-13 11:27:05', '2023-01-02 09:18:34', 'Android'),
(206, 1, 11, 1, '466', '2023-03-04 11:27:05', '2023-01-02 09:18:35', 'Android'),
(207, 1, 3, 1, '414', '2023-08-29 11:27:05', '2023-01-02 09:18:35', 'Android'),
(208, 1, 5, 1, '904', '2023-03-20 11:27:05', '2023-01-02 09:18:35', 'Android'),
(209, 1, 1, 1, '916', '2023-04-12 11:27:05', '2023-01-02 09:18:35', 'Android'),
(210, 1, 2, 1, '311', '2022-03-21 11:27:05', '2023-01-02 09:18:35', 'Android'),
(211, 1, 1, 1, '340', '2023-07-18 11:27:05', '2023-01-02 09:18:36', 'Android'),
(212, 1, 3, 1, '778', '2022-11-09 11:27:05', '2023-01-02 09:18:36', 'Android'),
(213, 1, 9, 1, '462', '2023-09-30 11:27:05', '2023-01-02 09:18:36', 'Android'),
(214, 1, 11, 1, '681', '2023-07-11 11:27:05', '2023-01-02 09:18:36', 'Android'),
(215, 1, 5, 1, '614', '2023-02-09 11:27:05', '2023-01-02 09:18:36', 'Android'),
(216, 1, 2, 1, '82', '2022-07-09 11:27:05', '2023-01-02 09:18:36', 'Android'),
(217, 1, 10, 1, '21', '2023-05-28 11:27:05', '2023-01-02 09:18:36', 'Android'),
(218, 1, 6, 1, '519', '2022-06-06 11:27:05', '2023-01-02 09:18:36', 'Android'),
(219, 1, 1, 1, '113', '2023-12-12 11:27:05', '2023-01-02 09:18:36', 'Android'),
(220, 1, 5, 1, '419', '2023-02-08 11:27:05', '2023-01-02 09:18:36', 'Android'),
(221, 1, 10, 1, '412', '2022-07-04 11:27:05', '2023-01-02 09:18:36', 'Android'),
(222, 1, 1, 1, '629', '2023-07-10 11:27:05', '2023-01-02 09:18:36', 'Android'),
(223, 1, 4, 1, '101', '2023-02-05 11:27:05', '2023-01-02 09:18:36', 'Android'),
(224, 1, 5, 1, '455', '2022-05-23 11:27:05', '2023-01-02 09:18:36', 'Android'),
(225, 1, 8, 1, '192', '2022-06-16 11:27:05', '2023-01-02 09:18:36', 'Android'),
(226, 1, 1, 1, '727', '2022-05-16 11:27:05', '2023-01-02 09:18:36', 'Android'),
(227, 1, 9, 1, '102', '2023-11-23 11:27:05', '2023-01-02 09:18:36', 'Android'),
(228, 1, 8, 1, '73', '2023-09-23 11:27:05', '2023-01-02 09:18:36', 'Android'),
(229, 1, 1, 1, '814', '2023-07-25 11:27:05', '2023-01-02 09:18:36', 'Android'),
(230, 1, 3, 1, '232', '2023-11-16 11:27:05', '2023-01-02 09:18:36', 'Android'),
(231, 1, 1, 1, '282', '2023-04-17 11:27:05', '2023-01-02 09:18:37', 'Android'),
(232, 1, 3, 1, '714', '2023-03-10 11:27:05', '2023-01-02 09:18:37', 'Android'),
(233, 1, 10, 1, '777', '2022-02-19 11:27:05', '2023-01-02 09:18:37', 'Android'),
(234, 1, 8, 1, '516', '2023-05-27 11:27:05', '2023-01-02 09:18:37', 'Android'),
(235, 1, 1, 1, '375', '2023-08-16 11:27:05', '2023-01-02 09:18:37', 'Android'),
(236, 1, 8, 1, '44', '2022-09-01 11:27:05', '2023-01-02 09:18:37', 'Android'),
(237, 1, 1, 1, '567', '2023-09-24 11:27:05', '2023-01-02 09:18:37', 'Android'),
(238, 1, 9, 1, '602', '2022-06-19 11:27:05', '2023-01-02 09:18:37', 'Android'),
(239, 1, 3, 1, '338', '2022-07-12 11:27:05', '2023-01-02 09:18:37', 'Android'),
(240, 1, 11, 1, '228', '2022-01-23 11:27:05', '2023-01-02 09:18:37', 'Android'),
(241, 1, 2, 1, '541', '2023-04-15 11:27:05', '2023-01-02 09:18:37', 'Android'),
(242, 1, 11, 1, '323', '2023-07-24 11:27:05', '2023-01-02 09:18:37', 'Android'),
(243, 1, 4, 1, '21', '2022-04-03 11:27:05', '2023-01-02 09:18:37', 'Android'),
(244, 1, 6, 1, '463', '2023-03-07 11:27:05', '2023-01-02 09:18:37', 'Android'),
(245, 1, 4, 1, '192', '2022-08-27 11:27:05', '2023-01-02 09:18:37', 'Android'),
(246, 1, 4, 1, '689', '2022-06-30 11:27:05', '2023-01-02 09:18:37', 'Android'),
(247, 1, 1, 1, '569', '2023-09-24 11:27:05', '2023-01-02 09:18:37', 'Android'),
(248, 1, 8, 1, '574', '2023-12-06 11:27:05', '2023-01-02 09:18:38', 'Android'),
(249, 1, 5, 1, '380', '2023-12-23 11:27:05', '2023-01-02 09:18:38', 'Android'),
(250, 1, 2, 1, '518', '2022-03-22 11:27:05', '2023-01-02 09:18:38', 'Android'),
(251, 1, 1, 1, '597', '2023-11-25 11:27:05', '2023-01-02 09:18:38', 'Android'),
(252, 1, 12, 1, '120', '2022-03-18 11:27:05', '2023-01-02 09:18:38', 'Android'),
(253, 1, 12, 1, '715', '2023-06-24 11:27:05', '2023-01-02 09:18:38', 'Android'),
(254, 1, 7, 1, '225', '2023-07-25 11:27:05', '2023-01-02 09:18:38', 'Android'),
(255, 1, 12, 1, '524', '2023-12-19 11:27:05', '2023-01-02 09:18:38', 'Android'),
(256, 1, 8, 1, '733', '2023-09-01 11:27:05', '2023-01-02 09:18:38', 'Android'),
(257, 1, 5, 1, '977', '2023-02-22 11:27:05', '2023-01-02 09:18:38', 'Android'),
(258, 1, 7, 1, '806', '2022-06-09 11:27:05', '2023-01-02 09:18:38', 'Android'),
(259, 1, 4, 1, '870', '2023-11-10 11:27:05', '2023-01-02 09:18:38', 'Android'),
(260, 1, 1, 1, '723', '2023-11-14 11:27:05', '2023-01-02 09:18:38', 'Android'),
(261, 1, 8, 1, '88', '2022-06-19 11:27:05', '2023-01-02 09:18:38', 'Android'),
(262, 1, 3, 1, '786', '2022-01-07 11:27:05', '2023-01-02 09:18:38', 'Android'),
(263, 1, 1, 1, '268', '2022-01-21 11:27:05', '2023-01-02 09:18:38', 'Android'),
(264, 1, 8, 1, '393', '2022-01-21 11:27:05', '2023-01-02 09:18:38', 'Android'),
(265, 1, 6, 1, '690', '2023-02-10 11:27:05', '2023-01-02 09:18:38', 'Android'),
(266, 1, 4, 1, '383', '2023-11-26 11:27:05', '2023-01-02 09:18:38', 'Android'),
(267, 1, 2, 1, '479', '2022-05-25 11:27:05', '2023-01-02 09:18:38', 'Android'),
(268, 1, 11, 1, '427', '2022-06-25 11:27:05', '2023-01-02 09:18:38', 'Android'),
(269, 1, 7, 1, '628', '2022-06-19 11:27:05', '2023-01-02 09:18:38', 'Android'),
(270, 1, 10, 1, '298', '2023-04-25 11:27:05', '2023-01-02 09:18:38', 'Android'),
(271, 1, 6, 1, '788', '2023-09-05 11:27:05', '2023-01-02 09:18:38', 'Android'),
(272, 1, 4, 1, '19', '2022-11-08 11:27:05', '2023-01-02 09:18:38', 'Android'),
(273, 1, 8, 1, '882', '2022-10-21 11:27:05', '2023-01-02 09:18:38', 'Android'),
(274, 1, 5, 1, '284', '2022-04-29 11:27:05', '2023-01-02 09:18:38', 'Android'),
(275, 1, 11, 1, '661', '2022-01-06 11:27:05', '2023-01-02 09:18:38', 'Android'),
(276, 1, 11, 1, '57', '2022-01-24 11:27:05', '2023-01-02 09:18:39', 'Android'),
(277, 1, 12, 1, '171', '2023-03-29 11:27:05', '2023-01-02 09:18:39', 'Android'),
(278, 1, 9, 1, '822', '2023-03-21 11:27:05', '2023-01-02 09:18:39', 'Android'),
(279, 1, 1, 1, '553', '2023-03-20 11:27:05', '2023-01-02 09:18:39', 'Android'),
(280, 1, 9, 1, '824', '2022-01-29 11:27:05', '2023-01-02 09:18:39', 'Android'),
(281, 1, 2, 1, '752', '2023-01-17 11:27:05', '2023-01-02 09:18:39', 'Android'),
(282, 1, 1, 1, '214', '2023-09-12 11:27:05', '2023-01-02 09:18:39', 'Android'),
(283, 1, 5, 1, '260', '2022-06-03 11:27:05', '2023-01-02 09:18:39', 'Android'),
(284, 1, 1, 1, '794', '2022-10-12 11:27:05', '2023-01-02 09:18:39', 'Android'),
(285, 1, 4, 1, '756', '2023-10-24 11:27:05', '2023-01-02 09:18:39', 'Android'),
(286, 1, 3, 1, '923', '2023-02-25 11:27:05', '2023-01-02 09:18:39', 'Android'),
(287, 1, 1, 1, '207', '2022-12-20 11:27:05', '2023-01-02 09:18:39', 'Android'),
(288, 1, 7, 1, '788', '2022-10-12 11:27:05', '2023-01-02 09:18:39', 'Android'),
(289, 1, 9, 1, '803', '2022-09-07 11:27:05', '2023-01-02 09:18:39', 'Android'),
(290, 1, 12, 1, '634', '2023-05-29 11:27:05', '2023-01-02 09:18:39', 'Android'),
(291, 1, 7, 1, '339', '2022-12-07 11:27:05', '2023-01-02 09:18:39', 'Android'),
(292, 1, 3, 1, '58', '2023-11-18 11:27:05', '2023-01-02 09:18:39', 'Android'),
(293, 1, 3, 1, '649', '2023-02-15 11:27:05', '2023-01-02 09:18:39', 'Android'),
(294, 1, 8, 1, '511', '2023-05-07 11:27:05', '2023-01-02 09:18:39', 'Android'),
(295, 1, 4, 1, '95', '2023-05-20 11:27:05', '2023-01-02 09:18:39', 'Android'),
(296, 1, 4, 1, '628', '2022-06-09 11:27:05', '2023-01-02 09:18:40', 'Android'),
(297, 1, 6, 1, '752', '2023-05-29 11:27:05', '2023-01-02 09:18:40', 'Android'),
(298, 1, 11, 1, '534', '2022-07-08 11:27:05', '2023-01-02 09:18:40', 'Android'),
(299, 1, 10, 1, '940', '2022-04-29 11:27:05', '2023-01-02 09:18:40', 'Android'),
(300, 1, 11, 1, '513', '2023-07-09 11:27:05', '2023-01-02 09:18:40', 'Android'),
(301, 6, 5, 1, '5', '2023-01-04', '2023-01-04 15:11:01', 'Max what field is this???'),
(302, 6, 1, 1, '10', '2023-01-18', '2023-01-18 10:49:13', 'Max what field is this???'),
(303, 6, 1, 1, '5', '2023-01-18', '2023-01-18 15:24:09', 'Max what field is this???'),
(304, 6, 1, 1, '5', '2023-01-22', '2023-01-22 12:43:02', 'Max what field is this???');

-- --------------------------------------------------------

--
-- Table structure for table `measurements_cooler`
--

CREATE TABLE `measurements_cooler` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `product_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_time` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `measurements_cooler`
--

INSERT INTO `measurements_cooler` (`id`, `user_id`, `product_id`, `amount`, `date_time`, `status`, `created_at`, `created_by`) VALUES
(1, 1, '05', '226', '2023-04-23 11:27:05', '', '2023-01-02 09:27:35', 'Android'),
(2, 1, '07', '66', '2023-07-26 11:27:05', '', '2023-01-02 09:27:35', 'Android'),
(3, 1, '03', '701', '2023-06-21 11:27:05', '', '2023-01-02 09:27:35', 'Android'),
(4, 1, '05', '337', '2023-03-20 11:27:05', '', '2023-01-02 09:27:35', 'Android'),
(5, 1, '06', '876', '2022-02-01 11:27:05', '', '2023-01-02 09:27:35', 'Android'),
(6, 1, '07', '659', '2023-02-06 11:27:05', '', '2023-01-02 09:27:35', 'Android'),
(7, 1, '11', '566', '2022-09-20 11:27:05', '', '2023-01-02 09:27:35', 'Android'),
(8, 1, '05', '188', '2022-05-01 11:27:05', '', '2023-01-02 09:27:35', 'Android'),
(9, 1, '05', '751', '2022-03-27 11:27:05', '', '2023-01-02 09:27:35', 'Android'),
(10, 1, '04', '678', '2023-02-08 11:27:05', '', '2023-01-02 09:27:35', 'Android'),
(11, 1, '08', '921', '2023-01-20 11:27:05', '', '2023-01-02 09:27:35', 'Android'),
(12, 1, '07', '329', '2022-05-19 11:27:05', '', '2023-01-02 09:27:35', 'Android'),
(13, 1, '01', '440', '2022-08-13 11:27:05', '', '2023-01-02 09:27:35', 'Android'),
(14, 1, '04', '725', '2023-09-28 11:27:05', '', '2023-01-02 09:27:35', 'Android'),
(15, 1, '09', '365', '2022-08-22 11:27:05', '', '2023-01-02 09:27:35', 'Android'),
(16, 1, '01', '44', '2022-04-30 11:27:05', '', '2023-01-02 09:27:35', 'Android'),
(17, 1, '06', '766', '2022-11-29 11:27:05', '', '2023-01-02 09:27:35', 'Android'),
(18, 1, '10', '862', '2023-01-16 11:27:05', '', '2023-01-02 09:27:35', 'Android'),
(19, 1, '09', '559', '2023-09-02 11:27:05', '', '2023-01-02 09:27:35', 'Android'),
(20, 1, '03', '316', '2023-07-06 11:27:05', '', '2023-01-02 09:27:36', 'Android'),
(21, 1, '12', '184', '2022-08-22 11:27:05', '', '2023-01-02 09:27:36', 'Android'),
(22, 1, '03', '186', '2022-02-08 11:27:05', '', '2023-01-02 09:27:36', 'Android'),
(23, 1, '08', '988', '2022-05-17 11:27:05', '', '2023-01-02 09:27:36', 'Android'),
(24, 1, '03', '858', '2023-11-19 11:27:05', '', '2023-01-02 09:27:36', 'Android'),
(25, 1, '07', '402', '2022-09-20 11:27:05', '', '2023-01-02 09:27:36', 'Android'),
(26, 1, '12', '462', '2023-04-29 11:27:05', '', '2023-01-02 09:27:36', 'Android'),
(27, 1, '09', '534', '2023-06-29 11:27:05', '', '2023-01-02 09:27:36', 'Android'),
(28, 1, '07', '626', '2023-12-08 11:27:05', '', '2023-01-02 09:27:36', 'Android'),
(29, 1, '11', '729', '2023-11-06 11:27:05', '', '2023-01-02 09:27:36', 'Android'),
(30, 1, '11', '30', '2023-08-15 11:27:05', '', '2023-01-02 09:27:36', 'Android'),
(31, 1, '08', '779', '2022-04-20 11:27:05', '', '2023-01-02 09:27:36', 'Android'),
(32, 1, '05', '936', '2022-06-26 11:27:05', '', '2023-01-02 09:27:36', 'Android'),
(33, 1, '02', '458', '2022-04-05 11:27:05', '', '2023-01-02 09:27:36', 'Android'),
(34, 1, '05', '145', '2023-05-29 11:27:05', '', '2023-01-02 09:27:36', 'Android'),
(35, 1, '07', '693', '2022-06-20 11:27:05', '', '2023-01-02 09:27:36', 'Android'),
(36, 1, '10', '952', '2023-12-04 11:27:05', '', '2023-01-02 09:27:36', 'Android'),
(37, 1, '04', '499', '2022-05-27 11:27:05', '', '2023-01-02 09:27:36', 'Android'),
(38, 1, '06', '655', '2022-06-30 11:27:05', '', '2023-01-02 09:27:36', 'Android'),
(39, 1, '02', '361', '2023-01-15 11:27:05', '', '2023-01-02 09:27:36', 'Android'),
(40, 1, '01', '645', '2023-04-24 11:27:05', '', '2023-01-02 09:27:36', 'Android'),
(41, 1, '02', '851', '2023-07-21 11:27:05', '', '2023-01-02 09:27:36', 'Android'),
(42, 1, '02', '66', '2022-04-02 11:27:05', '', '2023-01-02 09:27:36', 'Android'),
(43, 1, '09', '425', '2022-09-28 11:27:05', '', '2023-01-02 09:27:36', 'Android'),
(44, 1, '05', '831', '2023-11-12 11:27:05', '', '2023-01-02 09:27:36', 'Android'),
(45, 1, '01', '562', '2022-11-27 11:27:05', '', '2023-01-02 09:27:36', 'Android'),
(46, 1, '02', '459', '2022-07-02 11:27:05', '', '2023-01-02 09:27:36', 'Android'),
(47, 1, '04', '101', '2022-12-05 11:27:05', '', '2023-01-02 09:27:36', 'Android'),
(48, 1, '02', '44', '2022-05-19 11:27:05', '', '2023-01-02 09:27:36', 'Android'),
(49, 1, '12', '482', '2022-05-20 11:27:05', '', '2023-01-02 09:27:36', 'Android'),
(50, 1, '11', '671', '2022-02-16 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(51, 1, '03', '214', '2023-06-02 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(52, 1, '05', '221', '2022-02-12 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(53, 1, '05', '407', '2023-10-15 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(54, 1, '12', '792', '2022-10-18 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(55, 1, '03', '74', '2023-04-29 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(56, 1, '11', '454', '2023-11-05 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(57, 1, '06', '747', '2023-08-10 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(58, 1, '01', '527', '2023-11-16 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(59, 1, '05', '974', '2022-11-15 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(60, 1, '06', '813', '2022-10-14 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(61, 1, '03', '714', '2023-10-22 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(62, 1, '04', '380', '2022-04-04 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(63, 1, '04', '601', '2022-12-02 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(64, 1, '12', '378', '2022-03-20 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(65, 1, '07', '240', '2022-12-05 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(66, 1, '06', '764', '2022-03-21 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(67, 1, '05', '737', '2023-11-30 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(68, 1, '02', '883', '2023-08-18 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(69, 1, '07', '877', '2022-11-30 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(70, 1, '07', '229', '2022-12-06 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(71, 1, '09', '516', '2022-06-20 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(72, 1, '05', '885', '2023-07-03 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(73, 1, '10', '562', '2022-09-29 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(74, 1, '11', '269', '2023-09-06 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(75, 1, '02', '828', '2023-09-07 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(76, 1, '02', '565', '2022-05-06 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(77, 1, '12', '957', '2022-05-09 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(78, 1, '01', '567', '2022-12-11 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(79, 1, '04', '228', '2022-01-05 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(80, 1, '10', '744', '2022-12-26 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(81, 1, '10', '300', '2023-01-21 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(82, 1, '07', '644', '2022-07-14 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(83, 1, '09', '576', '2023-09-10 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(84, 1, '03', '158', '2023-05-28 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(85, 1, '09', '83', '2023-06-29 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(86, 1, '04', '564', '2022-06-19 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(87, 1, '08', '400', '2023-06-25 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(88, 1, '10', '774', '2022-05-02 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(89, 1, '11', '776', '2022-07-09 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(90, 1, '06', '636', '2023-08-10 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(91, 1, '05', '213', '2022-05-14 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(92, 1, '05', '60', '2023-10-16 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(93, 1, '04', '872', '2023-06-20 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(94, 1, '07', '38', '2023-12-02 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(95, 1, '09', '92', '2023-05-06 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(96, 1, '03', '213', '2023-06-18 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(97, 1, '05', '686', '2022-02-09 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(98, 1, '02', '574', '2022-12-15 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(99, 1, '11', '170', '2023-06-29 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(100, 1, '09', '910', '2023-06-12 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(101, 1, '02', '220', '2023-12-21 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(102, 1, '08', '361', '2023-06-05 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(103, 1, '03', '145', '2022-12-17 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(104, 1, '07', '993', '2022-11-02 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(105, 1, '10', '363', '2023-03-13 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(106, 1, '11', '447', '2022-06-25 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(107, 1, '07', '885', '2023-03-27 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(108, 1, '09', '107', '2023-02-06 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(109, 1, '03', '786', '2023-01-20 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(110, 1, '03', '131', '2022-04-10 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(111, 1, '06', '371', '2023-04-01 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(112, 1, '12', '340', '2023-04-12 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(113, 1, '12', '817', '2023-05-19 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(114, 1, '10', '255', '2023-05-21 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(115, 1, '02', '910', '2023-08-18 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(116, 1, '02', '345', '2023-01-11 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(117, 1, '05', '770', '2023-08-15 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(118, 1, '06', '828', '2023-05-01 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(119, 1, '02', '877', '2023-06-17 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(120, 1, '12', '389', '2022-06-05 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(121, 1, '02', '177', '2022-06-30 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(122, 1, '05', '999', '2022-10-29 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(123, 1, '12', '74', '2023-06-23 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(124, 1, '11', '33', '2022-12-09 11:27:05', '', '2023-01-02 09:27:37', 'Android'),
(125, 1, '11', '704', '2023-08-17 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(126, 1, '09', '940', '2023-08-06 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(127, 1, '01', '301', '2023-05-08 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(128, 1, '03', '52', '2022-05-18 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(129, 1, '10', '987', '2023-04-28 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(130, 1, '11', '72', '2022-12-27 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(131, 1, '10', '152', '2022-08-27 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(132, 1, '04', '944', '2022-05-02 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(133, 1, '11', '616', '2022-01-13 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(134, 1, '03', '805', '2022-08-10 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(135, 1, '09', '617', '2022-08-09 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(136, 1, '06', '514', '2022-12-21 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(137, 1, '06', '623', '2023-08-01 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(138, 1, '01', '571', '2022-10-07 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(139, 1, '02', '404', '2022-09-01 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(140, 1, '05', '326', '2023-08-10 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(141, 1, '05', '429', '2022-06-12 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(142, 1, '05', '924', '2023-12-21 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(143, 1, '02', '504', '2022-04-14 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(144, 1, '05', '493', '2023-12-22 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(145, 1, '09', '377', '2022-02-27 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(146, 1, '07', '479', '2022-05-15 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(147, 1, '06', '873', '2023-03-02 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(148, 1, '02', '925', '2023-07-16 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(149, 1, '09', '797', '2023-09-01 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(150, 1, '10', '676', '2023-01-15 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(151, 1, '07', '36', '2023-11-28 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(152, 1, '08', '777', '2022-12-25 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(153, 1, '02', '174', '2023-05-21 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(154, 1, '09', '640', '2022-04-02 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(155, 1, '11', '419', '2022-09-16 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(156, 1, '05', '389', '2022-04-21 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(157, 1, '03', '839', '2022-12-01 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(158, 1, '04', '262', '2022-10-17 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(159, 1, '03', '830', '2023-06-24 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(160, 1, '12', '224', '2022-07-15 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(161, 1, '09', '961', '2022-02-24 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(162, 1, '11', '362', '2022-09-21 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(163, 1, '02', '691', '2022-03-27 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(164, 1, '08', '275', '2023-10-28 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(165, 1, '08', '89', '2022-08-21 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(166, 1, '11', '321', '2023-10-19 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(167, 1, '03', '406', '2023-02-01 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(168, 1, '04', '225', '2022-12-06 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(169, 1, '01', '878', '2022-10-26 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(170, 1, '01', '862', '2023-05-23 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(171, 1, '09', '824', '2022-07-19 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(172, 1, '04', '409', '2022-03-02 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(173, 1, '08', '10', '2022-12-23 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(174, 1, '01', '463', '2023-03-22 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(175, 1, '08', '514', '2022-08-14 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(176, 1, '12', '28', '2023-04-05 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(177, 1, '12', '464', '2022-09-29 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(178, 1, '06', '894', '2023-01-09 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(179, 1, '11', '558', '2023-06-24 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(180, 1, '09', '325', '2023-06-06 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(181, 1, '10', '973', '2022-03-26 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(182, 1, '10', '930', '2022-02-11 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(183, 1, '10', '955', '2023-04-18 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(184, 1, '12', '914', '2023-10-07 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(185, 1, '09', '915', '2022-09-11 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(186, 1, '02', '233', '2023-05-21 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(187, 1, '10', '764', '2023-06-07 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(188, 1, '07', '765', '2022-03-09 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(189, 1, '12', '26', '2023-08-19 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(190, 1, '06', '852', '2023-05-02 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(191, 1, '02', '851', '2023-06-26 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(192, 1, '04', '392', '2022-05-18 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(193, 1, '10', '437', '2023-12-20 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(194, 1, '09', '714', '2022-02-03 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(195, 1, '03', '631', '2022-11-16 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(196, 1, '01', '490', '2023-03-29 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(197, 1, '12', '569', '2023-11-30 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(198, 1, '06', '941', '2023-03-10 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(199, 1, '05', '921', '2022-09-06 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(200, 1, '04', '306', '2022-12-08 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(201, 1, '06', '474', '2023-12-18 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(202, 1, '03', '266', '2023-02-28 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(203, 1, '04', '531', '2022-07-08 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(204, 1, '12', '715', '2023-09-09 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(205, 1, '12', '369', '2023-03-28 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(206, 1, '01', '790', '2023-04-02 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(207, 1, '07', '149', '2023-08-23 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(208, 1, '10', '42', '2023-06-13 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(209, 1, '06', '771', '2023-10-08 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(210, 1, '04', '622', '2022-04-17 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(211, 1, '01', '546', '2022-11-10 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(212, 1, '07', '339', '2022-01-18 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(213, 1, '01', '176', '2023-08-29 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(214, 1, '04', '822', '2022-07-03 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(215, 1, '01', '402', '2022-02-02 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(216, 1, '04', '61', '2022-05-14 11:27:05', '', '2023-01-02 09:27:38', 'Android'),
(217, 1, '02', '958', '2023-04-09 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(218, 1, '03', '941', '2022-12-07 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(219, 1, '06', '398', '2023-11-25 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(220, 1, '09', '401', '2022-02-17 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(221, 1, '01', '71', '2022-08-09 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(222, 1, '01', '75', '2023-02-13 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(223, 1, '04', '222', '2022-07-28 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(224, 1, '06', '323', '2023-04-18 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(225, 1, '06', '567', '2023-02-16 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(226, 1, '01', '609', '2022-10-13 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(227, 1, '07', '458', '2022-05-17 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(228, 1, '07', '746', '2023-05-21 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(229, 1, '10', '95', '2022-04-25 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(230, 1, '02', '951', '2022-12-12 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(231, 1, '07', '210', '2023-01-26 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(232, 1, '11', '964', '2022-09-03 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(233, 1, '09', '615', '2023-07-26 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(234, 1, '10', '397', '2022-07-08 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(235, 1, '02', '67', '2022-12-22 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(236, 1, '08', '245', '2022-11-04 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(237, 1, '11', '488', '2023-12-02 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(238, 1, '07', '512', '2022-06-18 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(239, 1, '09', '725', '2023-01-19 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(240, 1, '12', '587', '2022-06-06 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(241, 1, '10', '469', '2023-07-30 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(242, 1, '08', '774', '2023-10-02 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(243, 1, '11', '655', '2023-05-16 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(244, 1, '05', '631', '2023-01-15 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(245, 1, '06', '199', '2022-01-12 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(246, 1, '10', '910', '2023-06-02 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(247, 1, '12', '941', '2022-09-02 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(248, 1, '03', '671', '2023-10-14 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(249, 1, '09', '893', '2023-12-06 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(250, 1, '05', '725', '2022-09-02 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(251, 1, '07', '562', '2022-06-23 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(252, 1, '07', '829', '2022-11-14 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(253, 1, '03', '192', '2023-08-02 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(254, 1, '03', '308', '2022-07-17 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(255, 1, '03', '394', '2022-02-11 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(256, 1, '04', '39', '2023-10-07 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(257, 1, '04', '779', '2023-07-05 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(258, 1, '12', '124', '2022-12-09 11:27:05', '', '2023-01-02 09:27:39', 'Android'),
(259, 1, '12', '106', '2022-02-25 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(260, 1, '05', '349', '2022-11-27 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(261, 1, '09', '617', '2023-09-29 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(262, 1, '04', '573', '2022-09-15 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(263, 1, '11', '608', '2023-01-01 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(264, 1, '08', '52', '2022-05-26 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(265, 1, '01', '711', '2022-06-12 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(266, 1, '10', '484', '2022-02-11 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(267, 1, '02', '455', '2023-02-07 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(268, 1, '08', '263', '2022-06-05 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(269, 1, '04', '675', '2023-04-07 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(270, 1, '12', '23', '2022-07-25 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(271, 1, '02', '787', '2023-09-28 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(272, 1, '07', '565', '2022-06-24 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(273, 1, '05', '381', '2022-10-18 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(274, 1, '03', '397', '2022-08-01 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(275, 1, '10', '40', '2022-11-29 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(276, 1, '01', '469', '2023-04-15 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(277, 1, '07', '87', '2023-12-15 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(278, 1, '01', '511', '2023-07-23 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(279, 1, '09', '973', '2022-10-04 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(280, 1, '12', '466', '2023-02-29 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(281, 1, '01', '361', '2023-10-01 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(282, 1, '12', '830', '2022-08-09 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(283, 1, '06', '88', '2023-06-06 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(284, 1, '04', '495', '2023-11-01 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(285, 1, '03', '166', '2023-02-21 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(286, 1, '08', '93', '2023-10-19 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(287, 1, '02', '327', '2023-10-19 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(288, 1, '12', '427', '2022-06-18 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(289, 1, '04', '928', '2023-05-06 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(290, 1, '02', '590', '2022-08-23 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(291, 1, '03', '492', '2023-01-29 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(292, 1, '11', '654', '2023-11-29 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(293, 1, '08', '258', '2023-01-30 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(294, 1, '11', '550', '2023-12-24 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(295, 1, '01', '203', '2022-01-29 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(296, 1, '10', '982', '2022-12-26 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(297, 1, '01', '388', '2023-05-20 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(298, 1, '02', '361', '2023-04-21 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(299, 1, '05', '87', '2022-10-30 11:27:05', '', '2023-01-02 09:27:40', 'Android'),
(300, 1, '06', '763', '2022-10-12 11:27:05', '', '2023-01-02 09:27:40', 'Android');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mpesa_transactions_b2c`
--

CREATE TABLE `mpesa_transactions_b2c` (
  `id` int(10) UNSIGNED NOT NULL,
  `amount` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_ref` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `partya` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `partyb` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `conversation_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `originator_conversation_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_timestamp` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mpesa_transactions_b2c`
--

INSERT INTO `mpesa_transactions_b2c` (`id`, `amount`, `transaction_ref`, `partya`, `partyb`, `description`, `conversation_id`, `originator_conversation_id`, `transaction_timestamp`, `created_at`, `updated_at`) VALUES
(1, '1200', 'RAM2WEAHV8', '251349', '254710293886', 'Loan disbursement', 'AG_20230122_20207b93dd840ef2bf08', '120611-5584297-1', '2023-01-22', NULL, NULL),
(2, '1200', 'RAM2WEAHV8', '251349', '254710293886', 'Loan disbursement', 'AG_20230122_20207b93dd840ef2bf08', '120611-5584297-1', '2023-01-22', NULL, NULL),
(3, '1200', 'RAM2WEAHV8', '251349', '254710293886', 'Loan disbursement', 'AG_20230122_20207b93dd840ef2bf08', '120611-5584297-1', '2023-01-22', NULL, NULL),
(4, '1200', 'RAM2WEAHV8', '251349', '254710293886', 'Loan disbursement', 'AG_20230122_20207b93dd840ef2bf08', '120611-5584297-1', '2023-01-22', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mpesa_transactions_c2b`
--

CREATE TABLE `mpesa_transactions_c2b` (
  `id` int(10) UNSIGNED NOT NULL,
  `transaction_ref` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_ref` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `patya` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `partyb` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_name` varchar(190) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_descritiption` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `merchant_request_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `checkout_request_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `response_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `response_description` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_message` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `completed_date_time` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'initiated',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mpesa_transactions_c2b`
--

INSERT INTO `mpesa_transactions_c2b` (`id`, `transaction_ref`, `account_ref`, `patya`, `partyb`, `customer_name`, `amount`, `payment_descritiption`, `merchant_request_id`, `checkout_request_id`, `response_code`, `response_description`, `customer_message`, `phone`, `completed_date_time`, `status`, `created_at`, `updated_at`) VALUES
(18, 'RJ9HQF23L', '1', '254710293886', '539100', 'erick ivuto', '1515', 'loan repayment', '6098-149255129-1', 'ws_CO_05012023145208316710293886', '0', 'Success. Request accepted for processing', 'Success. Request accepted for processing', NULL, NULL, 'initiated', '2023-01-05 11:52:09', NULL),
(19, 'RJ8HQF545', '1', '254710293886', '539100', 'erick ivuto', '1515', 'loan repayment', '314-29175384-1', 'ws_CO_18012023132344265710293886', '0', 'Success. Request accepted for processing', 'Success. Request accepted for processing', NULL, NULL, 'initiated', '2023-01-18 10:23:44', NULL),
(20, NULL, '1', '254758547654', '539100', NULL, '1515', 'loan repayment', '63286-196162843-1', 'ws_CO_20012023144332674758547654', '0', 'Success. Request accepted for processing', 'Success. Request accepted for processing', NULL, NULL, 'initiated', '2023-01-20 11:43:33', NULL),
(21, NULL, '1', '254710293886', '539100', NULL, '1', 'loan repayment', '2276-200628891-1', 'ws_CO_21012023194721183710293886', '0', 'Success. Request accepted for processing', 'Success. Request accepted for processing', NULL, NULL, 'initiated', '2023-01-21 16:47:21', NULL),
(22, 'RAL4VUM7XG', '1', '2547 ***** 886', '539100', 'erick', '1.00', 'loan repayment', NULL, NULL, NULL, NULL, NULL, '2547 ***** 886', '2023-01-21 07:47:31', 'success', '2023-01-21 16:47:31', NULL),
(23, NULL, '1', '254710293886', '539100', NULL, '2727', 'loan repayment', '65454-4198970-1', 'ws_CO_22012023010134682710293886', '0', 'Success. Request accepted for processing', 'Success. Request accepted for processing', NULL, NULL, 'initiated', '2023-01-21 22:01:35', NULL),
(24, NULL, '1', '254710293886', '539100', NULL, '1', 'loan repayment', '10753-142711941-1', 'ws_CO_22012023010301236710293886', '0', 'Success. Request accepted for processing', 'Success. Request accepted for processing', NULL, NULL, 'initiated', '2023-01-21 22:03:01', NULL),
(25, 'RAM0WE9NVG', '1', '2547 ***** 886', '539100', 'erick', '1.00', 'loan repayment', NULL, NULL, NULL, NULL, NULL, '2547 ***** 886', '2023-01-22 01:03:10', 'success', '2023-01-21 22:03:10', NULL),
(26, NULL, '1', '254710293886', '539100', NULL, '1', 'loan repayment', '103169-164905949-1', 'ws_CO_22012023011044347710293886', '0', 'Success. Request accepted for processing', 'Success. Request accepted for processing', NULL, NULL, 'initiated', '2023-01-21 22:10:44', NULL),
(27, NULL, '1', '254710293886', '539100', NULL, '1', 'loan repayment', '42573-111935-1', 'ws_CO_22012023011139621710293886', '0', 'Success. Request accepted for processing', 'Success. Request accepted for processing', NULL, NULL, 'initiated', '2023-01-21 22:11:39', NULL),
(28, 'RAM1WECU8P', '1', '2547 ***** 886', '539100', 'erick', '1.00', 'loan repayment', NULL, NULL, NULL, NULL, NULL, '2547 ***** 886', '2023-01-22 01:11:47', 'success', '2023-01-21 22:11:47', NULL),
(29, 'RAM6WEEP4W', '1', '2547 ***** 886', '539100', 'erick', '2.00', 'loan repayment', NULL, NULL, NULL, NULL, NULL, '2547 ***** 886', '2023-01-22 01:16:38', 'success', '2023-01-21 22:16:38', NULL),
(30, NULL, '1', '254710293886', '539100', NULL, '1', 'loan repayment', '32483-152542325-1', 'ws_CO_22012023015057241710293886', '0', 'Success. Request accepted for processing', 'Success. Request accepted for processing', NULL, NULL, 'initiated', '2023-01-21 22:50:57', NULL),
(31, 'RAM8WEOWO2', '1', '2547 ***** 886', '539100', 'erick', '1.00', 'loan repayment', NULL, NULL, NULL, NULL, NULL, '2547 ***** 886', '2023-01-22 01:51:07', 'success', '2023-01-21 22:51:07', NULL),
(32, 'RAM3WEPD13', '1', '2547 ***** 886', '539100', 'erick', '2.00', 'loan repayment', NULL, NULL, NULL, NULL, NULL, '2547 ***** 886', '2023-01-22 01:53:13', 'success', '2023-01-21 22:53:13', NULL),
(33, NULL, '1', '254710293886', '539100', NULL, '1', 'loan repayment', '91540-4976898-1', 'ws_CO_22012023015744706710293886', '0', 'Success. Request accepted for processing', 'Success. Request accepted for processing', NULL, NULL, 'initiated', '2023-01-21 22:57:45', NULL),
(34, 'RAM7WEQE8N', '1', '2547 ***** 886', '539100', 'erick', '1.00', 'loan repayment', NULL, NULL, NULL, NULL, NULL, '2547 ***** 886', '2023-01-22 01:57:53', 'success', '2023-01-21 22:57:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `tax_id` int(10) UNSIGNED NOT NULL,
  `salary_setup_id` int(10) UNSIGNED NOT NULL,
  `pay_date` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `unit_id`, `created_at`, `created_by`) VALUES
(1, 'Milk', 1, '2022-12-09 16:57:37', '1'),
(2, 'Water', 1, '2023-01-01 20:22:09', ''),
(3, 'Tea', 2, '2023-01-01 20:22:09', ''),
(4, 'Coffee', 2, '2023-01-01 20:22:09', ''),
(5, 'Maize', 2, '2023-01-01 20:22:09', ''),
(6, 'Sugarcane', 2, '2023-01-01 20:22:09', ''),
(7, 'Wheat', 2, '2023-01-01 20:22:09', ''),
(8, 'Chicken meat', 2, '2023-01-01 20:22:09', ''),
(9, 'Beef', 2, '2023-01-01 20:22:09', ''),
(10, 'Pork', 2, '2023-01-01 20:22:09', ''),
(11, 'Beans', 2, '2023-01-01 20:22:09', '');

-- --------------------------------------------------------

--
-- Table structure for table `product_prices`
--

CREATE TABLE `product_prices` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `cooperative_id` int(10) UNSIGNED NOT NULL,
  `price` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `selling_price` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_prices`
--

INSERT INTO `product_prices` (`id`, `product_id`, `cooperative_id`, `price`, `selling_price`, `created_at`, `created_by`) VALUES
(1, 1, 1, '50', '60', '2022-12-09 17:02:23', 'Android'),
(2, 3, 3, '128', '343', '2023-01-01 20:36:05', 'Android'),
(3, 4, 4, '141', '437', '2023-01-01 20:36:06', 'Android'),
(4, 5, 5, '472', '606', '2023-01-01 20:36:06', 'Android'),
(5, 6, 6, '519', '670', '2023-01-01 20:36:06', 'Android'),
(6, 7, 7, '791', '880', '2023-01-01 20:36:06', 'Android'),
(7, 8, 8, '303', '362', '2023-01-01 20:36:06', 'Android'),
(8, 9, 9, '718', '876', '2023-01-01 20:36:06', 'Android'),
(9, 10, 10, '136', '220', '2023-01-01 20:36:06', 'Android'),
(10, 11, 11, '491', '727', '2023-01-01 20:36:06', 'Android');

-- --------------------------------------------------------

--
-- Table structure for table `salary_setup`
--

CREATE TABLE `salary_setup` (
  `id` int(10) UNSIGNED NOT NULL,
  `basic` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `commission` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `wages` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `savings`
--

CREATE TABLE `savings` (
  `id` int(11) NOT NULL,
  `user_id` varchar(109) DEFAULT NULL,
  `amount` varchar(109) DEFAULT NULL,
  `available_amount_with_interest` varchar(109) DEFAULT NULL,
  `next_interest_date` varchar(109) DEFAULT NULL,
  `deposit_method` varchar(109) DEFAULT NULL,
  `transaction_reference` varchar(109) DEFAULT NULL,
  `deposit_name` varchar(100) DEFAULT NULL,
  `partya` varchar(100) DEFAULT NULL,
  `partyb` varchar(100) DEFAULT NULL,
  `date_time_deposited` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `savings`
--

INSERT INTO `savings` (`id`, `user_id`, `amount`, `available_amount_with_interest`, `next_interest_date`, `deposit_method`, `transaction_reference`, `deposit_name`, `partya`, `partyb`, `date_time_deposited`) VALUES
(1, '1', '5600', '5600', NULL, 'MPESA', 'DYUVSDDC', 'erick ivuto', '0710293886', '539100', '2023-01-18 08:00:04'),
(2, '1', '1.00', '1.00', '2023-02-20', 'MPESA', 'RAL9VODT0H', 'erick', '2547 ***** 886', '539100', '2023-01-21 16:02:37'),
(3, '1', '1.00', '1.00', '2023-02-20', 'MPESA', 'RAL5VVDHPD', 'erick', '2547 ***** 886', '539100', '2023-01-21 16:52:57'),
(4, '1', NULL, NULL, '2023-02-20', NULL, NULL, NULL, NULL, NULL, '2023-01-21 16:55:50'),
(5, '1', NULL, NULL, '2023-02-20', NULL, NULL, NULL, NULL, NULL, '2023-01-21 16:55:59'),
(6, '1', NULL, NULL, '2023-02-20', NULL, NULL, NULL, NULL, NULL, '2023-01-21 16:56:27'),
(7, '1', '1.00', '1.00', '2023-02-20', 'MPESA', 'RAL2VW7D68', 'erick', '2547 ***** 886', '539100', '2023-01-21 16:59:04'),
(8, '1', NULL, NULL, '2023-02-20', NULL, NULL, NULL, NULL, NULL, '2023-01-21 17:04:59'),
(9, '1', NULL, NULL, '2023-02-20', NULL, NULL, NULL, NULL, NULL, '2023-01-21 17:14:51');

-- --------------------------------------------------------

--
-- Table structure for table `sent_sms`
--

CREATE TABLE `sent_sms` (
  `id` int(11) NOT NULL,
  `date_sent` varchar(100) DEFAULT NULL,
  `user_id` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `taxes`
--

CREATE TABLE `taxes` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `name`, `created_at`, `created_by`) VALUES
(1, 'Liters', '2022-12-09 17:01:43', '1'),
(2, 'Kilograms', '2023-01-04 15:09:34', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(30) DEFAULT NULL,
  `fullname` varchar(50) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `location_id` varchar(200) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `otp_login` varchar(30) DEFAULT NULL,
  `otp_porter` varchar(30) DEFAULT NULL,
  `id_number` varchar(30) DEFAULT NULL,
  `user_type` varchar(3) DEFAULT NULL,
  `user_role` varchar(3) DEFAULT NULL,
  `card_number` varchar(30) DEFAULT NULL,
  `cooperative_id` varchar(100) NOT NULL,
  `date_time_created` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `fullname`, `email`, `phone`, `location_id`, `password`, `otp_login`, `otp_porter`, `id_number`, `user_type`, `user_role`, `card_number`, `cooperative_id`, `date_time_created`) VALUES
(1, 'person1', 'Erick Ivuto', 'eivuto@gmail.com', '0710293886', 'Nairobi,Kenya', '9d4e1e23bd5b727046a9e3b4b7db57bd8d6ee684', NULL, NULL, '36808815', '1', '1', NULL, '1', NULL),
(2, 'agrovet1', 'Example agrovet 1', 'eivuto1@gmail.com', '0710293886', 'Nairobi,Kenya', '9d4e1e23bd5b727046a9e3b4b7db57bd8d6ee684', NULL, NULL, '36808828', '2', '1', NULL, '1', NULL),
(3, 'agrovet2', 'Example agrovet 2', 'eivuto1@gmail.com', '0710293886', 'Nairobi,Kenya', '9d4e1e23bd5b727046a9e3b4b7db57bd8d6ee684', NULL, NULL, '36808819', '2', '1', NULL, '1', NULL),
(4, 'agrovet3', 'Example agrovet 3', 'eivuto1@gmail.com', '0710293886', 'Nairobi,Kenya', '9d4e1e23bd5b727046a9e3b4b7db57bd8d6ee684', NULL, NULL, '36808818', '2', '1', NULL, '2', NULL),
(5, 'agrovet4', 'Example agrovet 4', 'eivuto1@gmail.com', '0710293886', 'Nairobi,Kenya', '9d4e1e23bd5b727046a9e3b4b7db57bd8d6ee684', NULL, NULL, '36808820', '2', '1', NULL, '1', NULL),
(6, 'porter1', 'Example agrovet 4', 'eivuto1@gmail.com', '0710293886', 'Nairobi,Kenya', '9d4e1e23bd5b727046a9e3b4b7db57bd8d6ee684', NULL, NULL, '36808824', '3', '1', NULL, '1', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `id` int(11) NOT NULL,
  `user_role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`id`, `user_role`) VALUES
(1, 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

CREATE TABLE `user_type` (
  `id` int(11) NOT NULL,
  `user_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_type`
--

INSERT INTO `user_type` (`id`, `user_type`) VALUES
(1, 'farmer'),
(2, 'agrovet'),
(3, 'porter');

-- --------------------------------------------------------

--
-- Table structure for table `withdrawals_savings`
--

CREATE TABLE `withdrawals_savings` (
  `id` int(11) NOT NULL,
  `user_id` varchar(109) DEFAULT NULL,
  `amount` varchar(109) DEFAULT NULL,
  `date_time` varchar(109) DEFAULT NULL,
  `phone_sent` varchar(109) DEFAULT NULL,
  `mpesa_ref` varchar(109) DEFAULT NULL,
  `conversation_id` varchar(109) DEFAULT NULL,
  `originator_conversation_id` varchar(109) DEFAULT NULL,
  `transaction_cost` varchar(109) DEFAULT NULL,
  `status` varchar(109) DEFAULT 'initiated'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `withdrawals_savings`
--

INSERT INTO `withdrawals_savings` (`id`, `user_id`, `amount`, `date_time`, `phone_sent`, `mpesa_ref`, `conversation_id`, `originator_conversation_id`, `transaction_cost`, `status`) VALUES
(13, '1', '1000', NULL, '254710293886', NULL, 'AG_20230121_20506baab57d1a144706', '65458-3877872-1', NULL, 'initiated'),
(14, '1', '1000', NULL, '254710293886', NULL, 'AG_20230121_2020468ec35635b6b351', '59555-201068663-1', NULL, 'initiated'),
(15, '1', '1000', NULL, '254710293886', NULL, 'AG_20230121_2020108fd0b06f7b4031', '116785-2987479-1', '20', 'initiated'),
(16, '1', '1000', '2023-01-06 10:10:06', '254710293886', NULL, 'AG_20230121_20604e3241844e471d86', '32503-152215366-1', '20', 'failed'),
(17, '1', '1000', NULL, '254710293886', NULL, 'AG_20230121_20601763c2bcb4e1a9ca', '107740-4726615-1', '20', 'initiated'),
(18, '1', '1000', NULL, '254710293886', NULL, 'AG_20230121_204041697c9afc3b5d6c', '103180-164606321-1', '20', 'initiated'),
(19, '1', '1000', NULL, '254710293886', NULL, 'AG_20230121_20302926e0beadc1ba05', '5469-204359982-1', '20', 'initiated'),
(20, '1', '1000', '2023-01-26 09:46:26', '25710293886', 'RAL9W9XO2J', 'AG_20230121_20606e9dd71cb984a055', '53277-197802135-1', '20', 'active'),
(21, '1', '1000', '2023-01-26 09:46:26', '25710293886', 'RAL2WA1M66', 'AG_20230121_202074c69bb81577f57c', '116781-3031003-1', '20', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `app_settings`
--
ALTER TABLE `app_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cooperatives`
--
ALTER TABLE `cooperatives`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `disbursed_goods_from_agrovets`
--
ALTER TABLE `disbursed_goods_from_agrovets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `disbursed_goods_from_agrovets_repayments`
--
ALTER TABLE `disbursed_goods_from_agrovets_repayments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `disbursed_loans`
--
ALTER TABLE `disbursed_loans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses_item`
--
ALTER TABLE `expenses_item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_repayment`
--
ALTER TABLE `loan_repayment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `measurements`
--
ALTER TABLE `measurements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `measurements_cooler`
--
ALTER TABLE `measurements_cooler`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mpesa_transactions_b2c`
--
ALTER TABLE `mpesa_transactions_b2c`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mpesa_transactions_c2b`
--
ALTER TABLE `mpesa_transactions_c2b`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_prices`
--
ALTER TABLE `product_prices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salary_setup`
--
ALTER TABLE `salary_setup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `savings`
--
ALTER TABLE `savings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sent_sms`
--
ALTER TABLE `sent_sms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `taxes`
--
ALTER TABLE `taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdrawals_savings`
--
ALTER TABLE `withdrawals_savings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `app_settings`
--
ALTER TABLE `app_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cooperatives`
--
ALTER TABLE `cooperatives`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `disbursed_goods_from_agrovets`
--
ALTER TABLE `disbursed_goods_from_agrovets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `disbursed_goods_from_agrovets_repayments`
--
ALTER TABLE `disbursed_goods_from_agrovets_repayments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `disbursed_loans`
--
ALTER TABLE `disbursed_loans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses_item`
--
ALTER TABLE `expenses_item`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_repayment`
--
ALTER TABLE `loan_repayment`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `measurements`
--
ALTER TABLE `measurements`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=305;

--
-- AUTO_INCREMENT for table `measurements_cooler`
--
ALTER TABLE `measurements_cooler`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=301;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mpesa_transactions_b2c`
--
ALTER TABLE `mpesa_transactions_b2c`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `mpesa_transactions_c2b`
--
ALTER TABLE `mpesa_transactions_c2b`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `product_prices`
--
ALTER TABLE `product_prices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `salary_setup`
--
ALTER TABLE `salary_setup`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `savings`
--
ALTER TABLE `savings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `sent_sms`
--
ALTER TABLE `sent_sms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `taxes`
--
ALTER TABLE `taxes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_type`
--
ALTER TABLE `user_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `withdrawals_savings`
--
ALTER TABLE `withdrawals_savings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
