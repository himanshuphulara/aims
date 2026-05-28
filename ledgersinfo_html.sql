-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 25, 2026 at 10:45 AM
-- Server version: 8.0.45-0ubuntu0.24.04.1
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ledgersinfo_html`
--

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `bank_detail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_amount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bankstatements`
--

CREATE TABLE `bankstatements` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `stmt_label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stmt_amount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stmt_date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stmt_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` int NOT NULL DEFAULT '0',
  `has_total_allotment` tinyint(1) NOT NULL DEFAULT '0',
  `type` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subscription_amount` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `parent_id`, `has_total_allotment`, `type`, `subscription_amount`, `created_at`, `updated_at`) VALUES
(1, 'Regt Fund', 0, 0, NULL, NULL, '2024-09-05 00:51:47', '2025-11-22 17:15:37'),
(6, 'Welfare Fund (5%)', 1, 0, 'Liabilities', NULL, '2024-09-05 00:53:01', '2024-10-21 23:16:45'),
(11, 'Public Fund', 0, 1, NULL, NULL, '2024-09-12 03:36:16', '2025-11-22 17:15:21'),
(12, 'ATG', 11, 0, 'Liabilities', NULL, '2024-09-12 03:36:33', '2024-10-10 02:25:33'),
(13, 'ETG', 11, 0, 'Liabilities', NULL, '2024-09-12 03:36:42', '2024-10-10 02:25:37'),
(14, 'Amenity', 11, 0, 'Liabilities', NULL, '2024-09-12 03:36:48', '2024-12-03 06:10:22'),
(15, 'TTIEG', 11, 0, 'Liabilities', NULL, '2024-09-12 03:36:54', '2024-10-10 02:25:45'),
(16, 'Condiment Expdr', 11, 0, 'Liabilities', NULL, '2024-09-12 03:37:07', '2024-10-10 02:25:53'),
(17, 'ACG', 11, 0, 'Liabilities', NULL, '2024-09-12 03:37:14', '2024-12-03 05:32:25'),
(18, 'SAG', 11, 0, 'Liabilities', NULL, '2024-09-12 03:37:21', '2024-12-03 05:32:29'),
(37, 'Officers Mess', 0, 0, NULL, NULL, '2024-10-23 07:15:05', '2024-10-23 07:20:24'),
(38, 'MESS FUND', 37, 0, 'Liabilities', '100.00', '2024-10-23 07:15:28', '2024-11-19 23:48:45'),
(39, 'MMA', 37, 0, 'Liabilities', '100.00', '2024-10-23 07:15:38', '2024-11-19 23:48:54'),
(40, 'CAT STOCK', 37, 0, 'Assets', NULL, '2024-10-23 07:16:14', '2024-10-29 04:02:39'),
(41, 'WINE STOCK', 37, 0, 'Assets', NULL, '2024-10-23 07:16:35', '2024-10-29 04:08:07'),
(43, 'CIG AND SOFT', 37, 0, 'Assets', NULL, '2024-10-23 07:17:17', '2024-11-13 01:06:08'),
(44, 'MEMENTO FUND', 37, 0, 'Liabilities', NULL, '2024-10-23 07:17:31', '2024-11-13 01:06:00'),
(45, 'LIB FUND', 37, 0, 'Liabilities', '30.00', '2024-10-23 07:19:34', '2024-11-19 23:49:20'),
(46, 'ENT FUND', 37, 0, 'Liabilities', '50.00', '2024-10-23 07:20:59', '2024-11-19 23:49:29'),
(47, 'SPORTS FUND', 37, 0, 'Liabilities', '30.00', '2024-10-23 07:21:11', '2024-11-19 23:49:02'),
(49, 'SY DR', 37, 0, 'Assets', NULL, '2024-10-24 00:45:59', '2024-11-13 01:07:16'),
(50, 'REGT CUTTING', 37, 0, 'Liabilities', NULL, '2024-10-24 00:46:19', '2024-11-13 01:07:36'),
(51, 'OFFICER DEPOSIT', 37, 0, 'Liabilities', NULL, '2024-10-24 00:47:07', '2024-11-13 01:07:50'),
(52, 'FD', 37, 0, 'Assets', NULL, '2024-10-24 00:47:22', '2024-11-13 01:08:03'),
(62, 'CSD', 0, 0, NULL, NULL, '2024-11-13 23:36:25', '2024-11-13 23:37:32'),
(63, 'JCO MESS', 0, 0, NULL, NULL, '2024-11-13 23:36:43', '2024-11-13 23:59:58'),
(64, 'CSD QD', 0, 0, NULL, NULL, '2024-11-13 23:36:56', '2024-11-14 00:00:04'),
(70, 'SY CR', 37, 0, 'Liabilities', NULL, '2024-11-14 01:13:49', '2024-11-17 23:50:26'),
(72, 'Mess Fund', 63, 0, 'Liabilities', '50.00', '2024-11-26 01:07:06', '2024-11-28 23:37:42'),
(73, 'MMA', 63, 0, 'Liabilities', '50.00', '2024-11-26 01:07:13', '2024-11-28 23:37:49'),
(74, 'Cat Stock', 63, 0, 'Assets', NULL, '2024-11-26 01:07:24', '2024-11-26 01:09:00'),
(75, 'Wine Stock', 63, 0, 'Assets', NULL, '2024-11-26 01:07:34', '2024-11-26 01:09:08'),
(76, 'Memento Fund', 63, 0, 'Liabilities', NULL, '2024-11-26 01:07:45', '2024-11-26 01:07:45'),
(77, 'JCO Deposit', 63, 0, 'Liabilities', NULL, '2024-11-26 01:07:53', '2024-11-26 01:07:53'),
(78, 'Sy Dr', 63, 0, 'Assets', NULL, '2024-11-26 01:07:59', '2024-11-26 01:07:59'),
(79, 'ENT Fund', 63, 0, 'Liabilities', '40.00', '2024-11-26 01:08:11', '2024-11-28 23:37:56'),
(80, 'Regt Cutting', 63, 0, 'Assets', NULL, '2024-11-26 01:08:20', '2024-11-26 01:09:34'),
(81, 'Soft', 63, 0, 'Assets', NULL, '2024-11-26 01:08:27', '2024-11-26 01:10:03'),
(82, 'FD', 63, 0, 'Assets', NULL, '2024-11-26 01:08:34', '2024-11-26 01:08:34'),
(83, 'Sy Crs', 63, 0, 'Liabilities', NULL, '2024-11-26 01:08:42', '2024-11-26 01:08:42'),
(84, 'Grocery Stock', 62, 0, 'Assets', NULL, '2024-12-03 06:29:36', '2024-12-03 06:29:36'),
(85, 'Liquor Stock', 62, 0, 'Assets', NULL, '2024-12-03 06:29:52', '2024-12-03 06:29:52'),
(86, 'Profit', 62, 0, 'Liabilities', NULL, '2024-12-03 06:30:01', '2024-12-03 06:30:01'),
(87, 'Fund Control', 62, 0, 'Liabilities', NULL, '2024-12-03 06:30:13', '2024-12-03 06:30:13'),
(88, 'Sy Cr', 62, 0, 'Liabilities', NULL, '2024-12-03 06:30:21', '2024-12-03 06:30:21'),
(89, 'Sy Dr', 62, 0, 'Assets', NULL, '2024-12-03 06:30:28', '2024-12-03 06:30:28'),
(90, 'Tps Welfare Fund', 64, 0, 'Liabilities', NULL, '2024-12-03 07:44:26', '2024-12-03 07:44:26'),
(91, 'Sports Fund', 64, 0, 'Liabilities', NULL, '2024-12-03 07:44:40', '2024-12-04 01:41:04'),
(99, 'TTB', 11, 0, 'Liabilities', '200', '2025-02-21 18:50:48', '2025-02-21 18:50:48'),
(101, 'Regt Fund 64 Aslt Engr Regt', 0, 0, NULL, NULL, '2025-11-27 17:06:56', '2025-11-27 17:49:38'),
(104, 'SY Dr', 101, 0, 'Assets', NULL, '2025-11-27 17:07:58', '2025-11-27 17:07:58'),
(105, 'Regt Head', 101, 0, 'Liabilities', NULL, '2025-11-27 17:08:25', '2025-11-27 17:08:25'),
(106, 'Edn Head', 101, 0, 'Liabilities', NULL, '2025-11-27 17:09:04', '2025-11-27 17:09:04'),
(107, 'Gurudwara', 101, 0, 'Liabilities', NULL, '2025-11-27 17:09:17', '2025-11-27 17:09:17'),
(108, 'Mandir', 101, 0, 'Liabilities', NULL, '2025-11-27 17:09:28', '2025-11-27 17:09:28'),
(109, 'Jazz Band', 101, 0, 'Liabilities', NULL, '2025-11-27 17:09:40', '2025-11-27 17:09:40'),
(110, 'Sports', 101, 0, 'Liabilities', NULL, '2025-11-27 17:09:52', '2025-11-27 17:09:52'),
(111, 'Sy Cr', 101, 0, 'Liabilities', NULL, '2025-11-27 17:10:04', '2025-11-27 17:10:04');

-- --------------------------------------------------------

--
-- Table structure for table `cheques`
--

CREATE TABLE `cheques` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `cheque_detail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cheque_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cheque_date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cheque_amount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cheque_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_status` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `civs`
--

