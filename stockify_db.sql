-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2025 at 12:04 AM
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
-- Database: `stockify_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `auditable_type` varchar(255) DEFAULT NULL,
  `auditable_id` bigint(20) UNSIGNED DEFAULT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `auditable_type`, `auditable_id`, `data`, `created_at`, `updated_at`) VALUES
(1, NULL, 'simulate_confirm', 'App\\Models\\StockMovement', 9, '{\"simulated\":true,\"quantity\":5}', '2025-11-24 06:50:45', '2025-11-24 06:50:45'),
(2, 3, 'confirm_in', 'App\\Models\\StockMovement', 14, '{\"product_id\":4,\"quantity\":5,\"notes\":null}', '2025-11-24 06:56:17', '2025-11-24 06:56:17'),
(3, 3, 'confirm_in', 'App\\Models\\StockMovement', 13, '{\"product_id\":4,\"quantity\":5,\"notes\":null}', '2025-11-24 06:56:22', '2025-11-24 06:56:22'),
(4, 3, 'confirm_out', 'App\\Models\\StockMovement', 15, '{\"product_id\":4,\"quantity\":5,\"notes\":null}', '2025-11-24 06:56:27', '2025-11-24 06:56:27'),
(5, 3, 'confirm_in', 'App\\Models\\StockMovement', 16, '{\"product_id\":5,\"quantity\":6,\"notes\":null}', '2025-11-27 03:57:19', '2025-11-27 03:57:19'),
(6, 3, 'confirm_out', 'App\\Models\\StockMovement', 17, '{\"product_id\":5,\"quantity\":3,\"notes\":null}', '2025-11-27 03:57:27', '2025-11-27 03:57:27'),
(7, 3, 'confirm_in', 'App\\Models\\StockMovement', 18, '{\"product_id\":4,\"quantity\":3,\"notes\":null}', '2025-11-28 01:55:20', '2025-11-28 01:55:20'),
(8, 3, 'confirm_in', 'App\\Models\\StockMovement', 8, '{\"product_id\":6,\"quantity\":7,\"notes\":\"Tambahan\"}', '2025-11-28 01:55:27', '2025-11-28 01:55:27'),
(9, 8, 'confirm_in', 'App\\Models\\StockMovement', 7, '{\"product_id\":5,\"quantity\":4,\"notes\":null}', '2025-12-01 21:57:23', '2025-12-01 21:57:23'),
(10, 8, 'confirm_in', 'App\\Models\\StockMovement', 6, '{\"product_id\":1,\"quantity\":5,\"notes\":null}', '2025-12-01 21:57:30', '2025-12-01 21:57:30'),
(11, 8, 'confirm_in', 'App\\Models\\StockMovement', 3, '{\"product_id\":1,\"quantity\":5,\"notes\":null}', '2025-12-01 21:57:40', '2025-12-01 21:57:40'),
(12, 8, 'confirm_out', 'App\\Models\\StockMovement', 20, '{\"product_id\":10,\"quantity\":7,\"notes\":null}', '2025-12-01 21:57:49', '2025-12-01 21:57:49'),
(13, 8, 'confirm_out', 'App\\Models\\StockMovement', 19, '{\"product_id\":5,\"quantity\":3,\"notes\":null}', '2025-12-01 21:57:54', '2025-12-01 21:57:54'),
(14, 8, 'confirm_out', 'App\\Models\\StockMovement', 4, '{\"product_id\":1,\"quantity\":5,\"notes\":null}', '2025-12-01 21:58:02', '2025-12-01 21:58:02'),
(15, 8, 'approve_in', 'App\\Models\\StockMovement', 21, '{\"product_id\":10,\"quantity\":7,\"notes\":\"Reorder dari supplier: \"}', '2025-12-01 22:17:29', '2025-12-01 22:17:29'),
(16, 8, 'approve_in', 'App\\Models\\StockMovement', 22, '{\"product_id\":10,\"quantity\":10,\"notes\":null}', '2025-12-01 22:18:33', '2025-12-01 22:18:33'),
(17, 8, 'approve_out', 'App\\Models\\StockMovement', 23, '{\"product_id\":10,\"quantity\":10,\"notes\":\"Dijual\"}', '2025-12-01 22:20:25', '2025-12-01 22:20:25'),
(18, 8, 'approve_out', 'App\\Models\\StockMovement', 24, '{\"product_id\":10,\"quantity\":7,\"notes\":null}', '2025-12-01 22:21:44', '2025-12-01 22:21:44'),
(19, 8, 'approve_in', 'App\\Models\\StockMovement', 46, '{\"product_id\":5,\"quantity\":20,\"notes\":\"Restock\"}', '2025-12-01 22:29:18', '2025-12-01 22:29:18'),
(20, 8, 'approve_in', 'App\\Models\\StockMovement', 48, '{\"product_id\":11,\"quantity\":5,\"notes\":null}', '2025-12-01 22:52:51', '2025-12-01 22:52:51');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'T-Shirt', 't-shirt', '2025-11-14 20:33:37', '2025-11-14 20:33:37'),
(3, 'Jaket', 'jaket', '2025-11-15 02:30:02', '2025-11-15 02:30:02'),
(4, 'Celana', 'celana', '2025-11-20 01:36:33', '2025-11-20 01:36:33'),
(5, 'Topi', 'topi', '2025-11-21 01:47:15', '2025-11-21 01:47:15');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_11_13_023435_create_categories_table', 1),
(6, '2025_11_13_023435_create_suppliers_table', 1),
(7, '2025_11_13_023436_create_products_table', 1),
(8, '2025_11_13_023436_create_stock_movements_table', 1),
(9, '2025_11_15_add_email_to_suppliers_table', 2),
(10, '2025_11_15_add_email_to_suppliers_table', 2),
(11, '2025_11_20_000000_add_sku_to_products_table', 3),
(12, '2025_11_21_000001_add_image_to_products_table', 4),
(13, '2025_11_24_120000_add_status_to_stock_movements_table', 4),
(14, '2025_11_24_121500_backfill_status_on_stock_movements', 5),
(15, '2025_11_24_122500_create_audit_logs_table', 6),
(16, '2025_11_24_130500_create_settings_table', 7),
(17, '2025_11_28_add_is_active_to_users', 8),
(18, '2025_11_29_000000_create_product_attributes_table', 9),
(19, '2025_11_29_000001_create_product_attribute_values_table', 9),
(20, '2025_11_29_000002_add_description_to_product_attributes_table', 10),
(21, '2025_12_02_050643_create_reorder_requests_table', 11);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
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
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `purchase_price` decimal(10,2) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `current_stock` int(11) NOT NULL DEFAULT 0,
  `min_stock` int(11) NOT NULL DEFAULT 10,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `supplier_id`, `name`, `sku`, `description`, `purchase_price`, `selling_price`, `current_stock`, `min_stock`, `image`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Kaos LebranJemes', NULL, 'Chuyyyyyyyyyyy', 70000.00, 150000.00, 17, 5, '1764617316_anxious-airbrush-mock.jpg', '2025-11-14 20:35:09', '2025-12-01 19:28:36'),
(4, 3, 2, 'Jeket Kulit', NULL, NULL, 99999.75, 200000.00, 28, 5, '1763775459_LogoHoodie-Front-Mock.jpg', '2025-11-15 02:30:53', '2025-11-28 01:48:08'),
(6, 5, 4, 'Topi Casual', NULL, NULL, 60000.00, 150000.00, 6, 5, '1763775474_CIZ-LOGO-HAT.jpg', '2025-11-21 01:47:58', '2025-11-24 02:08:46'),
(7, 1, 4, 'Kaos Putih', NULL, 'Kaossssssssssssssssssssss', 60000.00, 150000.00, 15, 10, '1764299925_NeckDeep-Tour-Tee-Back.png', '2025-11-28 03:18:45', '2025-11-28 03:19:00'),
(10, 1, 4, 'Kaos Long Slevee', NULL, NULL, 50000.00, 150000.00, 3, 5, '1764618806_BSMNT-FS.jpg', '2025-12-01 19:53:26', '2025-12-01 22:21:44'),
(11, 4, 4, 'Celana Jeans Premium', NULL, NULL, 100000.00, 250000.00, 15, 5, '1764629487_blue-jeans-pant-transparent-background-png.png', '2025-12-01 22:51:27', '2025-12-01 22:52:51');

-- --------------------------------------------------------

--
-- Table structure for table `product_attributes`
--

CREATE TABLE `product_attributes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('text','select','number') NOT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`options`)),
  `is_required` tinyint(1) NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_attributes`
--

INSERT INTO `product_attributes` (`id`, `category_id`, `name`, `description`, `type`, `options`, `is_required`, `order`, `created_at`, `updated_at`) VALUES
(1, 1, 'Ukuran', NULL, 'select', '[\"XS\",\"S\",\"M\",\"L\",\"XL\",\"XXL\"]', 0, 0, '2025-11-28 20:22:34', '2025-12-01 20:36:49'),
(3, 1, 'Warna', NULL, 'select', '[\"Hitam\",\"Putih\"]', 0, 0, '2025-12-01 20:35:51', '2025-12-01 20:35:51');

-- --------------------------------------------------------

--
-- Table structure for table `product_attribute_values`
--

CREATE TABLE `product_attribute_values` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `attribute_id` bigint(20) UNSIGNED NOT NULL,
  `value` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_attribute_values`
