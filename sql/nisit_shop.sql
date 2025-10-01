-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 30, 2025 at 10:39 AM
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
-- Database: `nisit_shop`
--
CREATE DATABASE IF NOT EXISTS `nisit_shop` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `nisit_shop`;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_code` varchar(12) NOT NULL,
  `user_name` varchar(150) NOT NULL,
  `user_phone` varchar(50) NOT NULL,
  `user_email` varchar(150) DEFAULT NULL,
  `pickup_option` enum('pickup','delivery') NOT NULL DEFAULT 'pickup',
  `pickup_date` date DEFAULT NULL,
  `status` enum('pending','confirmed','preparing','ready_for_pickup','picked_up','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_code`, `user_name`, `user_phone`, `user_email`, `pickup_option`, `pickup_date`, `status`, `created_at`, `total_amount`, `user_id`) VALUES
(1, 'D24123CF', 'KSD33', '005', 'asaasda@gmail.com', 'pickup', '2025-09-22', 'pending', '2025-09-21 08:02:24', 0.00, NULL),
(2, '50C3A523', 'KSD33', '005', 'asaasda@gmail.com', 'pickup', '2025-09-22', 'pending', '2025-09-21 08:17:10', 0.00, NULL),
(3, 'F15B1055', 'KSD33', '005', 'asaasda@gmail.com', 'pickup', '2025-09-22', 'pending', '2025-09-21 08:51:51', 0.00, NULL),
(4, 'D9C14A78', 'KSD33', '005', 'asaasda@gmail.com', 'pickup', '2025-09-22', 'pending', '2025-09-21 08:52:51', 0.00, NULL),
(5, '074FB081', 'KSD33', '005', 'asaasda@gmail.com', 'pickup', '2025-09-22', 'pending', '2025-09-21 09:00:33', 0.00, NULL),
(6, '8C69BBC7', 'KSD33', '005', '', 'pickup', '2025-09-22', 'pending', '2025-09-21 09:17:50', 0.00, NULL),
(7, 'DA12740A', 'เค้กช็อค', '005', '6...3@msu.ac.th', 'pickup', '2025-09-22', 'cancelled', '2025-09-21 10:05:47', 0.00, NULL),
(8, 'DE4A96F5', 'อัย', '031244865132', '5566@gmail.com', 'pickup', '2025-10-23', 'pending', '2025-09-30 07:57:34', 0.00, NULL),
(9, 'BA1B6542', 'อัย', '031244865132', '5566@gmail.com', 'pickup', '2025-10-01', 'pending', '2025-09-30 07:57:47', 0.00, NULL),
(10, '26F0193D', 'อัย', '031244865132', '5566@gmail.com', 'pickup', '2025-10-01', 'pending', '2025-09-30 07:58:21', 0.00, NULL),
(11, 'CB3AE733', 'อัย', '031244865132', '5566@gmail.com', 'pickup', '2025-10-01', 'pending', '2025-09-30 08:32:39', 35.00, 6),
(12, 'BF8D9098', 'อัย', '031244865132', '5566@gmail.com', 'pickup', '2025-10-01', 'pending', '2025-09-30 08:34:15', 7435.00, 6),
(13, 'DB151640', 'f', '031244865132', 'asd@gmail.com', 'pickup', '2025-10-23', 'pending', '2025-09-30 08:35:26', 50.00, 5),
(14, '9FBD75DA', 'f', '031244865132', 'asd@gmail.com', 'pickup', '2025-10-01', 'pending', '2025-09-30 08:37:04', 29.00, 5);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `qty`, `unit_price`) VALUES
(8, 8, 13, 1, 99.00),
(9, 9, 26, 1, 50.00),
(10, 10, 26, 1, 50.00),
(11, 11, 25, 1, 35.00),
(12, 12, 26, 1, 50.00),
(13, 12, 25, 211, 35.00),
(14, 13, 26, 1, 50.00),
(15, 14, 24, 1, 29.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `sku` varchar(50) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock` int(11) NOT NULL DEFAULT 0,
  `expected_restock_date` date DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `sku`, `name`, `description`, `price`, `stock`, `expected_restock_date`, `image`, `is_active`, `created_at`, `updated_at`) VALUES
(10, 'SH-1', 'เสื้อเฟรชชี่ ปี2568', NULL, 250.00, 50, NULL, '1759211947_เสื้อเฟรชชี่.PNG', 1, '2025-09-30 05:59:07', '2025-09-30 05:59:07'),
(11, 'SH-2', 'เสื้อพละ', NULL, 250.00, 125, NULL, '1759212047_เสื้อพละ.PNG', 1, '2025-09-30 06:00:47', '2025-09-30 06:00:47'),
(12, 'SH-3', 'เสื้อนิสิตชาย', NULL, 120.00, 162, NULL, '1759212082_เสื้อนิสิตชาย.jpg', 1, '2025-09-30 06:01:22', '2025-09-30 06:01:22'),
(13, 'SH-4', 'เสื้อนิสิตหญิง', NULL, 99.00, 177, NULL, '1759212197_เสื้อนิสิตหญิง.jpg', 1, '2025-09-30 06:03:17', '2025-09-30 07:57:34'),
(14, 'PL-1', 'กางเกงพละ', NULL, 200.00, 99, NULL, '1759212281_กางเกงพละ.PNG', 1, '2025-09-30 06:04:41', '2025-09-30 06:04:41'),
(15, 'PL-2', 'กางเกงนิสิตชาย', NULL, 100.00, 212, NULL, '1759212311_กางเกงนิสิตชาย.jpg', 1, '2025-09-30 06:05:11', '2025-09-30 06:05:11'),
(16, 'PL-3', 'กระโปรงนิสิตหญิง', NULL, 99.00, 159, NULL, '1759212336_กระโปรงนิสิตหญิง.jpg', 1, '2025-09-30 06:05:36', '2025-09-30 06:05:36'),
(17, 'PL-4', 'กระโปรงพิธีการหญิง', NULL, 150.00, 99, NULL, '1759212362_กระโปรงพิธีการหญิง.jpg', 1, '2025-09-30 06:06:02', '2025-09-30 06:06:02'),
(18, 'PL-5', 'กางเกงพิธีการชาย', NULL, 150.00, 99, NULL, '1759212382_กางเกงพิธีการชาย.png', 1, '2025-09-30 06:06:22', '2025-09-30 06:06:22'),
(19, 'SO-1', 'รองเท้าคัทชูหญิง', NULL, 100.00, 66, NULL, '1759212419_รองเท้าคัทชูหญิง.jpg', 1, '2025-09-30 06:06:59', '2025-09-30 07:31:26'),
(20, 'SO-2', 'รองเท้าคัทชูชาย', NULL, 100.00, 59, NULL, '1759212438_รองเท้าคัทชูชาย.jpg', 1, '2025-09-30 06:07:18', '2025-09-30 07:31:21'),
(21, 'OT-1', 'หัวเข็มขัดหญิง', NULL, 40.00, 543, NULL, '1759212470_หัวเข็มขัดหญิง.jpeg', 1, '2025-09-30 06:07:50', '2025-09-30 06:08:20'),
(22, 'OT-2', 'หัวเข็มขัดชาย', NULL, 40.00, 89, NULL, '1759212492_หัวเข็มขัดชาย.jpeg', 1, '2025-09-30 06:08:12', '2025-09-30 06:08:12'),
(23, 'KM-1', 'สายเข็มขัด', NULL, 59.00, 200, NULL, '1759212529_สายเข็มขัด.jpeg', 1, '2025-09-30 06:08:49', '2025-09-30 06:08:49'),
(24, 'OT-3', 'กระดุมเสื้อนิสิตหญิง', NULL, 29.00, 98, NULL, '1759212556_กระดุมเสื้อนิสิตหญิง.jpeg', 1, '2025-09-30 06:09:16', '2025-09-30 08:37:04'),
(25, 'OT-4', 'ตุ้งติ้ง', NULL, 35.00, 0, NULL, '1759212580_ตุ้งติ้ง.jpeg', 1, '2025-09-30 06:09:40', '2025-09-30 08:34:15'),
(26, 'OT-5', 'เน็คไทด์', NULL, 50.00, 32, NULL, '1759212606_เน็คไทด์.jpeg', 1, '2025-09-30 06:10:06', '2025-09-30 08:35:26'),
(27, 'OT-6', 'เข็มกลัดอก', NULL, 25.00, 78, NULL, '1759212623_เข็มกลัดอก.jpeg', 1, '2025-09-30 06:10:23', '2025-09-30 06:10:23');

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `type` enum('import','adjustment','order','refund') NOT NULL,
  `qty` int(11) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `product_id`, `type`, `qty`, `note`, `created_at`) VALUES
(8, 13, 'order', 1, 'order DE4A96F5', '2025-09-30 07:57:34'),
(9, 26, 'order', 1, 'order BA1B6542', '2025-09-30 07:57:47'),
(10, 26, 'order', 1, 'order 26F0193D', '2025-09-30 07:58:21'),
(11, 25, 'order', 1, 'order CB3AE733', '2025-09-30 08:32:39'),
(12, 26, 'order', 1, 'order BF8D9098', '2025-09-30 08:34:15'),
(13, 25, 'order', 211, 'order BF8D9098', '2025-09-30 08:34:15'),
(14, 26, 'order', 1, 'order DB151640', '2025-09-30 08:35:26'),
(15, 24, 'order', 1, 'order 9FBD75DA', '2025-09-30 08:37:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'เค้กช็อค', '6...3@msu.ac.th', '$2y$10$ZQoOijQyp/kYg45DnMHnNuQUy1cSOI4wUim35JtWF6EYTRF9.iRKO', '2025-09-21 08:16:37'),
(2, 'kkk', 'kkk@kkk.com', '$2y$10$/.ll1PiwF.oAq2SA4Sd05.ughd0x/5orYgrLU8Xk3sbBnE9bii/eG', '2025-09-21 09:23:46'),
(3, 'kkkasd', 'kkkasd@kkkasd.com', '$2y$10$CGhkgeZj0S0XwjO6Ved7R.mf5fhJUe1qh1UCBlfCsMxiAqkcNbWQ6', '2025-09-21 09:30:00'),
(4, 'zxc', 'zxc@zxc.com', '$2y$10$0B.AABMcOWs1d.TGRRZ5W.Ja2tsutaEGlMgTsyhq9xsjcN8OmAJMW', '2025-09-21 09:33:22'),
(5, 'f', 'asd@gmail.com', '$2y$10$wYx/Zge2gJltw0PxOrGgHOOWvOxao3tDEcmNvv/BGD/xBURhYU/im', '2025-09-30 05:48:52'),
(6, 'อัย', '5566@gmail.com', '$2y$10$vqBG71dokZCKDAxJ3NBBlu18jfTjy/GDDbwYtnxipTgWMNxlUSfZ2', '2025-09-30 07:36:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_code` (`order_code`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `order_items_ibfk_2` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_movements_ibfk_1` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
