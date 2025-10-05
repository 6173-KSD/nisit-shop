-- phpMyAdmin SQL Dump
-- version 5.0.4deb2+deb11u2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 05, 2025 at 08:50 PM
-- Server version: 10.5.29-MariaDB-0+deb11u1
-- PHP Version: 7.4.33

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
  `payment_method` varchar(50) DEFAULT NULL,
  `status` enum('pending','confirmed','preparing','ready_for_pickup','picked_up','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_code`, `user_name`, `user_phone`, `user_email`, `pickup_option`, `pickup_date`, `payment_method`, `status`, `created_at`) VALUES
(1, 'D24123CF', 'KSD33', '005', 'asaasda@gmail.com', 'pickup', '2025-09-22', NULL, 'pending', '2025-09-21 08:02:24'),
(2, '50C3A523', 'KSD33', '005', 'asaasda@gmail.com', 'pickup', '2025-09-22', NULL, 'pending', '2025-09-21 08:17:10'),
(3, 'F15B1055', 'KSD33', '005', 'asaasda@gmail.com', 'pickup', '2025-09-22', NULL, 'pending', '2025-09-21 08:51:51'),
(4, 'D9C14A78', 'KSD33', '005', 'asaasda@gmail.com', 'pickup', '2025-09-22', NULL, 'pending', '2025-09-21 08:52:51'),
(5, '074FB081', 'KSD33', '005', 'asaasda@gmail.com', 'pickup', '2025-09-22', NULL, 'pending', '2025-09-21 09:00:33'),
(6, '8C69BBC7', 'KSD33', '005', '', 'pickup', '2025-09-22', NULL, 'pending', '2025-09-21 09:17:50'),
(7, 'DA12740A', 'เค้กช็อค', '005', '6...3@msu.ac.th', 'pickup', '2025-09-22', NULL, 'cancelled', '2025-09-21 10:05:47'),
(8, 'D8E2B1A5', 'tummai', '0971271271', 'y56y56y@gmail', 'pickup', '2025-09-30', NULL, 'pending', '2025-09-29 11:01:54'),
(9, '46443AA1', 'เค้กช็อค', '005', '6...3@msu.ac.th', 'pickup', '2025-10-06', NULL, 'pending', '2025-10-05 09:16:35'),
(10, 'FEEAB9BD', 'เค้กช็อค', '005', '6...3@msu.ac.th', 'pickup', '2025-10-06', NULL, 'pending', '2025-10-05 09:17:03'),
(11, '0A503B36', 'เค้กช็อค', '005', '6...3@msu.ac.th', 'pickup', '2025-10-06', NULL, 'pending', '2025-10-05 09:17:32'),
(12, '08451C3A', 'เค้กช็อค', '005', '6...3@msu.ac.th', 'pickup', '2025-10-06', NULL, 'pending', '2025-10-05 09:20:28'),
(13, 'DDCDDBB7', 'เค้กช็อค', '005', '6...3@msu.ac.th', 'pickup', '2025-10-06', NULL, 'pending', '2025-10-05 09:22:37'),
(14, 'DB9F961C', 'เค้กช็อค', '005', '6...3@msu.ac.th', 'pickup', '2025-10-06', NULL, 'pending', '2025-10-05 09:24:09'),
(15, '33D8890C', 'เค้กช็อค', '005', '6...3@msu.ac.th', 'pickup', '2025-10-06', NULL, 'pending', '2025-10-05 09:27:37'),
(16, 'B8F81C59', 'เค้กช็อค', '005', '6...3@msu.ac.th', 'pickup', '2025-10-06', NULL, 'pending', '2025-10-05 09:29:58'),
(17, 'D95FACAF', 'เค้กช็อค', '005', '6...3@msu.ac.th', 'pickup', '2025-10-06', NULL, 'pending', '2025-10-05 09:31:22'),
(18, '22701EA1', 'เค้กช็อค', '005', '6...3@msu.ac.th', 'pickup', '2025-10-06', NULL, 'pending', '2025-10-05 09:33:40'),
(19, 'C0B3C1A5', 'เค้กช็อค', '005', '6...3@msu.ac.th', 'pickup', '2025-10-06', NULL, 'pending', '2025-10-05 09:35:13'),
(21, 'F3985180', 'เค้กช็อค', '005', '6...3@msu.ac.th', 'pickup', '2025-10-06', NULL, 'pending', '2025-10-05 11:21:18'),
(22, 'ACFE3FE5', 'เค้กช็อค', '005', '6...3@msu.ac.th', 'pickup', '2025-10-06', NULL, 'pending', '2025-10-05 11:23:20'),
(23, '36528813', 'เค้กช็อค', '005', '6...3@msu.ac.th', 'pickup', '2025-10-06', NULL, 'pending', '2025-10-05 11:26:50'),
(24, 'D67706AF', 'เค้กช็อค', '005', '6...3@msu.ac.th', 'pickup', '2025-10-06', NULL, 'pending', '2025-10-05 11:35:38'),
(25, '9E74A840', 'เค้กช็อค', '005', '6...3@msu.ac.th', 'pickup', '2025-10-06', NULL, 'pending', '2025-10-05 11:37:44'),
(26, '9EDBA9FB', 'เค้กช็อค', '005', '6...3@msu.ac.th', 'pickup', '2025-10-06', 'cash', 'pending', '2025-10-05 11:54:45'),
(27, 'CAA1C74A', 'เค้กช็อค', '005', '6...3@msu.ac.th', 'pickup', '2025-10-06', 'cash', 'pending', '2025-10-05 13:12:16'),
(28, '7C4DAABB', 'เค้กช็อค', '005', '6...3@msu.ac.th', 'pickup', '2025-10-06', 'cash', 'pending', '2025-10-05 13:15:21'),
(29, 'EAA880D4', 'เค้กช็อค', '005', '6...3@msu.ac.th', 'pickup', '2025-10-06', 'cash', 'pending', '2025-10-05 13:28:15'),
(30, 'C845789A', 'เค้กช็อค', '005', '6...3@msu.ac.th', 'pickup', '2025-10-06', 'cash', 'pending', '2025-10-05 13:39:38');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size` varchar(20) DEFAULT NULL,
  `qty` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `size`, `qty`, `unit_price`) VALUES
(9, 9, 44, NULL, 1, '50.00'),
(10, 9, 42, NULL, 1, '29.00'),
(11, 9, 35, NULL, 1, '150.00'),
(12, 10, 44, NULL, 1, '50.00'),
(13, 11, 41, NULL, 1, '59.00'),
(14, 12, 44, NULL, 1, '50.00'),
(15, 13, 42, NULL, 1, '29.00'),
(16, 14, 45, NULL, 1, '25.00'),
(17, 15, 45, NULL, 1, '25.00'),
(18, 16, 45, NULL, 1, '25.00'),
(19, 17, 44, NULL, 1, '50.00'),
(20, 18, 45, NULL, 1, '25.00'),
(21, 19, 45, NULL, 1, '25.00'),
(24, 24, 45, NULL, 1, '25.00'),
(25, 24, 44, NULL, 1, '50.00'),
(26, 25, 38, NULL, 1, '100.00'),
(27, 26, 45, NULL, 1, '25.00'),
(28, 27, 33, NULL, 1, '100.00'),
(29, 28, 45, NULL, 1, '25.00'),
(30, 28, 31, NULL, 1, '99.00'),
(31, 29, 39, NULL, 1, '40.00'),
(32, 29, 31, NULL, 1, '99.00'),
(33, 30, 31, 'L', 1, '99.00'),
(34, 30, 35, '28', 1, '150.00'),
(35, 30, 28, 'XXL', 1, '250.00');

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
(28, 'SH-1', 'เสื้อเฟรชชี่ ปี2568', NULL, '250.00', 49, NULL, 'เสื้อเฟรชชี่.PNG', 1, '2025-10-01 13:08:07', '2025-10-05 13:39:38'),
(29, 'SH-2', 'เสื้อพละ', NULL, '250.00', 125, NULL, 'เสื้อพละ.PNG', 1, '2025-10-01 13:08:07', '2025-10-01 13:08:07'),
(30, 'SH-3', 'เสื้อนิสิตชาย', NULL, '120.00', 162, NULL, 'เสื้อนิสิตชาย.jpg', 1, '2025-10-01 13:08:07', '2025-10-01 13:08:07'),
(31, 'SH-4', 'เสื้อนิสิตหญิง', NULL, '99.00', 174, NULL, 'เสื้อนิสิตหญิง.jpg', 1, '2025-10-01 13:08:07', '2025-10-05 13:39:38'),
(32, 'PL-1', 'กางเกงพละ', NULL, '200.00', 99, NULL, 'กางเกงพละ.PNG', 1, '2025-10-01 13:08:07', '2025-10-01 13:08:07'),
(33, 'PL-2', 'กางเกงนิสิตชาย', NULL, '100.00', 211, NULL, 'กางเกงนิสิตชาย.jpg', 1, '2025-10-01 13:08:07', '2025-10-05 13:12:16'),
(34, 'PL-3', 'กระโปรงนิสิตหญิง', NULL, '99.00', 159, NULL, 'กระโปรงนิสิตหญิง.jpg', 1, '2025-10-01 13:08:07', '2025-10-01 13:08:07'),
(35, 'PL-4', 'กระโปรงพิธีการหญิง', NULL, '150.00', 97, NULL, 'กระโปรงพิธีการหญิง.jpg', 1, '2025-10-01 13:08:07', '2025-10-05 13:39:38'),
(36, 'PL-5', 'กางเกงพิธีการชาย', NULL, '150.00', 99, NULL, 'กางเกงพิธีการชาย.png', 1, '2025-10-01 13:08:07', '2025-10-01 13:08:07'),
(37, 'SO-1', 'รองเท้าคัทชูหญิง', NULL, '100.00', 66, NULL, 'รองเท้าคัทชูหญิง.jpg', 1, '2025-10-01 13:08:07', '2025-10-01 13:08:07'),
(38, 'SO-2', 'รองเท้าคัทชูชาย', NULL, '100.00', 58, NULL, 'รองเท้าคัทชูชาย.jpg', 1, '2025-10-01 13:08:07', '2025-10-05 11:37:44'),
(39, 'OT-1', 'หัวเข็มขัดหญิง', NULL, '40.00', 542, NULL, 'หัวเข็มขัดหญิง.jpeg', 1, '2025-10-01 13:08:07', '2025-10-05 13:28:15'),
(40, 'OT-2', 'หัวเข็มขัดชาย', NULL, '40.00', 89, NULL, 'หัวเข็มขัดชาย.jpeg', 1, '2025-10-01 13:08:07', '2025-10-01 13:08:07'),
(41, 'KM-1', 'สายเข็มขัด', NULL, '59.00', 199, NULL, 'สายเข็มขัด.jpeg', 1, '2025-10-01 13:08:07', '2025-10-05 09:17:32'),
(42, 'OT-3', 'กระดุมเสื้อนิสิตหญิง', NULL, '29.00', 96, NULL, 'กระดุมเสื้อนิสิตหญิง.jpeg', 1, '2025-10-01 13:08:07', '2025-10-05 09:22:37'),
(43, 'OT-4', 'ตุ้งติ้ง', NULL, '35.00', 0, NULL, 'ตุ้งติ้ง.jpeg', 1, '2025-10-01 13:08:07', '2025-10-01 13:08:07'),
(44, 'OT-5', 'เน็คไทด์', NULL, '50.00', 27, NULL, 'เน็คไทด์.jpeg', 1, '2025-10-01 13:08:07', '2025-10-05 11:35:38'),
(45, 'OT-6', 'เข็มกลัดอก', NULL, '25.00', 70, NULL, 'เข็มกลัดอก.jpeg', 1, '2025-10-01 13:08:07', '2025-10-05 13:15:21');

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
(9, 44, 'order', 1, 'order 46443AA1', '2025-10-05 09:16:35'),
(10, 42, 'order', 1, 'order 46443AA1', '2025-10-05 09:16:35'),
(11, 35, 'order', 1, 'order 46443AA1', '2025-10-05 09:16:35'),
(12, 44, 'order', 1, 'order FEEAB9BD', '2025-10-05 09:17:03'),
(13, 41, 'order', 1, 'order 0A503B36', '2025-10-05 09:17:32'),
(14, 44, 'order', 1, 'order 08451C3A', '2025-10-05 09:20:28'),
(15, 42, 'order', 1, 'order DDCDDBB7', '2025-10-05 09:22:37'),
(16, 45, 'order', 1, 'order DB9F961C', '2025-10-05 09:24:09'),
(17, 45, 'order', 1, 'order 33D8890C', '2025-10-05 09:27:37'),
(18, 45, 'order', 1, 'order B8F81C59', '2025-10-05 09:29:58'),
(19, 44, 'order', 1, 'order D95FACAF', '2025-10-05 09:31:22'),
(20, 45, 'order', 1, 'order 22701EA1', '2025-10-05 09:33:40'),
(21, 45, 'order', 1, 'order C0B3C1A5', '2025-10-05 09:35:13'),
(22, 45, 'order', 1, 'order D67706AF', '2025-10-05 11:35:38'),
(23, 44, 'order', 1, 'order D67706AF', '2025-10-05 11:35:38'),
(24, 38, 'order', 1, 'order 9E74A840', '2025-10-05 11:37:44'),
(25, 45, 'order', 1, 'order 9EDBA9FB', '2025-10-05 11:54:45'),
(26, 33, 'order', 1, 'order CAA1C74A', '2025-10-05 13:12:16'),
(27, 45, 'order', 1, 'order 7C4DAABB', '2025-10-05 13:15:21'),
(28, 31, 'order', 1, 'order 7C4DAABB', '2025-10-05 13:15:21'),
(29, 39, 'order', 1, 'order EAA880D4', '2025-10-05 13:28:15'),
(30, 31, 'order', 1, 'order EAA880D4', '2025-10-05 13:28:15'),
(31, 31, 'order', 1, 'order C845789A', '2025-10-05 13:39:38'),
(32, 35, 'order', 1, 'order C845789A', '2025-10-05 13:39:38'),
(33, 28, 'order', 1, 'order C845789A', '2025-10-05 13:39:38');

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
(5, 'tummai', 'y56y56y@gmail', '$2y$10$4a0m8CNyH9SLNgD/RHGv5uED7UPEsXUuRCKNoOWxKrkY4M85AJpBi', '2025-09-29 10:59:35');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