--

INSERT INTO `product_attribute_values` (`id`, `product_id`, `attribute_id`, `value`, `created_at`, `updated_at`) VALUES
(9, 10, 1, 'XL', '2025-12-01 20:36:21', '2025-12-01 20:36:21'),
(10, 10, 3, 'Hitam', '2025-12-01 20:36:21', '2025-12-01 20:36:21'),
(11, 1, 3, 'Hitam', '2025-12-01 20:37:06', '2025-12-01 20:37:06');

-- --------------------------------------------------------

--
-- Table structure for table `reorder_requests`
--

CREATE TABLE `reorder_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','approved','rejected','completed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reorder_requests`
--

INSERT INTO `reorder_requests` (`id`, `product_id`, `supplier_id`, `user_id`, `quantity`, `notes`, `status`, `created_at`, `updated_at`) VALUES
(1, 10, 4, 2, 7, NULL, 'pending', '2025-12-01 22:08:37', '2025-12-01 22:08:37'),
(2, 10, 4, 2, 7, NULL, 'pending', '2025-12-01 22:09:05', '2025-12-01 22:09:05'),
(3, 10, 4, 2, 7, NULL, 'pending', '2025-12-01 22:17:08', '2025-12-01 22:17:08');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'app_name', 'Stockify', '2025-11-24 07:55:32', '2025-11-24 07:55:32'),
(2, 'timezone', 'Asia/Jakarta', '2025-11-24 07:55:32', '2025-11-24 07:55:32'),
(3, 'default_min_stock', '5', '2025-11-24 07:55:32', '2025-11-24 07:55:32'),
(4, 'app_logo', NULL, '2025-11-24 07:55:33', '2025-11-27 02:10:00');

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('in','out') NOT NULL,
  `quantity` int(11) NOT NULL,
  `notes` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `product_id`, `user_id`, `type`, `quantity`, `notes`, `status`, `created_at`, `updated_at`) VALUES
