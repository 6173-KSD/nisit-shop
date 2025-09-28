-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 21, 2025 at 04:03 PM
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_code`, `user_name`, `user_phone`, `user_email`, `pickup_option`, `pickup_date`, `status`, `created_at`) VALUES
(1, 'D24123CF', 'KSD33', '005', 'asaasda@gmail.com', 'pickup', '2025-09-22', 'pending', '2025-09-21 08:02:24'),
(2, '50C3A523', 'KSD33', '005', 'asaasda@gmail.com', 'pickup', '2025-09-22', 'pending', '2025-09-21 08:17:10'),
(3, 'F15B1055', 'KSD33', '005', 'asaasda@gmail.com', 'pickup', '2025-09-22', 'pending', '2025-09-21 08:51:51'),
(4, 'D9C14A78', 'KSD33', '005', 'asaasda@gmail.com', 'pickup', '2025-09-22', 'pending', '2025-09-21 08:52:51'),
(5, '074FB081', 'KSD33', '005', 'asaasda@gmail.com', 'pickup', '2025-09-22', 'pending', '2025-09-21 09:00:33'),
(6, '8C69BBC7', 'KSD33', '005', '', 'pickup', '2025-09-22', 'pending', '2025-09-21 09:17:50'),
(7, 'DA12740A', 'เค้กช็อค', '005', '6...3@msu.ac.th', 'pickup', '2025-09-22', 'pending', '2025-09-21 10:05:47');

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
(1, 1, 2, 15, 450.00),
(2, 2, 2, 1, 450.00),
(7, 7, 8, 1, 200.00);

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
(1, 'UNI-SET-01', 'ชุดนิสิต (ครบชุด)', 'เสื้อ+กางเกง/กระโปรง ตามมาตรฐานมหาวิทยาลัย', 890.00, 30, NULL, NULL, 1, '2025-09-14 08:12:54', '2025-09-14 08:12:54'),
(2, 'PE-SET-01', 'ชุดพละ', 'ชุดพละมหาวิทยาลัย ไซส์มาตรฐาน', 450.00, 34, NULL, NULL, 1, '2025-09-14 08:12:54', '2025-09-21 08:17:10'),
(8, 'TS', 'เสื้อนิสิตหญิง', NULL, 200.00, 49, '2025-09-21', '1758449096_2.jpg', 1, '2025-09-21 10:04:56', '2025-09-21 10:05:47');

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
(1, 2, 'order', 15, 'order D24123CF', '2025-09-21 08:02:24'),
(2, 2, 'order', 1, 'order 50C3A523', '2025-09-21 08:17:10'),
(7, 8, 'order', 1, 'order DA12740A', '2025-09-21 10:05:47');

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
(4, 'zxc', 'zxc@zxc.com', '$2y$10$0B.AABMcOWs1d.TGRRZ5W.Ja2tsutaEGlMgTsyhq9xsjcN8OmAJMW', '2025-09-21 09:33:22');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