CREATE TABLE `civs` (
  `id` bigint UNSIGNED NOT NULL,
  `voc_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `property_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `issue_voc_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `issue_date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `issue_unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `issue_station` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `receipt_voc_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `receipt_date` date NOT NULL,
  `receipt_unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `receipt_station` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `civ` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `civ_upload` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crvs`
--

CREATE TABLE `crvs` (
  `id` bigint UNSIGNED NOT NULL,
  `voc_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `property_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `issue_voc_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `issue_expense` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `issue_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `issue_station` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receipt_voc_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receipt_date` date DEFAULT NULL,
  `receipt_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receipt_station` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_from` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `for_fy` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gem` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dt` date DEFAULT NULL,
  `contact_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gemcrac` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dated` date DEFAULT NULL,
  `crv` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `holder_sign` text COLLATE utf8mb4_unicode_ci,
  `crv_upload` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dbbackups`
--

CREATE TABLE `dbbackups` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `downloaded` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grand_total`
--

CREATE TABLE `grand_total` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `voc_type` varchar(100) NOT NULL,
  `assets` varchar(100) DEFAULT NULL,
  `liabilities` varchar(100) DEFAULT NULL,
  `book_amount` varchar(100) DEFAULT NULL,
  `rdate` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `grand_total`
--

INSERT INTO `grand_total` (`id`, `user_id`, `category_id`, `voc_type`, `assets`, `liabilities`, `book_amount`, `rdate`) VALUES
(1, 8, 101, 'Receipt', '4700409.53', '4700409.53', '94011.30', '2026-01-01'),
(2, 8, 101, 'Receipt', '4700409.53', '4700409.53', '94011.30', '2025-10-01'),
(3, 8, 62, 'Receipt', '0.00', '0.00', '0.00', '2026-01-01'),
(4, 8, 101, 'Receipt', '4646053.53', '4646053.53', '14075.30', '2025-11-01'),
(5, 8, 1, 'Receipt', '16800.00', '16800.00', '0.00', '2026-01-01'),
(6, 8, 63, 'Receipt', '0.00', '0.00', '0.00', '2026-01-01'),
(7, 8, 101, 'Receipt', '4700409.53', '4700409.53', '94011.30', '2025-12-01'),
(8, 8, 101, 'Receipt', '0.00', '0.00', '0.00', '2026-11-01'),
(9, 8, 101, 'Receipt', '0.00', '0.00', '0.00', '2026-12-01'),
(10, 8, 62, 'Receipt', '6466500.76', '6466500.76', '1798235.82', '2025-11-01'),
(11, 8, 62, 'Receipt', '6466500.76', '6466500.76', '1798235.82', '2025-12-01'),
(12, 8, 11, 'Receipt', '0.00', '0.00', '0.00', '2026-01-01'),
(13, 8, 11, 'Receipt', '0.00', '0.00', '0.00', '2025-12-01'),
(14, 8, 101, 'Receipt', '-108015.00', '-108015.00', '64075.00', '2024-12-31'),
(15, 8, 1, 'Receipt', '0.00', '0.00', '0.00', '2026-02-01'),
(16, 8, 1, 'Receipt', '0.00', '0.00', '0.00', '2026-04-01'),
(17, 8, 101, 'Receipt', '0.00', '0.00', '0.00', '2026-04-01');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `parent_item_id` int DEFAULT NULL,
  `property_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lpno` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `items` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `au` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amt` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `serviceable` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `repairable` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auction` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destroyable` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dep_per` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dep_amt` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amt_after_depr` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unsv_items_amt` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pre_value` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rboo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `astb_done` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mess_bill_summary`
--

CREATE TABLE `mess_bill_summary` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `officer_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `officer_rank` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `officer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_of_days` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `per_day_messing` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `bill_total` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arrears` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `round_off` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subcatgs_json` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mess_sub_category`
--

CREATE TABLE `mess_sub_category` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `main_category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subcategory_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subcat_date` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_08_29_100633_create_permission_tables', 2),
(7, '2024_09_02_044039_create_categories_table', 3),
(8, '2024_09_05_102001_create_vochers_table', 4),
(9, '2024_09_05_102001_create_vouchers_table', 5),
(13, '2024_09_17_095938_create_crvs_table', 8),
(14, '2024_09_18_054925_create_nivs_table', 9),
(15, '2024_09_19_052847_create_civs_table', 10),
(16, '2024_09_20_182652_create_notifications_table', 10),
(18, '2024_11_10_172423_create_sydrs_table', 11),
(20, '2024_11_11_054217_create_sycrs_table', 12),
(22, '2024_11_11_061503_create_banks_table', 13),
(23, '2024_11_11_094355_create_bankstatements_table', 14),
(24, '2024_11_10_073806_create_cheques_table', 15),
(26, '2024_11_11_122451_create_properties_table', 16),
(28, '2024_11_14_053211_create_officers_table', 17),
(29, '2024_11_14_100910_create_mess_bill_summary_table', 18),
(30, '2024_11_19_113805_create_mess_sub_category_table', 19),
(31, '2024_12_09_044106_create_items_table', 20),
(32, '2024_12_12_054230_create_dbbackups_table', 21),
(33, '2025_11_22_222317_create_units_table', 22),
(34, '2025_11_22_224154_add_has_total_allotment_to_categories_table', 23),
(35, '2025_11_22_225017_add_total_allotment_to_vouchers_bbf_table', 24),
(36, '2025_11_22_230120_create_total_allotments_table', 24),
(37, '2025_11_23_204345_create_pcda_transactions_table', 25),
(38, '2026_01_08_125159_add_voc_fd_to_vouchers_and_vouchers_bbf_tables', 26);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_permissions`
--

INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES
(2, 'App\\Models\\User', 7),
(25, 'App\\Models\\User', 7),
(26, 'App\\Models\\User', 7),
(27, 'App\\Models\\User', 7),
(28, 'App\\Models\\User', 7),
(29, 'App\\Models\\User', 7),
(30, 'App\\Models\\User', 7),
(31, 'App\\Models\\User', 7),
(32, 'App\\Models\\User', 7),
(33, 'App\\Models\\User', 7),
(34, 'App\\Models\\User', 7),
(35, 'App\\Models\\User', 7),
(36, 'App\\Models\\User', 7),
(37, 'App\\Models\\User', 7),
(38, 'App\\Models\\User', 7),
(39, 'App\\Models\\User', 7),
(40, 'App\\Models\\User', 7),
(41, 'App\\Models\\User', 7),
(43, 'App\\Models\\User', 7),
(2, 'App\\Models\\User', 20),
(3, 'App\\Models\\User', 20),
(5, 'App\\Models\\User', 20),
(6, 'App\\Models\\User', 20),
(7, 'App\\Models\\User', 20),
(8, 'App\\Models\\User', 20),
(9, 'App\\Models\\User', 20),
(10, 'App\\Models\\User', 20),
(11, 'App\\Models\\User', 20),
(12, 'App\\Models\\User', 20),
(13, 'App\\Models\\User', 20),
(14, 'App\\Models\\User', 20),
(15, 'App\\Models\\User', 20),
(17, 'App\\Models\\User', 20),
(18, 'App\\Models\\User', 20),
(19, 'App\\Models\\User', 20),
(20, 'App\\Models\\User', 20),
(21, 'App\\Models\\User', 20),
(22, 'App\\Models\\User', 20),
(23, 'App\\Models\\User', 20),
(24, 'App\\Models\\User', 20),
(25, 'App\\Models\\User', 20),
(26, 'App\\Models\\User', 20),
(27, 'App\\Models\\User', 20),
(28, 'App\\Models\\User', 20),
(29, 'App\\Models\\User', 20),
(30, 'App\\Models\\User', 20),
(31, 'App\\Models\\User', 20),
(32, 'App\\Models\\User', 20),
(33, 'App\\Models\\User', 20),
(34, 'App\\Models\\User', 20),
(35, 'App\\Models\\User', 20),
(36, 'App\\Models\\User', 20),
(37, 'App\\Models\\User', 20),
(38, 'App\\Models\\User', 20),
(39, 'App\\Models\\User', 20),
(40, 'App\\Models\\User', 20),
(41, 'App\\Models\\User', 20),
(43, 'App\\Models\\User', 20),
(2, 'App\\Models\\User', 28),
(25, 'App\\Models\\User', 28),
(26, 'App\\Models\\User', 28),
(27, 'App\\Models\\User', 28),
(28, 'App\\Models\\User', 28),
(29, 'App\\Models\\User', 28),
(2, 'App\\Models\\User', 29),
(25, 'App\\Models\\User', 29),
(26, 'App\\Models\\User', 29),
(27, 'App\\Models\\User', 29),
(28, 'App\\Models\\User', 29),
(29, 'App\\Models\\User', 29),
(2, 'App\\Models\\User', 30),
(3, 'App\\Models\\User', 30),
(5, 'App\\Models\\User', 30),
(6, 'App\\Models\\User', 30),
(7, 'App\\Models\\User', 30),
(8, 'App\\Models\\User', 30),
(9, 'App\\Models\\User', 30),
(10, 'App\\Models\\User', 30),
(11, 'App\\Models\\User', 30),
(12, 'App\\Models\\User', 30),
(13, 'App\\Models\\User', 30),
(14, 'App\\Models\\User', 30),
(15, 'App\\Models\\User', 30),
(17, 'App\\Models\\User', 30),
(18, 'App\\Models\\User', 30),
(19, 'App\\Models\\User', 30),
(20, 'App\\Models\\User', 30),
(21, 'App\\Models\\User', 30),
(22, 'App\\Models\\User', 30),
(23, 'App\\Models\\User', 30),
(24, 'App\\Models\\User', 30),
(25, 'App\\Models\\User', 30),
(26, 'App\\Models\\User', 30),
(27, 'App\\Models\\User', 30),
(28, 'App\\Models\\User', 30),
(29, 'App\\Models\\User', 30),
(30, 'App\\Models\\User', 30),
(31, 'App\\Models\\User', 30),
(32, 'App\\Models\\User', 30),
(33, 'App\\Models\\User', 30),
(34, 'App\\Models\\User', 30),
(35, 'App\\Models\\User', 30),
(36, 'App\\Models\\User', 30),
(37, 'App\\Models\\User', 30),
(38, 'App\\Models\\User', 30),
(39, 'App\\Models\\User', 30),
(40, 'App\\Models\\User', 30),
(41, 'App\\Models\\User', 30),
(43, 'App\\Models\\User', 30),
(49, 'App\\Models\\User', 30),
(50, 'App\\Models\\User', 30),
(51, 'App\\Models\\User', 30),
(52, 'App\\Models\\User', 30),
(53, 'App\\Models\\User', 30);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(4, 'App\\Models\\User', 7),
(3, 'App\\Models\\User', 20),
(12, 'App\\Models\\User', 22),
(11, 'App\\Models\\User', 23),
(3, 'App\\Models\\User', 28),
(3, 'App\\Models\\User', 29),
(4, 'App\\Models\\User', 30);

-- --------------------------------------------------------

--
-- Table structure for table `nivs`
--

CREATE TABLE `nivs` (
  `id` bigint UNSIGNED NOT NULL,
  `voc_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `property_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `issue_voc_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `issue_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `issue_station` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receipt_voc_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receipt_date` date DEFAULT NULL,
  `receipt_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receipt_station` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `issued_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `received_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sig` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rank` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `niv` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `niv_upload` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('00133c30-e436-4834-8be6-28b50a7e7a08', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Public Fund\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2025-12-21 00:00:06', '2025-12-21 00:00:06'),
('003f69a7-c03d-4ee0-a0d1-aeff06e4cfc3', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-13 12:48:55', '2026-01-13 12:48:55'),
('006fab3b-cb4e-4e4c-9238-17ee592547f7', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 14:52:33', '2026-01-12 14:52:33'),
('008570fb-3058-4078-84c7-c71cd9733ce8', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:31:01', '2026-01-08 06:31:01'),
('0199ba80-48ac-4de4-a5a8-66bd0dee3921', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 08:02:54', '2026-01-08 08:02:54'),
('0211dc54-8eeb-41b4-9987-33c41a796955', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:38:47', '2026-01-08 10:38:47'),
('0311f489-1290-4d04-a101-28aa06421da1', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 05:20:23', '2026-01-08 05:20:23'),
('0340cbaf-1fbc-4546-bae8-7bee8b3a4f4e', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:47:57', '2026-01-08 07:47:57'),
('0344d903-777d-4ac0-89c6-c1ebbb3e4cd9', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:59:55', '2026-01-08 10:59:55'),
('03e8c0a8-2fad-47d7-af58-ca28b1a5710e', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"CSD\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-14 08:36:28', '2026-01-14 08:36:28'),
('041f779a-da50-4c89-97e4-3ba5fdee8722', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:59:55', '2026-01-08 10:59:55'),
('04b751be-f31c-406b-83b4-501d45783ab8', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 05:16:17', '2026-01-08 05:16:17'),
('0560054e-b7b8-4a47-be72-580d5809b47c', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:56:27', '2026-01-08 06:56:27'),
('05f0e9f3-f756-42d6-bc2b-83106ca87b32', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Public Fund\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2025-12-24 13:10:38', '2025-12-24 13:10:38'),
('0602964e-29f5-4b88-ae0f-8f7f8f8e3674', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 15:46:56', '2026-01-12 15:46:56'),
('0641e2ea-32cf-435e-b620-fd9b6dd608ef', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-13 12:51:09', '2026-01-13 12:51:09'),
('06643688-ba38-486f-845b-a7764fabb872', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"CSD\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-14 08:32:21', '2026-01-14 08:32:21'),
('085a7cd7-b8cf-4df1-a854-a7319bc8169e', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:36:17', '2026-01-08 10:36:17'),
('0869b364-90ca-4d42-8b4b-b784489e2ae0', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-13 12:51:52', '2026-01-13 12:51:52'),
('0a241be3-a43e-48c7-8f93-58ea515b2483', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 15:33:40', '2026-01-12 15:33:40'),
('0ae98c49-f9ea-4f86-a5a4-7ca2af928588', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:39:31', '2026-01-08 07:39:31'),
('0c3072c0-9bd1-4e26-9dc7-c70f4ee82b5f', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 15:30:14', '2026-01-12 15:30:14'),
('0c63d5d2-9f26-4982-84b0-fc0648f4dc66', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:48:25', '2026-01-08 07:48:25'),
('0d8ede5b-862a-4452-b36c-8117dc1b8e88', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 16:02:41', '2026-01-12 16:02:41'),
('0dc91e5b-d466-455c-802f-e795dfd5da2c', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:01:39', '2026-01-08 07:01:39'),
('0eb3f305-3063-40e5-ab2a-409a199851ce', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:03:12', '2026-01-08 07:03:12'),
('0f438111-1380-4c52-bcf8-b06ba0522972', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:56:49', '2026-01-08 06:56:49'),
('0f71f4d0-fcc1-426a-8989-ad00d10ec95a', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:50:33', '2026-01-08 06:50:33'),
('0f8277ed-da3d-4d32-b45c-bac6f179f333', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"CSD\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-14 08:32:21', '2026-01-14 08:32:21'),
('0fa39c6e-4c8d-410f-a2ba-a28ed263ec91', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-13 12:52:30', '2026-01-13 12:52:30'),
('104ee223-5562-4ee5-82d4-982ae2388f6e', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-13 12:51:52', '2026-01-13 12:51:52'),
('10b5ade3-8e62-42ee-a5f6-2354153bd992', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 05:19:55', '2026-01-08 05:19:55'),
('10c0ae88-1bbf-4473-9241-8ff1a155213a', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 08:02:31', '2026-01-08 08:02:31'),
('114066e0-3aa7-47ef-8bbc-df2a3b1c8510', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 14:54:06', '2026-01-12 14:54:06'),
('116a4404-1881-45fb-9b73-f21e5e75e897', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:28:58', '2026-01-08 06:28:58'),
('13784dbc-8370-42be-8955-a8e4d47c9b44', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:48:25', '2026-01-08 07:48:25'),
('157c19de-d41e-4ecb-ab81-59f84a3b658b', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:39:00', '2026-01-08 07:39:00'),
('1671cd9f-ab03-403b-8e5c-9ac9242eed6c', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 16:04:04', '2026-01-12 16:04:04'),
('16bce5ac-249d-4825-aa49-f09fe5d4dac4', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:04:36', '2026-01-08 07:04:36'),
('16c31691-2569-4559-94b0-c9979ffb67ee', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 15:43:30', '2026-01-12 15:43:30'),
('16c8e1ec-b4de-4677-9bcd-902f911dc219', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:55:48', '2026-01-08 07:55:48'),
('176a3ca9-a18a-4590-a8bc-cd482433df97', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:36:17', '2026-01-08 10:36:17'),
('183149cb-42fe-4dd4-87b5-5e9d8da2fada', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:31:20', '2026-01-08 06:31:20'),
('188133de-38b5-4fe8-9d8e-c3f8f30a2c72', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:40:25', '2026-01-08 07:40:25'),
('1a353cf3-6390-45a0-bdbc-2f60b62f7c87', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:43:10', '2026-01-08 10:43:10'),
('1a7d1834-abed-416e-94f9-6920a5c8ada0', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 08:02:54', '2026-01-08 08:02:54'),
('1ac8ca8b-a08a-426b-a491-fecfdcb873a1', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:39:00', '2026-01-08 07:39:00'),
('1cb604ae-9787-482f-9ee2-edc1ee8f72ed', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:43:10', '2026-01-08 10:43:10'),
('1ce31388-697a-42a9-9597-76d317755bfe', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 08:02:31', '2026-01-08 08:02:31'),
('1d43d60d-376a-4bc0-9d1a-a2d509e4d6c4', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 15:46:56', '2026-01-12 15:46:56'),
('1dd4ff34-0659-45ca-9930-485eb8a84de1', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:29:22', '2026-01-08 06:29:22'),
('1e1b1618-7a1a-495b-b4d5-dcc12fc43888', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2025-12-20 23:33:18', '2025-12-20 23:33:18'),
('1e2a5fe8-1eb1-4d3d-80b8-8bc2fc153fc8', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 05:16:51', '2026-01-08 05:16:51'),
('20853785-c61c-41dc-a72f-490458a285c5', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 15:23:38', '2026-01-12 15:23:38'),
('2157a6fc-c693-4c06-b0c5-a3a1741f0854', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:48:25', '2026-01-08 07:48:25'),
('219f139b-60d6-409b-be37-d0dd5553db40', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:55:06', '2026-01-08 06:55:06'),
('21f335ae-bee4-42d7-86b7-f81fb70ab97b', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-09 15:24:24', '2026-01-09 15:24:24'),
('25b17819-3592-4f8c-a14e-5c90b133d1fa', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:01:13', '2026-01-08 07:01:13'),
('27469698-c64b-40c0-b2ae-34c446655527', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:50:33', '2026-01-08 06:50:33'),
('28832df4-73ae-4ad0-9ee2-5fa57989396e', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:28:58', '2026-01-08 06:28:58'),
('28d6a8da-542b-4fd0-8b8c-566ae17fbbcf', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:03:29', '2026-01-08 07:03:29'),
('291de579-3eea-4546-8c03-67b711033ae7', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:59:33', '2026-01-08 10:59:33'),
('298822cb-3d3f-4d32-9792-c44c899f53e0', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:32:53', '2026-01-08 06:32:53'),
('29984d36-a6f8-43ea-8fe6-126d93e38331', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:48:25', '2026-01-08 07:48:25'),
('2a12ece0-511d-48d0-985a-5a93f6c84e4c', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 16:04:04', '2026-01-12 16:04:04'),
('2b24439c-6cc5-47f4-b829-b2bae46d2956', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 05:16:18', '2026-01-08 05:16:18'),
('2e56e80b-6e61-4008-89ab-6e560a5a475b', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Public Fund\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2025-12-21 00:01:30', '2025-12-21 00:01:30'),
('2ed5dd7f-7fc4-42e7-bfd1-afaea6fcf60d', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 16:17:39', '2026-01-12 16:17:39'),
('2f03f6b2-d594-40e0-9846-080e51c89831', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-09 15:21:11', '2026-01-09 15:21:11'),
('303e1f23-b37e-4f69-a29d-d11b42aff25d', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:03:12', '2026-01-08 07:03:12'),
('3117063b-33ab-4620-8c77-17798a4791b5', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 11:00:45', '2026-01-08 11:00:45'),
('320fd773-750a-4327-9df3-12a09c2bbdf6', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 14:52:33', '2026-01-12 14:52:33'),
('333b2390-8f08-41cc-b2a2-3177032f3321', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 08:03:12', '2026-01-08 08:03:12'),
('350e0c1a-cdca-415b-b61a-fa1366ab95dc', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:01:13', '2026-01-08 07:01:13'),
('3648619c-1ad1-43a6-9f7b-810ab8610bf3', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:01:13', '2026-01-08 07:01:13'),
('3693323c-e367-47fd-99b0-c406fc2feb44', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 08:02:31', '2026-01-08 08:02:31'),
('37126f37-f5c2-4b5f-99ce-eadaa544d189', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 15:30:14', '2026-01-12 15:30:14'),
('377fccba-a1cd-4d46-8be9-7749eb2ca4af', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:47:57', '2026-01-08 07:47:57'),
('37d1b450-9c99-4211-95df-0865c1283bf9', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:04:57', '2026-01-08 07:04:57'),
('384b69a3-f7bf-445c-95a7-a109313c2324', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:31:43', '2026-01-08 06:31:43'),
('387a637f-3d65-4603-9502-298a08252c85', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:56:12', '2026-01-08 07:56:12'),
('3a722c0d-889a-4a9b-a8d8-9adbddfcd893', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-13 12:48:55', '2026-01-13 12:48:55'),
('3a797905-d8b2-4937-bd64-bc6d7dce4d64', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:56:27', '2026-01-08 06:56:27'),
('3bd6a9cc-43fc-470f-8e46-3cddf220a049', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 11:00:45', '2026-01-08 11:00:45'),
('3c288cfd-2378-402f-9e55-a93209f6d1cb', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 15:23:38', '2026-01-12 15:23:38'),
('3cecd4e4-d1b5-41e7-900d-5df81a4ed4ff', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-13 12:51:09', '2026-01-13 12:51:09'),
('3e44bf7d-4741-46fd-86ae-50e33dd971b2', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2025-12-20 19:28:40', '2025-12-20 19:28:40'),
('3f69b731-f583-4ad5-a389-d166ea1e651f', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-09 15:21:11', '2026-01-09 15:21:11'),
('407d83a2-6f19-4fa9-92c3-4a75f7aa31d5', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:04:36', '2026-01-08 07:04:36'),
('40dd6f79-13ae-480c-898d-3e7affda8528', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:04:12', '2026-01-08 07:04:12'),
('411a289e-8ac8-499a-b6e0-ac59f0a5a618', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 05:19:55', '2026-01-08 05:19:55'),
('413f3f23-6689-42cc-a476-a5eb37cc5d0f', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 05:16:51', '2026-01-08 05:16:51'),
('41664711-d5c8-4453-88bb-67748b5f68e9', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:51:36', '2026-01-08 06:51:36'),
('417d3d7b-57af-4af7-9f02-6d6e340ad5cf', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:51:17', '2026-01-08 06:51:17'),
('41f28d51-8ebb-4ab1-a96f-e78be1792cbf', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 15:49:31', '2026-01-12 15:49:31'),
('44d9cd46-8329-4394-99a0-f565206151b3', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2025-12-20 19:35:28', '2025-12-20 19:35:28'),
('4665a1e2-a175-4642-acef-1b1700600e74', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:40:47', '2026-01-08 07:40:47'),
('46c51876-e807-4944-bb2e-0b939b2d2505', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:58:26', '2026-01-08 07:58:26'),
('46fd4a1d-ae24-43bb-892e-6719b5393c98', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:54:37', '2026-01-08 06:54:37'),
('47120279-51af-4205-90e6-3fde491cdaed', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:32:53', '2026-01-08 06:32:53'),
('48f0c7a0-0ed9-4af4-baf6-7144d55ff59f', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:04:57', '2026-01-08 07:04:57'),
('49cf4cdd-949b-4d63-8d67-bae6fff807f2', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 05:19:55', '2026-01-08 05:19:55'),
('49e466bd-dbdc-4ab6-af36-19843063d847', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2025-12-24 12:45:17', '2025-12-24 12:45:17'),
('4cb1f79a-0e26-4bbb-8a69-2b0016c37370', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:32:33', '2026-01-08 06:32:33'),
('4d6e4048-db28-48dc-b0a4-52bee4846f81', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:58:52', '2026-01-08 07:58:52'),
('4d764f65-8365-4df8-b44d-9195075477d9', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:02:04', '2026-01-08 07:02:04'),
('4df311c9-2dd9-45ca-bf73-f7e4d8c3514c', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:29:22', '2026-01-08 06:29:22'),
('4e6e6553-f15e-4ab5-aae3-3363a84fa8b4', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:00:55', '2026-01-08 07:00:55'),
('4f36a1ba-a826-451c-bd31-a6984e8ca78c', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 14:52:33', '2026-01-12 14:52:33'),
('4f518fc8-b54c-4f38-a14b-b9a1e2c08a19', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:55:48', '2026-01-08 07:55:48'),
('4fd598e6-52d3-43b8-82e1-a4f65b0573a4', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 15:56:57', '2026-01-12 15:56:57'),
('505f4d57-5c91-413e-953a-552c0cbccf1b', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:35:55', '2026-01-08 10:35:55'),
('52afeb43-e7a0-476a-933c-0f2e78b3b38b', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 14:54:06', '2026-01-12 14:54:06'),
('52f24871-5e3b-48cf-b097-4790184dba33', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-13 12:45:32', '2026-01-13 12:45:32'),
('532a6080-9029-4253-8a9e-f8506c02a7c2', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:58:52', '2026-01-08 07:58:52'),
('53947883-71fb-4e78-823f-6ff0ad62ae51', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 11:00:45', '2026-01-08 11:00:45'),
('53a150ae-77ad-46f2-80f3-c90f4616c258', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 08:02:31', '2026-01-08 08:02:31'),
('5526aeb5-b15d-43fc-916a-56e8be4de949', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:32:33', '2026-01-08 06:32:33'),
('55a5a12a-42ae-4278-a2fc-a4e956c28d7d', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Public Fund\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2025-12-24 13:10:38', '2025-12-24 13:10:38'),
('566191d4-a758-4aa4-8a13-deb6227aac12', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 11:00:45', '2026-01-08 11:00:45'),
('56b6f957-cc1f-4c46-9406-4fd55b22de2a', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 15:33:40', '2026-01-12 15:33:40'),
('56c827bc-8d8f-40d2-8794-ca9bc61d035a', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:43:33', '2026-01-08 10:43:33'),
('57015f85-abd5-40dd-a261-598bd04d8ccc', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-13 12:51:52', '2026-01-13 12:51:52'),
('57114efd-17e7-4e9e-a889-162d885623dc', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-13 12:53:25', '2026-01-13 12:53:25'),
('57807fe6-8d08-4ae7-90a0-38617c840a94', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:59:32', '2026-01-08 07:59:32'),
('57a29d08-d908-4cfe-9574-f0e528599500', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 15:30:10', '2026-01-12 15:30:10'),
('583de330-2c57-49f9-b58a-196ba8ec29ab', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 16:02:41', '2026-01-12 16:02:41'),
('58e19778-2432-40a6-955d-1ae81f88ed95', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:35:55', '2026-01-08 10:35:55'),
('59d0e737-7bb5-40fd-ba82-bd926a66562c', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 11:01:06', '2026-01-08 11:01:06'),
('5a3bdbe8-256d-43de-8ab0-437835390755', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:28:31', '2026-01-08 06:28:31'),
('5bc7eb86-c287-4e4d-917f-81941ac9fd1c', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:35:55', '2026-01-08 10:35:55'),
('5d71bd01-e876-4130-8f6d-eb8ed465b9b4', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:43:33', '2026-01-08 10:43:33'),
('5e260285-1534-451c-a2ad-90c5bd1f8307', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 05:16:18', '2026-01-08 05:16:18'),
('5ebeb5a2-e497-43ff-8c03-5b76aec7f503', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:58:26', '2026-01-08 07:58:26'),
('6008af5b-c5ef-4b3a-b2da-37fc71740105', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 05:20:23', '2026-01-08 05:20:23'),
('61cc268b-9b04-4743-a92a-f49ce19d0dbc', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:04:57', '2026-01-08 07:04:57'),
('626ee4ee-d3de-493e-96aa-27c858871992', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-13 12:48:55', '2026-01-13 12:48:55'),
('62713a37-5830-4571-af5a-fdb196ec1209', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:51:17', '2026-01-08 06:51:17'),
('633ea586-2c69-4e1c-9c29-f8bb78ad8b1f', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:44:20', '2026-01-08 10:44:20'),
('6414ad13-6bef-4b59-b018-28e3e184879d', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:56:49', '2026-01-08 06:56:49'),
('64b18e5c-f5fa-4d8c-b923-2b211c5f8836', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:51:17', '2026-01-08 06:51:17'),
('6533eeb2-d2d5-4501-a9fe-f57f60b820ad', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2025-12-24 15:12:16', '2025-12-24 15:12:16'),
('65849720-c048-4854-9e50-d1b357babb7c', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:51:36', '2026-01-08 06:51:36'),
('67b9a0e5-e129-4337-9a25-b8da875bb74f', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:54:37', '2026-01-08 06:54:37'),
('67cc73e9-94c2-478b-862a-4d90af8b61b8', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:58:26', '2026-01-08 07:58:26'),
('6802e470-eede-41b8-a271-623a0e63241c', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-13 12:51:09', '2026-01-13 12:51:09'),
('6969fb2a-3dce-4b26-ae03-ecd0f67f0a2b', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-09 15:21:11', '2026-01-09 15:21:11'),
('69b25875-4266-4ce6-95da-a5dc8916019e', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:01:39', '2026-01-08 07:01:39'),
('6a36e927-bcae-4fc5-8929-8b5b19631dd0', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 15:49:31', '2026-01-12 15:49:31'),
('6a8a9308-53cf-4ad7-bcf2-d7296632fc82', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2025-12-20 19:28:40', '2025-12-20 19:28:40'),
('6cf36b9b-787b-4424-a70b-02d15006cff1', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 15:46:56', '2026-01-12 15:46:56'),
('6d3ff4aa-488c-4402-8fef-de19ba7a91a8', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 16:10:53', '2026-01-12 16:10:53'),
('6f14802c-7f94-45bf-9933-e88a3102b120', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 08:03:34', '2026-01-08 08:03:34'),
('71182513-7945-4853-9090-62b9d0626871', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:29:46', '2026-01-08 06:29:46'),
('73cb8140-333f-4475-957e-5a219c0f1779', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:38:47', '2026-01-08 10:38:47'),
('74845000-6dac-435e-97a2-5f16db0ec810', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 15:49:31', '2026-01-12 15:49:31'),
('75495c92-615b-4f0c-89d6-bd452b7b3f6d', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-13 12:47:53', '2026-01-13 12:47:53'),
('7722d7c3-ebca-4dfe-9b65-f4a7a03c11c0', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:00:55', '2026-01-08 07:00:55'),
('77779220-2acf-449c-8d45-e7759e2fedc2', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 08:01:46', '2026-01-08 08:01:46'),
('77a828cc-421a-444c-a395-a2b9b4289a29', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:39:11', '2026-01-08 10:39:11'),
('77b18e1c-f5d2-4553-8b5a-76ebb4a51532', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-13 12:53:25', '2026-01-13 12:53:25'),
('77c60c03-271e-4fbc-9c44-2f514788e80a', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:59:13', '2026-01-08 07:59:13'),
('78156c90-822a-4c14-bc74-dd8591373e63', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 16:17:39', '2026-01-12 16:17:39'),
('78ae7513-cd6e-47e2-996c-3dc43790211e', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:40:47', '2026-01-08 07:40:47'),
('78b480bb-54ae-4353-b864-0b282c9079ca', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2025-12-20 23:33:18', '2025-12-20 23:33:18'),
('78d48251-0210-450d-a765-d2b5ce402839', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:29:46', '2026-01-08 06:29:46'),
('796b3887-7705-4a7d-9a75-a03bfde25788', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-13 12:47:53', '2026-01-13 12:47:53'),
('7a2f747a-445b-4745-aed0-26722947e07c', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:36:17', '2026-01-08 10:36:17'),
('7ab30719-0c92-4789-9d02-ce6afd53c9f8', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 08:03:12', '2026-01-08 08:03:12'),
('7babed3e-2e4f-4433-a350-738066603121', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2025-12-20 19:35:28', '2025-12-20 19:35:28'),
('7f216989-9fcf-4719-9f22-0222423bf98d', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:47:57', '2026-01-08 07:47:57'),
('7f606e07-5b15-4317-9081-20667ce22011', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:43:57', '2026-01-08 10:43:57'),
('7fd2e3d6-fbdf-425b-a83f-f724eca08204', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:31:01', '2026-01-08 06:31:01'),
('80a2a250-be24-4484-aeae-0e247dc669f8', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:39:31', '2026-01-08 07:39:31'),
('80a4572c-21b4-4a83-8ade-52db87dffc0f', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 16:04:04', '2026-01-12 16:04:04'),
('82002e4c-d8f9-4c8e-99e1-b2103eb453b3', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Public Fund\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2025-12-21 00:00:06', '2025-12-21 00:00:06');
INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('82862cf3-e4aa-45b3-93f2-c7cb286b42e3', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"CSD\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-14 08:32:21', '2026-01-14 08:32:21'),
('82d9c673-06f5-48f8-a609-46c221c0161f', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:31:20', '2026-01-08 06:31:20'),
('82eb2ce6-1d2d-4053-9f39-bdab39eaf2ea', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:50:33', '2026-01-08 06:50:33'),
('83868387-65c9-417d-b585-8a364eda745e', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:39:00', '2026-01-08 07:39:00'),
('83ee307c-c539-49c1-bb4a-db037625d6ee', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 14:54:06', '2026-01-12 14:54:06'),
('8793fc38-b925-4a54-afde-f304b6de4ed1', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:40:47', '2026-01-08 07:40:47'),
('879fd779-851f-48e7-a981-7696e6d9ee7d', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:59:13', '2026-01-08 07:59:13'),
('8831de9c-0e29-47c0-8e72-975d171259dc', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2025-12-24 15:12:16', '2025-12-24 15:12:16'),
('88751c88-a5ff-4d1c-9361-97705a22941f', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 15:46:56', '2026-01-12 15:46:56'),
('88d6db2b-278c-4f70-8fac-78a99910ff36', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:56:12', '2026-01-08 07:56:12'),
('8bf7fe02-6377-468a-bbf8-539a37311193', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:39:11', '2026-01-08 10:39:11'),
('8cb4051b-650b-48e2-8786-edff037595cb', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:58:26', '2026-01-08 07:58:26'),
('8cca28f3-5833-46fb-a857-f9288a14a4b6', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 08:02:02', '2026-01-08 08:02:02'),
('8d48bc2d-5975-4e06-b9b5-603888bd146e', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:38:47', '2026-01-08 10:38:47'),
('8f54a60d-c758-4059-8d6a-fc1827ac971b', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 16:17:39', '2026-01-12 16:17:39'),
('8fd5be3d-91f3-4504-bdfc-23807703e1a4', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 14:52:33', '2026-01-12 14:52:33'),
('90a8a057-e947-486c-afda-32065be433a6', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 15:56:57', '2026-01-12 15:56:57'),
('90e4513f-0720-4e2d-863d-2281c0e0a2e1', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:32:33', '2026-01-08 06:32:33'),
('9140f51e-94fd-4836-b9e7-22992d9ad7c0', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:54:37', '2026-01-08 06:54:37'),
('91c9fc9d-5f77-4253-b3c1-9b884039c939', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2025-12-20 23:34:49', '2025-12-20 23:34:49'),
('92494e05-3cad-4ccf-b748-bbb061b057a9', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 08:02:02', '2026-01-08 08:02:02'),
('92fb5d06-cc63-432c-b5a5-797dcb7ddf57', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:04:12', '2026-01-08 07:04:12'),
('93943af0-5572-4569-98e3-647d09e19696', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:32:53', '2026-01-08 06:32:53'),
('97336d97-010e-446c-a407-dcee5f63fcdf', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:39:00', '2026-01-08 07:39:00'),
('98e8cd43-9a59-470a-b09b-1750513223a8', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-13 12:48:55', '2026-01-13 12:48:55'),
('9a037096-052a-4448-9134-0ae03f913952', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:03:51', '2026-01-08 07:03:51'),
('9a9c912e-eaa4-4f0f-9122-55424c2d0367', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Public Fund\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2025-12-21 00:01:30', '2025-12-21 00:01:30'),
('9b4a4770-6934-4c78-ba6d-c5c6a7cac5cf', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:31:43', '2026-01-08 06:31:43'),
('9eae9dae-5523-40ea-9adb-a7425367b954', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 11:01:06', '2026-01-08 11:01:06'),
('9fa94774-32be-4d5d-a4cb-99890e37353e', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:56:49', '2026-01-08 06:56:49'),
('9fceb98f-1a42-49cd-a970-7bbb714eaa85', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:50:09', '2026-01-08 06:50:09'),
('9ff5bec3-8d3f-4156-b485-1223628b0645', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:55:06', '2026-01-08 06:55:06'),
('9ffa3b30-eae3-47cd-bf42-8cbb50aa9ff6', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:03:12', '2026-01-08 07:03:12'),
('a05baa70-a7ed-4c1d-bbaf-5f7ee74d4420', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:28:58', '2026-01-08 06:28:58'),
('a275de10-900b-40f5-8eff-a44a81adae7c', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:59:33', '2026-01-08 10:59:33'),
('a34516f6-73b4-4341-8cc5-1ee2b3099356', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:31:01', '2026-01-08 06:31:01'),
('a3c2411b-04c2-497d-bc22-82f7f11ac72b', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:29:22', '2026-01-08 06:29:22'),
('a55fc581-2867-48ac-87b2-09ed7309fa44', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:03:29', '2026-01-08 07:03:29'),
('a628ecf4-23cf-4366-bda0-ac2abb9d4b10', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:43:10', '2026-01-08 10:43:10'),
('a72a61b0-2029-42d3-b0d6-c1f5a5d46204', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"CSD\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-14 08:32:21', '2026-01-14 08:32:21'),
('a77f827b-c3a2-4a29-a6df-e245b898a69b', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:44:20', '2026-01-08 10:44:20'),
('a7a30a24-0b72-41a4-8ca4-23666a15abcb', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2025-12-20 19:28:40', '2025-12-20 19:28:40'),
('a8304af0-f6df-4048-8515-68208538114c', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:02:04', '2026-01-08 07:02:04'),
('a99654b5-7221-4d4c-a01b-c5e40c4e6344', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:59:32', '2026-01-08 07:59:32'),
('a9d4e316-3d12-4e64-8d16-10ac2af96895', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:28:31', '2026-01-08 06:28:31'),
('a9fe91a6-88c7-4ca0-a6de-90a70c24f025', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-13 12:45:32', '2026-01-13 12:45:32'),
('ac361890-13e5-43f9-ac16-5c43b084e34d', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:31:43', '2026-01-08 06:31:43'),
('acb8f1a6-5d10-4a73-88c6-586ce3a2c248', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:03:29', '2026-01-08 07:03:29'),
('acce112b-50b4-4494-b373-ddb67b0ead39', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:47:57', '2026-01-08 07:47:57'),
('ae00b365-380a-47e4-ab1e-4e8ff5091ea4', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:59:33', '2026-01-08 10:59:33'),
('ae02fddb-e944-4cc6-be17-07ea50ea0ed8', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:32:53', '2026-01-08 06:32:53'),
('ae36338c-cd44-41a8-a0b4-569e075f078b', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 15:33:40', '2026-01-12 15:33:40'),
('afb8860f-399d-4836-a618-acc300eabccd', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:44:20', '2026-01-08 10:44:20'),
('b01b6b73-40a2-4e62-b2b5-66a66931acf0', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:59:13', '2026-01-08 07:59:13'),
('b0d9d8d2-98da-4d67-bc00-7886c1f1a526', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:43:57', '2026-01-08 10:43:57'),
('b1069933-5653-4d2b-b0a6-86d72b7a5cd4', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 05:20:23', '2026-01-08 05:20:23'),
('b1984003-2891-4f0f-b449-5bfc39d06e1c', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:51:17', '2026-01-08 06:51:17'),
('b20eb68f-269e-4272-882d-62f39092c69b', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:58:52', '2026-01-08 07:58:52'),
('b2468cd3-467c-437c-9655-03cb0be7742b', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 05:16:51', '2026-01-08 05:16:51'),
('b3d71f6f-5ab5-4988-bfa6-132ccaf5a867', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-13 12:47:53', '2026-01-13 12:47:53'),
('b4368c86-de13-4b0f-acb6-0fab0fbbc9f1', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:31:20', '2026-01-08 06:31:20'),
('b6330eaa-c087-466f-a6ef-12db04a26279', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:59:33', '2026-01-08 10:59:33'),
('b76ad297-e1d1-4947-9f00-f82915e1e25e', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 15:56:57', '2026-01-12 15:56:57'),
('ba0d5363-9576-466b-8f2f-940232c2a7be', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 08:03:34', '2026-01-08 08:03:34'),
('ba6cb3b7-85a3-4e33-8296-159fb3aacb4a', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2025-12-24 12:45:17', '2025-12-24 12:45:17'),
('ba8d3420-b6c1-47ae-9ffe-a45ed567cebf', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:56:12', '2026-01-08 07:56:12'),
('baa9db65-551d-436f-a833-32d49c04475c', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:50:09', '2026-01-08 06:50:09'),
('bac2179a-316e-485d-8985-b0b7e8b93a0b', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 16:17:39', '2026-01-12 16:17:39'),
('bbbf1849-3bf1-48a8-8e24-e5569f69e8b9', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:32:07', '2026-01-08 06:32:07'),
('bc5bdb38-6444-4331-b785-c8af2c156b9d', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 08:02:02', '2026-01-08 08:02:02'),
('bd4d42bc-4001-4620-9073-ef758233c4ba', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:55:48', '2026-01-08 07:55:48'),
('bda6430f-4275-40b9-9743-7c34c332560d', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:01:39', '2026-01-08 07:01:39'),
('bdc655e4-62a4-47f5-93de-322f348e0930', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 08:01:46', '2026-01-08 08:01:46'),
('be4b1b92-5c31-422c-bf4b-b3e23fca28d2', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:50:33', '2026-01-08 06:50:33'),
('bf22ebec-a1d4-4a4d-9acd-f2c3b9838bfb', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 15:56:57', '2026-01-12 15:56:57'),
('c06db533-a554-4396-9f6f-c398e0928056', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 15:30:14', '2026-01-12 15:30:14'),
('c1b0f748-ca42-4234-92f8-4be482d02987', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:02:04', '2026-01-08 07:02:04'),
('c33d3457-46d1-407e-b384-5e62f17f7bc8', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:38:47', '2026-01-08 10:38:47'),
('c3c7d1cc-c70e-436a-9cd9-f27a3b830254', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:03:51', '2026-01-08 07:03:51'),
('c43f5298-7778-4ac5-9ac9-ca55afa00db7', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:43:57', '2026-01-08 10:43:57'),
('c44a5970-c5ce-432f-aa95-466305d54dde', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:29:46', '2026-01-08 06:29:46'),
('c5e696b7-4647-45b6-a703-e35a45d2d97d', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:56:27', '2026-01-08 06:56:27'),
('c610f83e-4b44-40f7-ad85-151fc804f44c', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2025-12-20 19:35:28', '2025-12-20 19:35:28'),
('c772c8a9-bbba-4442-8305-88e7c7e943e7', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:32:07', '2026-01-08 06:32:07'),
('c814ad4a-55ab-401c-b34a-12cbdc251605', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:59:13', '2026-01-08 07:59:13'),
('ca5c0f27-08b6-47b1-bc1d-f94dcbc7c97f', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-13 12:45:32', '2026-01-13 12:45:32'),
('caa41638-373c-4cab-bdf3-0ac6a827db06', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:40:25', '2026-01-08 07:40:25'),
('cb2789e1-c7a4-4caa-a1fe-846056f69110', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 05:19:55', '2026-01-08 05:19:55'),
('cbd511eb-a775-44a5-94a0-fb2333b0f5e2', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:40:25', '2026-01-08 07:40:25'),
('cc6b34be-4d4a-4d96-937f-8f53ba620eaa', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-09 15:24:24', '2026-01-09 15:24:24'),
('cc760478-baba-48a6-bfcf-42826db270d3', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:39:11', '2026-01-08 10:39:11'),
('cdb69ddd-6eeb-4da9-bc19-9d9c9e24d2f5', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 08:01:46', '2026-01-08 08:01:46'),
('ce119055-c328-4fe8-9044-f660bb43c139', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:59:55', '2026-01-08 10:59:55'),
('ce9d8454-f016-45b0-9ab3-4b427d040a74', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:03:12', '2026-01-08 07:03:12'),
('cf21e8d1-e866-47dc-82f5-6a42571f5c7d', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:55:06', '2026-01-08 06:55:06'),
('cf69d819-c30d-4f6d-99ce-8af86d856fde', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:58:52', '2026-01-08 07:58:52'),
('cf7e7921-03ec-420d-a08e-cb606ab41d7f', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-13 12:51:09', '2026-01-13 12:51:09'),
('cfaa98f8-fb16-44e1-8f7c-9bc3e8fc62e1', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:50:09', '2026-01-08 06:50:09'),
('d03d1694-03e4-4f3a-9728-6e60be3e71bb', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:43:33', '2026-01-08 10:43:33'),
('d04b0862-65b9-47b0-b855-4e5ae40a9bdb', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 08:02:54', '2026-01-08 08:02:54'),
('d05769d0-508c-4858-ac6b-d940bc3bb732', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 16:10:53', '2026-01-12 16:10:53'),
('d07808f6-9d36-48e0-8cfa-acbbeaa699bc', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:54:37', '2026-01-08 06:54:37'),
('d0c7babd-8009-4d3f-bfd5-9a5783570133', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 11:01:06', '2026-01-08 11:01:06'),
('d0e0eb86-2c84-4160-ad4b-eb36b997e070', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:43:33', '2026-01-08 10:43:33'),
('d103c651-4ab9-4d68-8669-d904af9bc3a2', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Public Fund\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2025-12-24 13:10:38', '2025-12-24 13:10:38'),
('d13836d5-1009-44bd-ab04-b3797e602d69', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 15:49:31', '2026-01-12 15:49:31'),
('d162b2aa-1779-4908-98f2-6f5c7b5a1c72', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 15:43:30', '2026-01-12 15:43:30'),
('d1782263-4fcf-4e51-8aa9-8ba0695bf613', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:01:39', '2026-01-08 07:01:39'),
('d1c1bcfc-a677-4e38-a73a-4619cf56b28e', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:43:10', '2026-01-08 10:43:10'),
('d1f1a468-6e01-42f9-affb-c727a9cc37b1', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 15:23:38', '2026-01-12 15:23:38'),
('d25fd748-dc9c-4cf0-8928-414154e157ef', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:02:04', '2026-01-08 07:02:04'),
('d441f13b-1e83-4749-a00d-67dfb3110a54', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2025-12-20 23:33:18', '2025-12-20 23:33:18'),
('d448e282-28d9-4dd7-b626-8fe7cc76fa0f', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:28:58', '2026-01-08 06:28:58'),
('d47c0646-2e44-455d-bd07-d9773bbe106a', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:56:12', '2026-01-08 07:56:12'),
('d59b19d9-65b2-4b26-bee0-8d8e204c1f60', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:43:57', '2026-01-08 10:43:57'),
('d63dbbb2-22a0-461b-8666-b0c86b9f5703', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 08:02:02', '2026-01-08 08:02:02'),
('d645b991-2b8c-40ed-a26b-7c254bfaab47', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 16:10:53', '2026-01-12 16:10:53'),
('d652147a-e3e3-49bd-aebe-396d6b3f74e4', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:40:47', '2026-01-08 07:40:47'),
('d6ef0bfc-64bb-4cae-a7a3-63ca9956db4d', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 15:30:14', '2026-01-12 15:30:14'),
('d6f79018-8c07-43ba-8777-790769e605d7', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 16:10:53', '2026-01-12 16:10:53'),
('d7944c7e-f822-4288-baa7-7fe184a59e20', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2025-12-20 23:34:49', '2025-12-20 23:34:49'),
('d7d63cb8-93bc-4bd9-beee-44882f4abebf', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"CSD\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-14 08:36:29', '2026-01-14 08:36:29'),
('d838fc68-bca7-4859-a9c1-c0a320f9a666', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:35:55', '2026-01-08 10:35:55'),
('d876486a-9d10-4984-a751-90b52721db4e', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Public Fund\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2025-12-21 00:00:06', '2025-12-21 00:00:06'),
('d8dcfd36-08ba-4597-bd13-a6ac3b0a414e', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-13 12:52:30', '2026-01-13 12:52:30'),
('d9baafb0-8359-4c77-a8dd-51db170fc00f', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:31:01', '2026-01-08 06:31:01'),
('da4d03f8-5975-4814-a7ad-e64ece049210', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2025-12-20 19:07:40', '2025-12-20 19:07:40'),
('da8c5399-a41d-455d-bf82-266115e439d0', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:00:55', '2026-01-08 07:00:55'),
('da9a9b66-8615-4fc2-bb56-c199a5bcb0bf', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:32:33', '2026-01-08 06:32:33'),
('db92f3b3-2d6a-40f7-b111-330d47939ebc', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 08:03:34', '2026-01-08 08:03:34'),
('ddc3ec96-d690-4488-bcef-b45ef0c80cc1', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:56:49', '2026-01-08 06:56:49'),
('de557b06-3a2e-405b-a719-e4e18d7ff9ec', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 15:30:10', '2026-01-12 15:30:10'),
('deea60cc-7c6f-4360-9bb0-cfa5852f3ee7', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 08:01:46', '2026-01-08 08:01:46'),
('defb467f-8fb3-436c-9acc-73deca3ed187', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:51:36', '2026-01-08 06:51:36'),
('df0073e6-6b9f-4a84-890f-f713891694b3', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:03:29', '2026-01-08 07:03:29'),
('df7d1236-f97a-442e-9331-3d3e4c457333', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 16:02:41', '2026-01-12 16:02:41'),
('df9cf55e-6ff4-457c-94e5-e036638f45aa', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:03:51', '2026-01-08 07:03:51'),
('e1b2de95-2f44-48e0-b2cd-f00e33b1be0a', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-09 15:21:11', '2026-01-09 15:21:11'),
('e2049e18-d4cd-4e8b-ad26-3f775dbc27cf', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-13 12:47:53', '2026-01-13 12:47:53'),
('e29d0754-ff2f-47fc-a48e-8d794183fc1b', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:39:31', '2026-01-08 07:39:31'),
('e35b8fcc-2f4d-4f2d-8b44-67c80c750f16', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:50:09', '2026-01-08 06:50:09'),
('e3b626c3-697c-4edd-b96f-5204f9ecad0b', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:32:07', '2026-01-08 06:32:07'),
('e3b72763-5788-46af-a180-af1e67ee1a67', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2025-12-24 12:45:17', '2025-12-24 12:45:17'),
('e4d36c88-0d66-47bd-8962-7361ddb468f3', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:28:31', '2026-01-08 06:28:31'),
('e5183035-03ff-4532-954a-35abc1f92c08', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:03:51', '2026-01-08 07:03:51'),
('e565445f-6a6f-44f5-b3c1-25fdd64b2b8c', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2025-12-20 19:07:40', '2025-12-20 19:07:40'),
('e658a929-1264-4d67-833c-81179a608391', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:29:22', '2026-01-08 06:29:22'),
('e80fe27f-aa6f-4a32-bada-60d6a5195850', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 08:03:34', '2026-01-08 08:03:34'),
('e933bb76-e91b-4cc2-bca2-34f46bf06a42', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-13 12:53:25', '2026-01-13 12:53:25'),
('e9a057c1-1fc3-4fac-afec-b1fd8e1d52d6', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:29:46', '2026-01-08 06:29:46'),
('ea31c2b4-177b-4215-bbe3-5ff57c980575', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 08:03:12', '2026-01-08 08:03:12'),
('ea8d1bef-36c9-4742-bcdf-6c0af3971aad', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:55:48', '2026-01-08 07:55:48'),
('eba2ef9e-d5e6-44ea-ac8a-eaea8d0e7bef', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 15:30:10', '2026-01-12 15:30:10'),
('ebf1c5f7-c367-49d9-aa8e-710b33f1c1a6', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-13 12:51:52', '2026-01-13 12:51:52'),
('ecf9a5b2-e185-431b-b3d5-926ba3ac681e', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:04:57', '2026-01-08 07:04:57'),
('ed88201f-6a39-4482-b548-06ef7ad09eeb', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:32:07', '2026-01-08 06:32:07'),
('eda567c2-1c2a-4681-bb79-a7190718c18b', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 15:43:30', '2026-01-12 15:43:30'),
('ef0b58e4-ca78-4b0c-a46d-8402e0fa0168', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-09 15:24:24', '2026-01-09 15:24:24'),
('ef512781-399f-4e4a-b3f7-c74d232d01f3', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2025-12-20 23:34:49', '2025-12-20 23:34:49'),
('ef81032b-e615-4e9d-a816-3bf53a8067e6', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:28:31', '2026-01-08 06:28:31'),
('f0aa8435-751c-4570-856f-b2f6d9297788', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:31:43', '2026-01-08 06:31:43'),
('f1c73e58-2968-40c3-8158-0d0752afb221', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-13 12:52:30', '2026-01-13 12:52:30'),
('f2042b80-8179-4c23-8335-0e1d5e1ca650', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:59:55', '2026-01-08 10:59:55'),
('f2703273-7b5b-4526-8411-261dfa384c04', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 15:33:40', '2026-01-12 15:33:40'),
('f28fe0c8-93a1-438a-a3d8-5765745aa21f', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:04:12', '2026-01-08 07:04:12'),
('f400c8a2-80a7-4cee-947b-757a419b78fc', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:59:32', '2026-01-08 07:59:32'),
('f43b5230-664c-4c2a-b434-bb51d0f36feb', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 15:23:38', '2026-01-12 15:23:38'),
('f4ad9e50-9309-439c-9f10-ab6d1332de7a', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:39:11', '2026-01-08 10:39:11'),
('f5ead360-1088-4fb7-b7a2-8c71c3905c8b', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:59:32', '2026-01-08 07:59:32'),
('f649cca0-4f0e-4474-9e7b-8123ec18308d', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 05:16:51', '2026-01-08 05:16:51'),
('f64e84e9-7f99-4947-b080-e8cb01aa5a29', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:04:12', '2026-01-08 07:04:12'),
('f64f684b-6528-4dd4-9f69-b0af24099f30', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 15:43:30', '2026-01-12 15:43:30'),
('f6dc119a-a5de-435c-b523-e6acad2aa00b', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:04:36', '2026-01-08 07:04:36'),
('f6dc3434-68da-4a62-bf23-ffd8ae3e0d5e', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 16:02:41', '2026-01-12 16:02:41'),
('f73ee400-ab86-4c67-9bdc-1fa411f62488', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:55:06', '2026-01-08 06:55:06'),
('f77f6f80-cf03-454b-9ecd-1b34f2891333', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2025-12-24 15:12:16', '2025-12-24 15:12:16');
INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('f7dcf194-0206-413e-8a95-163387963ccd', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"CSD\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-14 08:36:29', '2026-01-14 08:36:29'),
('f7e3f2bb-ae6a-414d-b02f-5b2ca6cdbbe3', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:00:55', '2026-01-08 07:00:55'),
('f7ea77a3-90fb-402f-8870-553896f00b74', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 11:01:06', '2026-01-08 11:01:06'),
('f83cf547-679d-421a-9b7e-947a02534b02', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 05:16:17', '2026-01-08 05:16:17'),
('f886397a-0ea4-4943-8fb1-b1b5ef080a6e', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:56:27', '2026-01-08 06:56:27'),
('f8c7797f-d98e-42fa-be11-d7e3138313a3', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:39:31', '2026-01-08 07:39:31'),
('f8d69aab-9a81-4228-a714-1272e6d3a495', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-09 15:24:24', '2026-01-09 15:24:24'),
('fa3ed0b6-7475-43d6-b88e-5c24a567da95', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 16:04:04', '2026-01-12 16:04:04'),
('fa9f516e-2fc6-469a-8553-aab8b96cbe02', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2025-12-20 19:07:40', '2025-12-20 19:07:40'),
('fac52cc4-2b20-43ac-8b54-cfeaf109e75c', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 05:20:23', '2026-01-08 05:20:23'),
('face6a21-7fb4-4c5d-9dfb-6286db26e468', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:01:13', '2026-01-08 07:01:13'),
('fafced0e-d334-4c6a-94c1-73001ad8fe21', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:04:36', '2026-01-08 07:04:36'),
('fafdd47f-aa8f-4db0-bcdc-c68c96ac55a7', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 15:30:10', '2026-01-12 15:30:10'),
('fb055f0d-1d39-4a9c-b790-4708940bd246', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-12 14:54:06', '2026-01-12 14:54:06'),
('fb258b63-461b-4b5f-aa91-8ca794c0e076', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:36:17', '2026-01-08 10:36:17'),
('fb27bde5-a0b2-41ad-b363-d60a89694adc', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 08:02:54', '2026-01-08 08:02:54'),
('fb445873-0a03-4cd6-a252-7c92b5cad2b0', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-13 12:45:32', '2026-01-13 12:45:32'),
('fcee0dd7-b1c1-4939-8290-aace89fb4f21', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:51:36', '2026-01-08 06:51:36'),
('fdd02684-73d9-4279-b6d0-5a866f4cdc5d', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 07:40:25', '2026-01-08 07:40:25'),
('fe846592-f167-4c6a-9045-d71fae0ab4eb', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"CSD\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-14 08:36:29', '2026-01-14 08:36:29'),
('fea93cb5-71ad-4e18-af94-0c955d0903f1', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 08:03:12', '2026-01-08 08:03:12'),
('feca5c75-057a-4ee5-a224-3fded1222389', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 06:31:20', '2026-01-08 06:31:20'),
('fef24745-9ac3-4fee-98ff-eb4cf99fad81', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-13 12:53:25', '2026-01-13 12:53:25'),
('ff04dafe-f7e9-4b29-ad72-c7a856ffb75f', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 28, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-13 12:52:30', '2026-01-13 12:52:30'),
('ff46292a-d4b4-4e46-9ffb-83a9915bf94f', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 8, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2025-12-24 15:12:16', '2025-12-24 15:12:16'),
('ff67cdfa-e06d-4328-aaa3-fd60477a525b', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 29, '{\"fund_type\":\"Public Fund\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2025-12-21 00:01:30', '2025-12-21 00:01:30'),
('ffce0083-f2a2-4020-9a79-7279a5942822', 'App\\Notifications\\UserNotification', 'App\\Models\\User', 30, '{\"fund_type\":\"Regt Fund 64 Aslt Engr Regt\",\"username\":\"admin\",\"message\":\"Voucher Added By admin\",\"link\":\"\"}', NULL, '2026-01-08 10:44:20', '2026-01-08 10:44:20');

-- --------------------------------------------------------

--
-- Table structure for table `officers`
--

CREATE TABLE `officers` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `officer_rank` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `officer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `officer_tos` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `officer_sos` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `marital_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subscriptions_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pcda_transactions`
--

CREATE TABLE `pcda_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `subcategory_id` bigint UNSIGNED DEFAULT NULL,
  `fund_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_type` enum('sent_to_pcda','booked_by_pcda') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `transaction_date` date NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `type`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(2, 'dashboard', 'dashboard', 'web', '2024-08-30 01:50:09', '2024-08-31 04:41:08'),
(3, 'user', 'useradd', 'web', '2024-08-30 01:50:15', '2024-08-31 04:41:15'),
(5, 'user', 'userslist', 'web', '2024-08-31 01:11:19', '2024-08-31 04:41:22'),
(6, 'user', 'useredit', 'web', '2024-08-31 04:42:04', '2024-08-31 04:42:04'),
(7, 'user', 'userupdate', 'web', '2024-08-31 04:42:12', '2024-08-31 04:42:12'),
(8, 'user', 'userpassword', 'web', '2024-08-31 04:42:19', '2024-08-31 04:42:19'),
(9, 'user', 'userdelete', 'web', '2024-08-31 04:42:27', '2024-08-31 04:42:27'),
(10, 'role', 'roles', 'web', '2024-08-31 04:42:42', '2024-08-31 04:42:42'),
(11, 'role', 'roleadd', 'web', '2024-08-31 04:43:52', '2024-08-31 04:43:52'),
(12, 'role', 'roleupdate', 'web', '2024-08-31 04:43:58', '2024-08-31 04:43:58'),
(13, 'role', 'roledelete', 'web', '2024-08-31 04:44:04', '2024-08-31 04:44:04'),
(14, 'role', 'rolegetpermissions', 'web', '2024-08-31 04:44:10', '2024-08-31 04:44:10'),
(15, 'role', 'rolesetpermissions', 'web', '2024-08-31 04:44:18', '2024-08-31 04:44:18'),
(17, 'category', 'categoryadd', 'web', '2024-09-09 05:29:18', '2024-09-09 05:29:18'),
(18, 'category', 'categorylist', 'web', '2024-09-09 05:29:18', '2024-09-09 05:29:18'),
(19, 'category', 'categoryupdate', 'web', '2024-09-09 05:29:18', '2024-09-09 05:29:18'),
(20, 'category', 'categorydelete', 'web', '2024-09-09 05:29:18', '2024-09-09 05:29:18'),
(21, 'category', 'subcategoryadd', 'web', '2024-09-09 05:29:18', '2024-09-09 05:29:18'),
(22, 'category', 'subcategorylist', 'web', '2024-09-09 05:29:18', '2024-09-09 05:29:18'),
(23, 'category', 'subcategoryupdate', 'web', '2024-09-09 05:29:18', '2024-09-09 05:29:18'),
(24, 'category', 'subcategorydelete', 'web', '2024-09-09 05:29:18', '2024-09-09 05:29:18'),
(25, 'voucher', 'voucher', 'web', '2024-09-09 05:29:18', '2024-09-09 05:29:18'),
(26, 'voucher', 'voucheradd', 'web', '2024-09-09 05:29:18', '2024-09-09 05:29:18'),
(27, 'voucher', 'voucheredit', 'web', '2024-09-18 12:53:08', '2024-09-18 12:53:14'),
(28, 'voucher', 'voucherupdate', 'web', '2024-09-18 12:53:16', '2024-09-18 12:53:19'),
(29, 'voucher', 'voucherdelete', 'web', '2024-09-18 12:53:21', '2024-09-18 12:53:24'),
(30, 'crv', 'crv', 'web', '2024-09-18 12:53:26', '2024-09-18 12:53:29'),
(31, 'crv', 'crvadd', 'web', '2024-09-18 12:55:01', '2024-09-18 12:55:04'),
(32, 'crv', 'crvedit', 'web', '2024-09-18 12:55:07', '2024-09-18 12:55:14'),
(33, 'crv', 'crvupdate', 'web', '2024-09-18 12:55:17', '2024-09-18 12:55:20'),
(34, 'crv', 'crvdelete', 'web', '2024-09-18 12:55:22', '2024-09-18 12:55:24'),
(35, 'niv', 'niv', 'web', '2024-09-18 12:55:26', '2024-09-18 12:55:28'),
(36, 'niv', 'nivadd', 'web', '2024-09-18 12:55:31', '2024-09-18 12:55:33'),
(37, 'niv', 'nivedit', 'web', '2024-09-18 12:55:35', '2024-09-18 12:55:37'),
(38, 'niv', 'nivupdate', 'web', '2024-09-18 12:55:39', '2024-09-18 12:55:42'),
(39, 'niv', 'nivdelete', 'web', '2024-09-18 12:55:09', '2024-09-18 12:55:11'),
(40, 'civ', 'civ', 'web', '2024-09-20 04:28:52', '2024-09-20 04:28:52'),
(41, 'crv', 'crvledger', 'web', '2024-10-08 10:06:13', '2024-10-08 10:06:13'),
(43, 'niv', 'nivledger', 'web', '2024-10-08 12:06:21', '2024-10-08 12:06:21'),
(44, 'user', 'unitslist', 'web', '2025-11-22 16:57:39', '2025-11-22 16:57:39'),
(45, 'user', 'unitadd', 'web', '2025-11-22 16:57:39', '2025-11-22 16:57:39'),
(46, 'user', 'unitedit', 'web', '2025-11-22 16:57:39', '2025-11-22 16:57:39'),
(47, 'user', 'unitupdate', 'web', '2025-11-22 16:57:39', '2025-11-22 16:57:39'),
(48, 'user', 'unitdelete', 'web', '2025-11-22 16:57:39', '2025-11-22 16:57:39'),
(49, 'voucher', 'voucherp', 'web', '2025-12-24 16:37:16', NULL),
(50, 'voucher', 'voucherpadd', 'web', '2025-12-24 16:37:16', NULL),
(51, 'voucher', 'voucherpedit', 'web', '2025-12-24 16:37:16', NULL),
(52, 'voucher', 'voucherpupdate', 'web', '2025-12-24 16:37:16', NULL),
(53, 'voucher', 'voucherpdelete', 'web', '2025-12-24 16:37:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `pro_item` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pro_date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pro_qty` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pro_total` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pro_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pro_remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(3, 'Accounts Officer', 'web', '2024-08-29 12:48:47', '2024-10-28 06:45:34'),
(4, 'Accounts Clerk', 'web', '2024-08-29 12:48:54', '2024-10-28 06:45:59'),
(10, 'Commanding Officer', 'web', '2024-10-28 06:45:02', '2024-10-28 06:45:02'),
(11, 'Store Holder', 'web', '2024-10-28 06:47:14', '2024-10-28 06:47:14'),
(12, 'MESS NCO', 'web', '2024-10-28 06:48:01', '2024-10-28 06:48:01');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(2, 3),
(25, 3),
(26, 3),
(27, 3),
(28, 3),
(29, 3),
(2, 4),
(3, 4),
(5, 4),
(6, 4),
(7, 4),
(8, 4),
(9, 4),
(10, 4),
(11, 4),
(12, 4),
(13, 4),
(14, 4),
(15, 4),
(17, 4),
(18, 4),
(19, 4),
(20, 4),
(21, 4),
(22, 4),
(23, 4),
(24, 4),
(25, 4),
(26, 4),
(27, 4),
(28, 4),
(29, 4),
(30, 4),
(31, 4),
(32, 4),
(33, 4),
(34, 4),
(35, 4),
(36, 4),
(37, 4),
(38, 4),
(39, 4),
(40, 4),
(41, 4),
(43, 4),
(49, 4),
(50, 4),
(51, 4),
(52, 4),
(53, 4),
(2, 10),
(3, 10),
(5, 10),
(6, 10),
(7, 10),
(8, 10),
(9, 10),
(10, 10),
(11, 10),
(12, 10),
(13, 10),
(14, 10),
(15, 10),
(17, 10),
(18, 10),
(19, 10),
(20, 10),
(21, 10),
(22, 10),
(23, 10),
(24, 10),
(25, 10),
(26, 10),
(27, 10),
(28, 10),
(29, 10),
(30, 10),
(31, 10),
(32, 10),
(33, 10),
(34, 10),
(35, 10),
(36, 10),
(37, 10),
(38, 10),
(39, 10),
(40, 10),
(41, 10),
(43, 10),
(2, 11),
(25, 11),
(26, 11),
(27, 11),
(28, 11),
(29, 11),
(30, 11),
(31, 11),
(32, 11),
(33, 11),
(34, 11),
(35, 11),
(36, 11),
(37, 11),
(38, 11),
(39, 11),
(40, 11),
(41, 11),
(43, 11),
(2, 12),
(25, 12),
(26, 12),
(27, 12),
(28, 12),
(29, 12),
(30, 12),
(31, 12),
(32, 12),
(33, 12),
(34, 12),
(35, 12),
(36, 12),
(37, 12),
(38, 12),
(39, 12),
(40, 12),
(41, 12),
(43, 12);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('BoXwGQxSNEwOoWgskiY7G20FvrhjrhtPS3eCJuN8', NULL, '66.132.195.77', 'Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVHQ4R0k5VVU0enZIQzJCVG5Ia1FlekFkVG84U2t5cXRON09pWmFSZiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8zNC4yMjMuMTkyLjUxIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1779698898),
('cuohgbOpomZBAOACcILDtNc26CpZ2u1KbNEM57OE', NULL, '66.132.195.77', 'Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUFozZnVTcFY3TEtORWlFZ1pwOGszTEFWWUl4bEZmc1R5OWo0VzRuVSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8zNC4yMjMuMTkyLjUxIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1779698947),
('dNDalE33bMzC9NV9zJOOYxcX7ME2p3wuLNYHZSgW', NULL, '185.242.226.102', 'python-requests/2.26.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZExhbGZBZjR1cnl4NTdUYVJaa29CQjNJSmdGaUNDN2RsbmVmTUtJTiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8zNC4yMjMuMTkyLjUxIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1779696179),
('GOx3Ur7HdAaotqpftDDuuMs2r8oPHlM848W4Ef8V', NULL, '45.196.233.42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOVhhQzlJTkt3dmRIemwxbnBHeUp4ckg0Q3RpdVNJSEJvUWhGemFnRyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjU6Imh0dHA6Ly8xNDYuNTYuMTgwLjQyOjMzMzMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1779705883),
('gXXXH8zX3sVmx7PF7f5ec78xqXGH8nRmIOMyqb6u', NULL, '43.133.14.237', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiT3ZVbzQ4d21tSU83TVRBbVFUMmc5QTBsNWxYY1VtdzQ5YWRDNWVMdiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8zNC4yMjMuMTkyLjUxIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1779703693),
('OJXJ0nbFFQQ1UQShMGuusWMKMLto7EJiFftqN9T7', NULL, '213.209.159.175', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36 Edge/15.15063', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTlRGQVhCS0RpdkdHV1pmR0Fsc29FeWFwQTdhQk5IZUdxZWNURFpIZyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8zNC4yMjMuMTkyLjUxLz9waHBpbmZvPS0xIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1779702910),
('q50vImoaQASTr8sBm3aPRXP3vmJeZ6Ml4XpHxkGk', NULL, '193.239.176.240', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.5845.140 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiVXJMcDZnN3JTWUlubWR5aW1iSkh1VUpTZmtNS1RnSXhOZ080NmVJdSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1779700650),
('UO9FFO4mH09WyGe01ueoUsI5pYB46BT4sxV0cIy6', NULL, '34.52.167.224', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoic2ZGOVpQcGxKcUdjYUlsbm80TGFlT0ZEc2FKSjNsc01iMkNsbnZCUSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8zNC4yMjMuMTkyLjUxIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1779696340),
('UvT3D4IbyHZljlIgFjxQ6Hq3hYSpyVgUpHf73KUL', NULL, '43.134.141.244', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTzhtVWhZNEJyVlJWRmZnNzY3UFNPTmpPZmtpMlZwYmhmTHB2WWwwTyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTY6Imh0dHA6Ly9lYzItMzQtMjIzLTE5Mi01MS51cy13ZXN0LTIuY29tcHV0ZS5hbWF6b25hd3MuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1779702866);

-- --------------------------------------------------------

--
-- Table structure for table `sycrs`
--

CREATE TABLE `sycrs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `sycr_label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sycr_amount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sycr_date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sydrs`
--

CREATE TABLE `sydrs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `sydr_label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sydr_amount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sydr_date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `total_allotments`
--

CREATE TABLE `total_allotments` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `subcategory_id` bigint UNSIGNED NOT NULL,
  `fund_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `allotment_amount` decimal(15,2) NOT NULL,
  `allotment_date` date NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint UNSIGNED NOT NULL,
  `unit_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `unit_name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(8, '64 Aslt Engr Regt', NULL, 1, '2025-11-22 17:04:39', '2025-12-24 09:36:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_pic` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `question` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `profile_pic`, `unit_name`, `question`, `created_at`, `updated_at`) VALUES
(8, 'admin', 'admin@admin.com', NULL, '$2y$12$TZrDvDtypqM42Whb5uMfpem/moUqGa7KUgfvbItHk4H0MTo3Sn/oC', '1', NULL, 'profilepic/9x4Gl9H89P8KZuyIfsf08mUwksHHz2FDDrHgVV3v.jpg', 'ad.unit', '28/10/2024', NULL, '2025-01-10 10:43:37'),
(28, 'Rahul', NULL, NULL, '$2y$12$8ymCl240NQyOMAwRyNSOlu6N01.Pe3EIdMWpKZsDfEfGfqeneiKRO', 'Accounts Officer', NULL, NULL, NULL, NULL, '2025-02-21 18:32:35', '2025-02-21 18:32:35'),
(29, 'test', NULL, NULL, '$2y$12$LIvouBomSWBJL7Ii92Kc3.eG0qS6dL9IZaOkK/eg440nOvzqfa5oq', 'Accounts Officer', NULL, NULL, NULL, NULL, '2025-06-27 11:43:04', '2025-06-27 11:43:04'),
(30, 'Accts Clk', NULL, NULL, '$2y$12$mT6YWahbjcb1xUG2jqfDyO9w6MqxHRlzIDyaD00wsd07EvxHNN8Uq', 'Accounts Clerk', NULL, NULL, NULL, NULL, '2025-12-24 13:16:31', '2025-12-24 13:16:31');

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `category_id` int NOT NULL,
  `voc_fund_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voc_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voc_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voc_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voc_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voc_whom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voc_acc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voc_cash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voc_bank` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voc_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `voc_memo_stk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voc_property` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voc_fd` decimal(10,2) NOT NULL DEFAULT '0.00',
  `voucher_entery` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`id`, `user_id`, `category_id`, `voc_fund_type`, `voc_type`, `voc_date`, `voc_no`, `voc_file`, `voc_whom`, `voc_acc`, `voc_cash`, `voc_bank`, `voc_json`, `voc_memo_stk`, `voc_property`, `voc_fd`, `voucher_entery`, `created_at`, `updated_at`) VALUES
(1, 8, 101, 'Regt Fund 64 Aslt Engr Regt', 'Payment', '2025-10-01', NULL, NULL, NULL, NULL, '0', '0', '{\"sy_dr\":0,\"regt_head\":0,\"edn_head\":0,\"gurudwara\":0,\"mandir\":0,\"jazz_band\":0,\"sports\":0,\"sy_cr\":0}', '0', '0', 0.00, 0, NULL, NULL),
(2, 8, 101, 'Regt Fund 64 Aslt Engr Regt', 'Receipt', '2025-10-01', NULL, NULL, NULL, NULL, '0', '0', '{\"sy_dr\":0,\"regt_head\":0,\"edn_head\":0,\"gurudwara\":0,\"mandir\":0,\"jazz_band\":0,\"sports\":0,\"sy_cr\":0}', '0', '0', 0.00, 0, NULL, NULL),
(3, 8, 101, 'Regt Fund 64 Aslt Engr Regt', 'Receipt', '2025-10-01', 'RV-11', 'vouchers/K7kAoELYW9RPL0XQXd3nGuwNRIYR37F0GLB0ewMO.pdf', 'Offrs, Jcos, ORs', 'Regtl and pic cut for the month of Oct 2025', '144155.00', '0', '{\"sy_dr\":null,\"regt_head\":\"80675.00\",\"edn_head\":null,\"gurudwara\":null,\"mandir\":null,\"jazz_band\":null,\"sports\":null,\"sy_cr\":\"63480.00\"}', NULL, NULL, 0.00, 1, '2026-01-09 15:21:11', '2026-01-09 15:22:48'),
(4, 8, 101, 'Regt Fund 64 Aslt Engr Regt', 'Receipt', '2025-10-27', 'RV-12', 'vouchers/HDFCM3QGpbEVPMqm5RKHRWg21XRr6QAhVgq19JZI.pdf', 'Central AWWA', 'Clearance of SY DR', '0', '30000', '{\"sy_dr\":\"30000\",\"regt_head\":null,\"edn_head\":null,\"gurudwara\":null,\"mandir\":null,\"jazz_band\":null,\"sports\":null,\"sy_cr\":null}', NULL, NULL, 0.00, 1, '2026-01-09 15:24:24', '2026-01-09 15:24:24'),
(5, 8, 101, 'Regt Fund 64 Aslt Engr Regt', 'Payment', '2025-10-11', 'PV-55', 'vouchers/qaht8LRxnlleY8UugDDlJV0f9D22CGeEmkEFJ0hd.pdf', 'Nb Sub Paramjeet', 'Regt football team spl diet', '0', '20000', '{\"sy_dr\":null,\"regt_head\":\"20000\",\"edn_head\":null,\"gurudwara\":null,\"mandir\":null,\"jazz_band\":null,\"sports\":null,\"sy_cr\":null}', NULL, NULL, 0.00, 1, '2026-01-12 14:52:33', '2026-01-12 14:52:33'),
(6, 8, 101, 'Regt Fund 64 Aslt Engr Regt', 'Payment', '2025-10-11', 'PV-56', 'vouchers/bWcYx3ohiz6OYy7sVebRNnUy9fr9eNjRY2q1Piet.pdf', 'Nb Sub Amresh Bahadur', 'Inter sqn kabbadi prize', '0', '5000', '{\"sy_dr\":null,\"regt_head\":\"5000\",\"edn_head\":null,\"gurudwara\":null,\"mandir\":null,\"jazz_band\":null,\"sports\":null,\"sy_cr\":null}', NULL, NULL, 0.00, 1, '2026-01-12 14:54:06', '2026-01-12 14:54:06'),
(7, 8, 101, 'Regt Fund 64 Aslt Engr Regt', 'Payment', '2025-11-01', NULL, NULL, NULL, NULL, '0', '0', '{\"sy_dr\":0,\"regt_head\":0,\"edn_head\":0,\"gurudwara\":0,\"mandir\":0,\"jazz_band\":0,\"sports\":0,\"sy_cr\":0}', '0', '0', 0.00, 0, NULL, NULL),
(8, 8, 101, 'Regt Fund 64 Aslt Engr Regt', 'Receipt', '2025-11-01', NULL, NULL, NULL, NULL, '0', '0', '{\"sy_dr\":0,\"regt_head\":0,\"edn_head\":0,\"gurudwara\":0,\"mandir\":0,\"jazz_band\":0,\"sports\":0,\"sy_cr\":0}', '0', '0', 0.00, 0, NULL, NULL),
(9, 8, 101, 'Regt Fund 64 Aslt Engr Regt', 'Payment', '2025-11-06', 'PV-57', 'vouchers/guuzVH6rl5Yd1wX3OWwDeUbs2pmkLm3siGzexVsH.pdf', 'Sub Sachin Singh', 'Pocso act lecture expdr', '0', '7245', '{\"sy_dr\":null,\"regt_head\":\"7245\",\"edn_head\":null,\"gurudwara\":null,\"mandir\":null,\"jazz_band\":null,\"sports\":null,\"sy_cr\":null}', NULL, '6200', 0.00, 1, '2026-01-12 15:23:38', '2026-01-12 15:23:38'),
(11, 8, 1, 'Regt Fund', 'Receipt', '2026-01-01', 'R120', 'vouchers/Hm0BrQm9decVC688loOUzhluXCNzghp9xQ5BABnV.png', 'R Bty', 'Regt Subd', '9450', '0', '{\"welfare_fund_(5%)\":\"9450\"}', NULL, NULL, 0.00, 1, '2026-01-12 15:30:14', '2026-01-12 15:30:14'),
(12, 8, 1, 'Regt Fund', 'Receipt', '2026-01-01', NULL, NULL, NULL, NULL, '0', '0', '{\"welfare_fund_(5%)\":0}', '0', '0', 0.00, 0, NULL, NULL),
(13, 8, 101, 'Regt Fund 64 Aslt Engr Regt', 'Payment', '2025-11-11', 'CE-11', 'vouchers/4FyKfxR9ibkGvPK5ywBH0t9JcWMBs5PYjiQD97Gd.pdf', 'SBI', 'Cash to Bank', '200000', '0', '{\"sy_dr\":null,\"regt_head\":null,\"edn_head\":null,\"gurudwara\":null,\"mandir\":null,\"jazz_band\":null,\"sports\":null,\"sy_cr\":null}', NULL, NULL, 0.00, 1, '2026-01-12 15:33:40', '2026-01-12 15:33:40'),
(14, 8, 101, 'Regt Fund 64 Aslt Engr Regt', 'Payment', '2025-11-27', 'PV-58', 'vouchers/ObryAh4tBwi2tJZ2sZp24a7AepA12Obv1bLmkzWc.pdf', 'Rt jco', 'Mandir expdr', '0', '17820', '{\"sy_dr\":null,\"regt_head\":\"17820\",\"edn_head\":null,\"gurudwara\":null,\"mandir\":null,\"jazz_band\":null,\"sports\":null,\"sy_cr\":null}', NULL, NULL, 0.00, 1, '2026-01-12 15:43:30', '2026-01-12 15:43:30'),
(15, 8, 101, 'Regt Fund 64 Aslt Engr Regt', 'Payment', '2025-11-27', 'PV-59', 'vouchers/xY2kaQrU6QL8GtmZ1uMsfHLCT9rNM6Q2rzjoHmBo.pdf', 'Sagat Singh Auditorium', 'Pic cut', '0', '278280', '{\"sy_dr\":null,\"regt_head\":null,\"edn_head\":null,\"gurudwara\":null,\"mandir\":null,\"jazz_band\":null,\"sports\":null,\"sy_cr\":\"278280\"}', NULL, NULL, 0.00, 1, '2026-01-12 15:46:56', '2026-01-12 15:46:56'),
(16, 8, 101, 'Regt Fund 64 Aslt Engr Regt', 'Payment', '2025-11-29', 'CE-12', 'vouchers/ate2REH4aamtjca53h4YZh7tN1HFAA8cdWVmP9uK.pdf', 'SBI', 'Cash to bank', '21480', '0', '{\"sy_dr\":null,\"regt_head\":null,\"edn_head\":null,\"gurudwara\":null,\"mandir\":null,\"jazz_band\":null,\"sports\":null,\"sy_cr\":null}', NULL, NULL, 0.00, 1, '2026-01-12 15:49:31', '2026-01-12 15:49:31'),
(17, 8, 101, 'Regt Fund 64 Aslt Engr Regt', 'Payment', '2025-11-30', 'PV-60', 'vouchers/mwJA6K5mPQZmApUBfn5h1FXUWyFsVSVCviX75Kcu.pdf', 'Sagat Singh Auditorium', 'Pic cut', '190380', '0', '{\"sy_dr\":null,\"regt_head\":null,\"edn_head\":null,\"gurudwara\":null,\"mandir\":null,\"jazz_band\":null,\"sports\":null,\"sy_cr\":\"190380\"}', NULL, NULL, 0.00, 1, '2026-01-12 15:56:57', '2026-01-12 15:56:57'),
(18, 8, 101, 'Regt Fund 64 Aslt Engr Regt', 'Receipt', '2025-11-01', 'RV-13', 'vouchers/GR5FCJ5FJPuDbVKBR1CziPbllA6XiVPv3dkU7ZXA.pdf', 'Regt cut', 'Regt cut', '144995', '0', '{\"sy_dr\":null,\"regt_head\":\"81955\",\"edn_head\":null,\"gurudwara\":null,\"mandir\":null,\"jazz_band\":null,\"sports\":null,\"sy_cr\":\"63040\"}', NULL, NULL, 0.00, 1, '2026-01-12 16:02:41', '2026-01-12 16:02:41'),
(19, 8, 101, 'Regt Fund 64 Aslt Engr Regt', 'Receipt', '2025-11-11', 'CE-11', 'vouchers/j00z9wSSrxjvhNd46QXZkS0KUpEujARwIeiGa4Fq.pdf', 'SBI', 'Cash to bank', '0', '200000', '{\"sy_dr\":null,\"regt_head\":null,\"edn_head\":null,\"gurudwara\":null,\"mandir\":null,\"jazz_band\":null,\"sports\":null,\"sy_cr\":null}', NULL, NULL, 0.00, 1, '2026-01-12 16:04:04', '2026-01-12 16:04:04'),
(20, 8, 101, 'Regt Fund 64 Aslt Engr Regt', 'Receipt', '2025-11-29', 'RV-14', 'vouchers/uTkJ1YJcfGgf5WtMxLFBoG0jZBH6u9msrizml1dz.pdf', 'AWWA Fund Acct', 'Pocso lec payment', '0', '61004.00', '{\"sy_dr\":null,\"regt_head\":null,\"edn_head\":null,\"gurudwara\":null,\"mandir\":null,\"jazz_band\":null,\"sports\":null,\"sy_cr\":\"61004.00\"}', NULL, NULL, 0.00, 1, '2026-01-12 16:10:53', '2026-01-12 16:10:53'),
(21, 8, 101, 'Regt Fund 64 Aslt Engr Regt', 'Receipt', '2025-11-29', 'CE-13', 'vouchers/l0jWSngSnhMAHZmoxCEV4PgybunxWhbJLZGcS3Kd.pdf', 'SBI', 'Cash to bank', '0', '21480', '{\"sy_dr\":null,\"regt_head\":null,\"edn_head\":null,\"gurudwara\":null,\"mandir\":null,\"jazz_band\":null,\"sports\":null,\"sy_cr\":null}', NULL, NULL, 0.00, 1, '2026-01-12 16:17:39', '2026-01-12 16:17:39'),
(22, 8, 101, 'Regt Fund 64 Aslt Engr Regt', 'Payment', '2025-12-01', NULL, NULL, NULL, NULL, '0', '0', '{\"sy_dr\":0,\"regt_head\":0,\"edn_head\":0,\"gurudwara\":0,\"mandir\":0,\"jazz_band\":0,\"sports\":0,\"sy_cr\":0}', '0', '0', 0.00, 0, NULL, NULL),
(23, 8, 101, 'Regt Fund 64 Aslt Engr Regt', 'Receipt', '2025-12-01', NULL, NULL, NULL, NULL, '0', '0', '{\"sy_dr\":0,\"regt_head\":0,\"edn_head\":0,\"gurudwara\":0,\"mandir\":0,\"jazz_band\":0,\"sports\":0,\"sy_cr\":0}', '0', '0', 0.00, 0, NULL, NULL),
(24, 8, 101, 'Regt Fund 64 Aslt Engr Regt', 'Payment', '2025-12-01', 'CE 13', 'vouchers/218CAW4X0HlanR8ULSGptDsG6GLwgRGBzU0rnb0C.pdf', 'PNB Bathinda Mil Stn', 'Cash to Bank', '143660', '0', '{\"sy_dr\":null,\"regt_head\":null,\"edn_head\":null,\"gurudwara\":null,\"mandir\":null,\"jazz_band\":null,\"sports\":null,\"sy_cr\":null}', NULL, NULL, 0.00, 1, '2026-01-13 12:45:32', '2026-01-13 16:30:00'),
(25, 8, 101, 'Regt Fund 64 Aslt Engr Regt', 'Payment', '2025-12-01', 'PV - 61', 'vouchers/JgigftWZdhx1hsuQ4F9DczbZZ1csNfP7e1x4zjzL.pdf', 'Nb Sub Kaman Singh', 'Clearance of Sy Cr', '0', '61004', '{\"sy_dr\":null,\"regt_head\":null,\"edn_head\":null,\"gurudwara\":null,\"mandir\":null,\"jazz_band\":null,\"sports\":null,\"sy_cr\":\"61004\"}', NULL, NULL, 0.00, 1, '2026-01-13 12:47:53', '2026-01-13 12:47:53'),
(26, 8, 101, 'Regt Fund 64 Aslt Engr Regt', 'Payment', '2025-12-01', 'PV - 62', 'vouchers/uVFQUqgdgpZt95KxKz8n1M7ljT6xGio3gAafHjhx.pdf', 'Nb Sub Trepan Singh', 'Expde', '0', '33436', '{\"sy_dr\":null,\"regt_head\":\"33436\",\"edn_head\":null,\"gurudwara\":null,\"mandir\":null,\"jazz_band\":null,\"sports\":null,\"sy_cr\":null}', NULL, NULL, 0.00, 1, '2026-01-13 12:48:55', '2026-01-13 12:48:55'),
(27, 8, 101, 'Regt Fund 64 Aslt Engr Regt', 'Receipt', '2025-12-01', 'RV -15', 'vouchers/EWfbKPbwTlSv1M6ecg5iSVn7Rhu2XcA3RS6tQKo4.pdf', 'Regt', 'Regtl Cut', '148080', '0', '{\"sy_dr\":null,\"regt_head\":\"83180\",\"edn_head\":null,\"gurudwara\":null,\"mandir\":null,\"jazz_band\":null,\"sports\":null,\"sy_cr\":\"64900\"}', NULL, NULL, 0.00, 1, '2026-01-13 12:51:09', '2026-01-13 12:51:09'),
(28, 8, 101, 'Regt Fund 64 Aslt Engr Regt', 'Receipt', '2025-12-01', 'CE 13', 'vouchers/R4nqEi9UyRXb8tEEEvtj0PbN8w19G1M7lcWNNMS6.pdf', 'PNB Bathinda Mil Stn', 'Cash to Bank', '0', '143660', '{\"sy_dr\":null,\"regt_head\":null,\"edn_head\":null,\"gurudwara\":null,\"mandir\":null,\"jazz_band\":null,\"sports\":null,\"sy_cr\":null}', NULL, NULL, 0.00, 1, '2026-01-13 12:51:52', '2026-01-13 12:51:52'),
(29, 8, 101, 'Regt Fund 64 Aslt Engr Regt', 'Receipt', '2025-12-01', 'CE 14', 'vouchers/y6kjak0uYqYeHwZoBnILcHB37wEmCkJXhK7cPOn5.pdf', 'PNB Bathinda Mil Stn', 'Interest Recd', '0', '716', '{\"sy_dr\":null,\"regt_head\":\"716\",\"edn_head\":null,\"gurudwara\":null,\"mandir\":null,\"jazz_band\":null,\"sports\":null,\"sy_cr\":null}', NULL, NULL, 0.00, 1, '2026-01-13 12:52:30', '2026-01-13 12:52:30'),
(30, 8, 101, 'Regt Fund 64 Aslt Engr Regt', 'Receipt', '2025-12-31', 'RV -16', 'vouchers/tx9VaGJB48xQgG3vMdCgWnskyM9QxgjR4A3d5PPh.pdf', 'Central AWWA', 'Clearance of Sy Dr', '0', '30000.00', '{\"sy_dr\":\"30000.00\",\"regt_head\":null,\"edn_head\":null,\"gurudwara\":null,\"mandir\":null,\"jazz_band\":null,\"sports\":null,\"sy_cr\":null}', NULL, NULL, 0.00, 1, '2026-01-13 12:53:25', '2026-01-13 12:53:25'),
(31, 8, 101, 'Regt Fund 64 Aslt Engr Regt', 'Payment', '2026-01-01', NULL, NULL, NULL, NULL, '0', '0', '{\"sy_dr\":0,\"regt_head\":0,\"edn_head\":0,\"gurudwara\":0,\"mandir\":0,\"jazz_band\":0,\"sports\":0,\"sy_cr\":0}', '0', '0', 0.00, 0, NULL, NULL),
(32, 8, 101, 'Regt Fund 64 Aslt Engr Regt', 'Receipt', '2026-01-01', NULL, NULL, NULL, NULL, '0', '0', '{\"sy_dr\":0,\"regt_head\":0,\"edn_head\":0,\"gurudwara\":0,\"mandir\":0,\"jazz_band\":0,\"sports\":0,\"sy_cr\":0}', '0', '0', 0.00, 0, NULL, NULL),
(33, 8, 62, 'CSD', 'Receipt', '2025-11-01', NULL, NULL, NULL, NULL, '0', '0', '{\"grocery_stock\":0,\"liquor_stock\":0,\"profit\":0,\"fund_control\":0,\"sy_cr\":0,\"sy_dr\":0}', '0', '0', 0.00, 0, NULL, NULL),
(34, 8, 62, 'CSD', 'Payment', '2025-11-01', NULL, NULL, NULL, NULL, '0', '0', '{\"grocery_stock\":0,\"liquor_stock\":0,\"profit\":0,\"fund_control\":0,\"sy_cr\":0,\"sy_dr\":0}', '0', '0', 0.00, 0, NULL, NULL),
(35, 8, 62, 'CSD', 'Receipt', '2025-11-30', 'RV 219', 'vouchers/ZZGIaaV4YSxVyc0R7YAGdau8y71lU7YiPPMeNlxR.pdf', 'CSD NCO', 'Sale of Stock', '0', '1469672', '{\"grocery_stock\":\"638488.02\",\"liquor_stock\":\"692244.68\",\"profit\":\"91008.03\",\"fund_control\":\"0\",\"sy_cr\":\"53210.00\",\"sy_dr\":\"0\"}', NULL, '0', 0.00, 1, '2026-01-14 08:32:21', '2026-01-14 08:32:59'),
(36, 8, 62, 'CSD', 'Payment', '2025-11-30', 'PV 17', 'vouchers/hx3DWyHSigRECsCNKgTOXeBGENfWRqqkjkJcsP5E.pdf', 'PV 17', 'Purhase of stock', '0', '1554897.00', '{\"grocery_stock\":\"910276.90\",\"liquor_stock\":\"640106.92\",\"profit\":\"9791.91\",\"fund_control\":\"0\",\"sy_cr\":\"0\",\"sy_dr\":\"0\"}', NULL, '0', 0.00, 1, '2026-01-14 08:36:28', '2026-01-14 08:36:28'),
(37, 8, 62, 'CSD', 'Payment', '2025-12-01', NULL, NULL, NULL, NULL, '0', '0', '{\"grocery_stock\":0,\"liquor_stock\":0,\"profit\":0,\"fund_control\":0,\"sy_cr\":0,\"sy_dr\":0}', '0', '0', 0.00, 0, NULL, NULL),
(38, 8, 62, 'CSD', 'Receipt', '2025-12-01', NULL, NULL, NULL, NULL, '0', '0', '{\"grocery_stock\":0,\"liquor_stock\":0,\"profit\":0,\"fund_control\":0,\"sy_cr\":0,\"sy_dr\":0}', '0', '0', 0.00, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vouchers_bbf`
--

CREATE TABLE `vouchers_bbf` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` int NOT NULL,
  `voc_fund_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voc_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voc_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voc_cash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voc_bank` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voc_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `voc_memo_stk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voc_property` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voc_fd` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_allotment` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vouchers_bbf`
--

INSERT INTO `vouchers_bbf` (`id`, `category_id`, `voc_fund_type`, `voc_type`, `voc_date`, `voc_cash`, `voc_bank`, `voc_json`, `voc_memo_stk`, `voc_property`, `voc_fd`, `total_allotment`, `created_at`, `updated_at`) VALUES
(1, 101, 'Regt Fund 64 Aslt Engr Regt', 'Payment', '2025-10-01', '0', '0', '{\"sy_dr\":\"128000\",\"regt_head\":null,\"edn_head\":null,\"gurudwara\":null,\"mandir\":null,\"jazz_band\":null,\"sports\":null,\"sy_cr\":null}', NULL, '1709929.05', 2710189.00, 0.00, '2026-01-09 15:12:27', '2026-01-09 15:12:27'),
(2, 101, 'Regt Fund 64 Aslt Engr Regt', 'Receipt', '2025-10-01', '230370.18', '29936.30', '{\"sy_dr\":null,\"regt_head\":\"21045.48\",\"edn_head\":\"3334\",\"gurudwara\":\"7984\",\"mandir\":\"11041\",\"jazz_band\":\"639\",\"sports\":\"2123\",\"sy_cr\":\"342140\"}', NULL, '0', 0.00, 0.00, '2026-01-09 15:18:27', '2026-01-09 15:18:27'),
(3, 101, 'Regt Fund 64 Aslt Engr Regt', 'Payment', '2025-11-01', '0', '0', '{\"sy_dr\":\"98000\",\"regt_head\":\"0\",\"edn_head\":\"0\",\"gurudwara\":\"0\",\"mandir\":\"0\",\"jazz_band\":\"0\",\"sports\":\"0\",\"sy_cr\":\"0\"}', NULL, '1709929.05', 2710189.00, 0.00, '2026-01-12 14:55:42', '2026-01-12 14:55:42'),
(5, 101, 'Regt Fund 64 Aslt Engr Regt', 'Receipt', '2025-11-01', '374525.18', '34936.3', '{\"sy_dr\":\"0\",\"regt_head\":\"76720.48\",\"edn_head\":\"3334\",\"gurudwara\":\"7984\",\"mandir\":\"11041\",\"jazz_band\":\"639\",\"sports\":\"2123\",\"sy_cr\":\"405620\"}', NULL, '0', 0.00, 0.00, '2026-01-12 15:01:06', '2026-01-12 15:01:06'),
(6, 1, 'Regt Fund', 'Receipt', '2026-01-01', '7850', '0', '{\"welfare_fund_(5%)\":\"7850\"}', NULL, '150', 350.00, 0.00, '2026-01-12 15:31:12', '2026-01-12 15:31:12'),
(7, 101, 'Regt Fund 64 Aslt Engr Regt', 'Payment', '2025-12-01', '0', '0', '{\"sy_dr\":\"98000\",\"regt_head\":\"0\",\"edn_head\":\"0\",\"gurudwara\":\"0\",\"mandir\":\"0\",\"jazz_band\":\"0\",\"sports\":\"0\",\"sy_cr\":\"0\"}', NULL, '1716129.05', 2710189.00, 0.00, '2026-01-12 16:18:20', '2026-01-12 16:18:20'),
(8, 101, 'Regt Fund 64 Aslt Engr Regt', 'Receipt', '2025-12-01', '107660.18', '14075.3', '{\"sy_dr\":\"0\",\"regt_head\":\"133610.48\",\"edn_head\":\"3334\",\"gurudwara\":\"7984\",\"mandir\":\"11041\",\"jazz_band\":\"639\",\"sports\":\"2123\",\"sy_cr\":\"61004\"}', NULL, '0', 0.00, 0.00, '2026-01-12 16:19:12', '2026-01-12 16:19:12'),
(10, 101, 'Regt Fund 64 Aslt Engr Regt', 'Payment', '2026-01-01', '0', '0', '{\"sy_dr\":\"68000\",\"regt_head\":\"0\",\"edn_head\":\"0\",\"gurudwara\":\"0\",\"mandir\":\"0\",\"jazz_band\":\"0\",\"sports\":\"0\",\"sy_cr\":\"0\"}', NULL, '1716129.05', 2710189.00, 0.00, '2026-01-13 12:55:20', '2026-01-13 12:55:20'),
(11, 101, 'Regt Fund 64 Aslt Engr Regt', 'Receipt', '2026-01-01', '112140.18', '94011.3', '{\"sy_dr\":\"0\",\"regt_head\":\"184070.48\",\"edn_head\":\"3334\",\"gurudwara\":\"7984\",\"mandir\":\"11041\",\"jazz_band\":\"639\",\"sports\":\"2123\",\"sy_cr\":\"64900\"}', NULL, '0', 0.00, 0.00, '2026-01-13 16:25:04', '2026-01-13 16:25:04'),
(12, 101, 'Regt Fund 64 Aslt Engr Regt', 'Receipt', '2026-01-01', '112080.18', '94011.3', '{\"sy_dr\":\"0\",\"regt_head\":\"184070.48\",\"edn_head\":\"3334\",\"gurudwara\":\"7984\",\"mandir\":\"11041\",\"jazz_band\":\"639\",\"sports\":\"2123\",\"sy_cr\":\"64900\"}', NULL, '0', 0.00, 0.00, '2026-01-13 16:30:30', '2026-01-13 16:30:30'),
(13, 62, 'CSD', 'Receipt', '2025-11-01', '0', '0', '{\"grocery_stock\":\"2148458.49\",\"liquor_stock\":\"1927298.47\",\"profit\":null,\"fund_control\":null,\"sy_cr\":null,\"sy_dr\":\"9842.25\"}', NULL, '363014.64', 0.00, 0.00, '2026-01-14 08:24:30', '2026-01-14 08:24:30'),
(14, 62, 'CSD', 'Receipt', '2025-11-01', '0', '1883460.82', '{\"grocery_stock\":\"0\",\"liquor_stock\":null,\"profit\":\"4386456.58\",\"fund_control\":\"1578152.45\",\"sy_cr\":\"4451\",\"sy_dr\":\"0\"}', NULL, '0', 0.00, 0.00, '2026-01-14 08:26:01', '2026-01-14 08:26:01'),
(15, 62, 'CSD', 'Payment', '2025-11-01', '0', '0', '{\"grocery_stock\":\"2148458.49\",\"liquor_stock\":\"1927298.47\",\"profit\":\"0\",\"fund_control\":\"0\",\"sy_cr\":\"0\",\"sy_dr\":\"9842.25\"}', NULL, '363014.61', 0.00, 0.00, '2026-01-14 08:27:39', '2026-01-14 08:27:39'),
(16, 62, 'CSD', 'Payment', '2025-12-01', '0', '0', '{\"grocery_stock\":\"2420247.37\",\"liquor_stock\":\"1875160.71\",\"profit\":\"0\",\"fund_control\":\"0\",\"sy_cr\":\"0\",\"sy_dr\":\"9842.25\"}', NULL, '363014.61', 0.00, 0.00, '2026-01-14 08:37:29', '2026-01-14 08:37:29'),
(17, 62, 'CSD', 'Receipt', '2025-12-01', '0', '1798235.82', '{\"grocery_stock\":\"0\",\"liquor_stock\":\"0\",\"profit\":\"4467672.7\",\"fund_control\":\"1578152.45\",\"sy_cr\":\"57661\",\"sy_dr\":\"0\"}', NULL, '0', 0.00, 0.00, '2026-01-14 08:37:53', '2026-01-14 08:37:53'),
(18, 63, 'Mess Bill', 'Messbill', '2026-01-01', '0', '0', '{\"mess_fund\":\"0.00\",\"mma\":\"0.00\",\"cat_stock\":\"0.00\",\"wine_stock\":\"0.00\",\"memento_fund\":\"0.00\",\"jco_deposit\":\"0.00\",\"sy_dr\":\"0.00\",\"ent_fund\":\"0.00\",\"regt_cutting\":\"0.00\",\"soft\":\"0.00\",\"fd\":\"0.00\",\"sy_crs\":\"0.00\"}', NULL, NULL, 0.00, 0.00, '2026-01-15 05:06:30', '2026-01-15 05:06:30'),
(19, 37, 'Mess Bill', 'Messbill', '2026-02-01', '0', '0', '{\"mess_fund\":\"0.00\",\"mma\":\"0.00\",\"cat_stock\":\"0.00\",\"wine_stock\":\"0.00\",\"cig_and_soft\":\"0.00\",\"memento_fund\":\"0.00\",\"lib_fund\":\"0.00\",\"ent_fund\":\"0.00\",\"sports_fund\":\"0.00\",\"sy_dr\":\"0.00\",\"regt_cutting\":\"0.00\",\"officer_deposit\":\"0.00\",\"fd\":\"0.00\",\"sy_cr\":\"0.00\"}', NULL, NULL, 0.00, 0.00, '2026-02-19 10:53:28', '2026-02-19 10:53:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bankstatements`
--
ALTER TABLE `bankstatements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cheques`
--
ALTER TABLE `cheques`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `civs`
--
ALTER TABLE `civs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crvs`
--
ALTER TABLE `crvs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dbbackups`
--
ALTER TABLE `dbbackups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `grand_total`
--
ALTER TABLE `grand_total`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mess_bill_summary`
--
ALTER TABLE `mess_bill_summary`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mess_sub_category`
--
ALTER TABLE `mess_sub_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `nivs`
--
ALTER TABLE `nivs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `officers`
--
ALTER TABLE `officers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pcda_transactions`
--
ALTER TABLE `pcda_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pcda_transactions_user_id_foreign` (`user_id`),
  ADD KEY `pcda_transactions_category_id_foreign` (`category_id`),
  ADD KEY `pcda_transactions_subcategory_id_foreign` (`subcategory_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `sycrs`
--
ALTER TABLE `sycrs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sydrs`
--
ALTER TABLE `sydrs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `total_allotments`
--
ALTER TABLE `total_allotments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `total_allotments_user_id_foreign` (`user_id`),
  ADD KEY `total_allotments_category_id_foreign` (`category_id`),
  ADD KEY `total_allotments_subcategory_id_foreign` (`subcategory_id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `units_unit_name_unique` (`unit_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vouchers_bbf`
--
ALTER TABLE `vouchers_bbf`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bankstatements`
--
ALTER TABLE `bankstatements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `cheques`
--
ALTER TABLE `cheques`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `civs`
--
ALTER TABLE `civs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crvs`
--
ALTER TABLE `crvs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dbbackups`
--
ALTER TABLE `dbbackups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grand_total`
--
ALTER TABLE `grand_total`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mess_bill_summary`
--
ALTER TABLE `mess_bill_summary`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mess_sub_category`
--
ALTER TABLE `mess_sub_category`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `nivs`
--
ALTER TABLE `nivs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `officers`
--
ALTER TABLE `officers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pcda_transactions`
--
ALTER TABLE `pcda_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `sycrs`
--
ALTER TABLE `sycrs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sydrs`
--
ALTER TABLE `sydrs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `total_allotments`
--
ALTER TABLE `total_allotments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `vouchers_bbf`
--
ALTER TABLE `vouchers_bbf`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pcda_transactions`
--
ALTER TABLE `pcda_transactions`
  ADD CONSTRAINT `pcda_transactions_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pcda_transactions_subcategory_id_foreign` FOREIGN KEY (`subcategory_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pcda_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `total_allotments`
--
ALTER TABLE `total_allotments`
  ADD CONSTRAINT `total_allotments_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `total_allotments_subcategory_id_foreign` FOREIGN KEY (`subcategory_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `total_allotments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