(3, 1, 2, 'in', 5, NULL, 'confirmed', '2025-11-19 02:05:29', '2025-12-01 21:57:40'),
(4, 1, 2, 'out', 5, NULL, 'confirmed', '2025-11-20 01:57:38', '2025-12-01 21:58:02'),
(6, 1, 3, 'in', 5, NULL, 'confirmed', '2025-11-20 06:40:17', '2025-12-01 21:57:30'),
(8, 6, 3, 'in', 7, 'Tambahan', 'confirmed', '2025-11-21 01:48:46', '2025-11-28 01:55:27'),
(9, 6, 3, 'out', 5, 'Dijual', 'confirmed', '2025-11-21 01:49:02', '2025-11-24 06:50:45'),
(10, 4, 2, 'in', 5, NULL, 'confirmed', '2025-11-24 01:26:52', '2025-11-24 02:31:11'),
(11, 6, 3, 'in', 3, NULL, 'confirmed', '2025-11-24 01:34:11', '2025-11-24 02:08:24'),
(12, 6, 3, 'out', 3, NULL, 'confirmed', '2025-11-24 02:08:46', '2025-11-24 02:08:59'),
(13, 4, 3, 'in', 5, NULL, 'confirmed', '2025-11-24 06:55:10', '2025-11-24 06:56:22'),
(14, 4, 2, 'in', 5, NULL, 'confirmed', '2025-11-24 06:55:44', '2025-11-24 06:56:17'),
(15, 4, 2, 'out', 5, NULL, 'confirmed', '2025-11-24 06:56:06', '2025-11-24 06:56:27'),
(18, 4, 2, 'in', 3, NULL, 'confirmed', '2025-11-28 01:48:08', '2025-11-28 01:55:20'),
(20, 10, 2, 'out', 7, NULL, 'confirmed', '2025-12-01 20:32:14', '2025-12-01 21:57:49'),
(21, 10, 2, 'in', 7, 'Reorder dari supplier: ', 'approved', '2025-12-01 22:17:09', '2025-12-01 22:17:29'),
(22, 10, 2, 'in', 10, NULL, 'approved', '2025-12-01 22:18:11', '2025-12-01 22:18:33'),
(23, 10, 2, 'out', 10, 'Dijual', 'approved', '2025-12-01 22:20:10', '2025-12-01 22:20:25'),
(24, 10, 2, 'out', 7, NULL, 'approved', '2025-12-01 22:21:15', '2025-12-01 22:21:44'),
(48, 11, 2, 'in', 5, NULL, 'approved', '2025-12-01 22:52:19', '2025-12-01 22:52:51');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `contact_person`, `phone`, `email`, `address`, `created_at`, `updated_at`) VALUES
(2, 'JatiKur', NULL, '08123456789', 'jatikur@stockify.test', 'Boyolali', '2025-11-14 20:24:54', '2025-11-14 20:29:59'),
(4, 'Hugo', NULL, '0985034344', 'hugo@contack.com', 'Yogyakarta', '2025-11-21 01:43:01', '2025-11-21 01:43:01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `role` enum('admin','manager','staff') NOT NULL DEFAULT 'staff',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `role`, `is_active`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin Stockify', 'admin@stockify.test', NULL, 'admin', 1, '$2a$12$wtI4kTCb4xaSo5tGmJmJHursdeb8LL0zBUDERwI6nQJ5Utdo4CM4.', NULL, '2025-11-14 18:15:29', '2025-11-14 18:15:29'),
(2, 'Manajer Gudang', 'manager@stockify.test', NULL, 'manager', 1, '$2a$12$kr3xFrDGXJqw3UQLeL4HveY5AqSRveVH0jlx66xicImriQjc59Ae2', NULL, '2025-11-14 18:15:29', '2025-11-14 18:15:29'),
(3, 'Staff Gudang', 'staff@stockify.test', NULL, 'staff', 1, '$2a$12$jSq9e7jZaP536eWNAsEvmOnUR4K2Wk1Vsth79co44SYkgJCZUOsc.', NULL, '2025-11-14 18:15:29', '2025-11-14 18:15:29'),
(5, 'Budiono Siregar', 'budiono@stockify.test', NULL, 'staff', 1, '$2y$10$rsKY93V0Q4ttOMt/iNx6SOxsH3FkGaB/Egmy9NEcIyYDNEWqvulBS', NULL, '2025-11-28 03:29:36', '2025-11-28 03:29:36'),
(6, 'Ahmat Temola', 'ahmat@stockify.test', NULL, 'manager', 1, '$2y$10$gVawaYzMOJdIUrdYXqfyIu2WQd6LXTzR9Xb1uvbUWEFzkYhL2KNpy', NULL, '2025-11-28 03:30:12', '2025-11-28 03:30:12'),
(7, 'Hanif Kjrtan', 'hanif@stockify.test', NULL, 'admin', 1, '$2y$10$Lw0RzPNJbJSaShpuIIO07.XFj8ADfpf6HriLh/85qi3zGTV6S6kfi', NULL, '2025-11-28 03:31:00', '2025-11-28 15:46:08'),
(8, 'Yatno', 'yatno@stockify.test', NULL, 'staff', 1, '$2y$10$xVHIZexxqOe7avSl7CLk/eFvlSwLI2c6.e7VsT/GSlkRtJlQgUZNi', NULL, '2025-12-01 21:57:07', '2025-12-01 21:57:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_user_id_index` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_name_unique` (`name`),
  ADD UNIQUE KEY `products_sku_unique` (`sku`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_supplier_id_foreign` (`supplier_id`);

--
-- Indexes for table `product_attributes`
--
ALTER TABLE `product_attributes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_attributes_category_id_name_unique` (`category_id`,`name`);

--
-- Indexes for table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_attribute_values_product_id_attribute_id_unique` (`product_id`,`attribute_id`),
  ADD KEY `product_attribute_values_attribute_id_foreign` (`attribute_id`);

--
-- Indexes for table `reorder_requests`
--
ALTER TABLE `reorder_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reorder_requests_product_id_foreign` (`product_id`),
  ADD KEY `reorder_requests_supplier_id_foreign` (`supplier_id`),
  ADD KEY `reorder_requests_user_id_foreign` (`user_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_movements_product_id_foreign` (`product_id`),
  ADD KEY `stock_movements_user_id_foreign` (`user_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `product_attributes`
--
ALTER TABLE `product_attributes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `reorder_requests`
--
ALTER TABLE `reorder_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_attributes`
--
ALTER TABLE `product_attributes`
  ADD CONSTRAINT `product_attributes_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  ADD CONSTRAINT `product_attribute_values_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `product_attributes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_attribute_values_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reorder_requests`
--
ALTER TABLE `reorder_requests`
  ADD CONSTRAINT `reorder_requests_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reorder_requests_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reorder_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_movements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
