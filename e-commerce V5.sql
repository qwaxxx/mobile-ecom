-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 19, 2025 at 07:24 AM
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
-- Database: `e-commerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `addcarts`
--

CREATE TABLE `addcarts` (
  `addcart_id` int(11) NOT NULL,
  `addcart_batch_id` int(10) NOT NULL,
  `addcart_user_id` int(10) NOT NULL,
  `addcart_seller_id` int(10) NOT NULL,
  `addcart_prod_id` int(10) NOT NULL,
  `addcart_pcs` int(10) NOT NULL,
  `addcart_price` int(10) NOT NULL,
  `addcart_status` varchar(255) NOT NULL,
  `addcart_date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `addcarts`
--

INSERT INTO `addcarts` (`addcart_id`, `addcart_batch_id`, `addcart_user_id`, `addcart_seller_id`, `addcart_prod_id`, `addcart_pcs`, `addcart_price`, `addcart_status`, `addcart_date`) VALUES
(18, 150621, 15, 0, 11, 2, 30000, '', '2025-04-18 15:18:04'),
(19, 150621, 15, 0, 12, 2, 20999, '', '2025-04-18 15:18:04'),
(20, 154992, 15, 0, 12, 6, 20999, '', '2025-04-18 15:18:04'),
(21, 153646, 15, 0, 11, 1, 30000, '', '2025-04-18 15:18:04'),
(22, 157919, 15, 0, 12, 3, 20999, '', '2025-04-18 15:18:04'),
(23, 157232, 15, 0, 11, 1, 30000, '', '2025-04-18 15:18:04'),
(24, 156174, 15, 0, 11, 1, 30000, '', '2025-04-18 15:18:04'),
(25, 157544, 15, 0, 12, 1, 20999, '', '2025-04-18 15:18:04'),
(26, 159938, 15, 0, 11, 1, 30000, '', '2025-04-18 15:18:04'),
(27, 155121, 15, 0, 12, 3, 20999, '', '2025-04-18 15:18:04'),
(28, 156392, 15, 0, 11, 3, 30000, '', '2025-04-18 15:18:04'),
(29, 150499, 15, 0, 11, 2, 30000, '', '2025-04-18 15:18:04'),
(30, 151366, 15, 0, 11, 1, 30000, '', '2025-04-18 15:18:04'),
(31, 158178, 15, 0, 12, 7, 20999, '', '2025-04-18 15:18:04'),
(32, 156883, 15, 8, 12, 1, 20999, '', '2025-04-18 15:18:04'),
(33, 72203, 7, 0, 11, 3, 30000, '', '2025-04-18 15:18:04'),
(34, 72203, 7, 0, 12, 2, 20999, '', '2025-04-18 15:18:04'),
(35, 78597, 7, 8, 12, 6, 20999, '', '2025-04-18 15:18:04'),
(36, 70836, 7, 9, 11, 1, 30000, '', '2025-04-18 15:18:04'),
(37, 70836, 7, 8, 12, 1, 20999, '', '2025-04-18 15:18:04'),
(38, 74558, 7, 9, 11, 1, 30000, 'pending', '2025-04-18 15:18:04'),
(39, 74558, 7, 8, 12, 1, 20999, 'accepted', '2025-04-19 05:06:06'),
(40, 76501, 7, 8, 12, 1, 20999, 'rejected', '2025-04-19 05:15:44'),
(41, 76501, 7, 9, 11, 1, 30000, 'pending', '2025-04-18 15:18:04'),
(42, 71676, 7, 8, 12, 2, 20999, 'rejected', '2025-04-19 05:15:56'),
(43, 71676, 7, 9, 11, 1, 30000, 'pending', '2025-04-18 15:18:04'),
(44, 77081, 7, 8, 12, 3, 20999, 'rejected', '2025-04-19 12:06:09'),
(45, 75721, 7, 8, 12, 3, 20999, 'pending', '2025-04-19 06:10:33'),
(46, 155949, 15, 9, 11, 3, 30000, 'pending', '2025-04-19 08:31:30'),
(47, 155949, 15, 8, 12, 3, 20999, 'pending', '2025-04-19 08:31:31'),
(48, 77313, 7, 8, 12, 2, 20999, 'accepted', '2025-04-19 12:40:12'),
(49, 77313, 7, 9, 11, 2, 30000, 'pending', '2025-04-19 12:15:33');

-- --------------------------------------------------------

--
-- Table structure for table `billing_orders`
--

CREATE TABLE `billing_orders` (
  `billing_order_id` int(11) NOT NULL,
  `billing_user_id` int(10) NOT NULL,
  `billing_temp_id` int(10) NOT NULL,
  `billing_fname` varchar(255) NOT NULL,
  `billing_lname` varchar(255) NOT NULL,
  `billing_email` varchar(255) NOT NULL,
  `billing_street_village_purok` varchar(255) NOT NULL,
  `billing_baranggay` varchar(255) NOT NULL,
  `billing_city` varchar(255) NOT NULL,
  `billing_province` varchar(255) NOT NULL,
  `billing_country` varchar(255) NOT NULL,
  `billing_postal` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `billing_orders`
--

INSERT INTO `billing_orders` (`billing_order_id`, `billing_user_id`, `billing_temp_id`, `billing_fname`, `billing_lname`, `billing_email`, `billing_street_village_purok`, `billing_baranggay`, `billing_city`, `billing_province`, `billing_country`, `billing_postal`) VALUES
(9, 15, 0, 'kill', 'last', 'eraoflorenciaforsale@gmail.com', 'purok-7', 'punta', 'pana-on', 'Misamis Occidental', 'Philippines', 7205),
(10, 7, 0, 'killer', 'kilua', 'eraoflorenciaforsale1@gmail.com', 'purok-7', 'punta', 'pana-on', 'Misamis Occidental', 'Philippines', 7205);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `addcart_id` int(10) NOT NULL,
  `message` text NOT NULL,
  `type` enum('purchase','response') NOT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `sender_id`, `addcart_id`, `message`, `type`, `status`, `created_at`) VALUES
(1, 8, 7, 44, 'Someone purchased your product: Yoga 7 / 3 pcs / Price : 20999', 'purchase', 'read', '2025-04-18 22:09:07'),
(2, 8, 7, 45, 'Someone purchased your product: Yoga 7 / 3 pcs / Price : 20999', 'purchase', 'read', '2025-04-18 22:10:33'),
(3, 9, 15, 46, 'Someone purchased your product: IdeaPad / 3 pcs / Price : 30000', 'purchase', 'read', '2025-04-19 00:31:31'),
(4, 8, 15, 47, 'Someone purchased your product: Yoga 7 / 3 pcs / Price : 20999', 'purchase', 'read', '2025-04-19 00:31:31'),
(5, 8, 7, 48, 'Someone purchased your product: Yoga 7 / 2 pcs / Price : 20999', 'purchase', 'read', '2025-04-19 04:15:33'),
(6, 9, 7, 49, 'Someone purchased your product: IdeaPad / 2 pcs / Price : 30000', 'purchase', 'unread', '2025-04-19 04:15:33'),
(7, 7, 8, 48, 'Your order #48 has been accepted.', 'response', 'read', '2025-04-19 04:40:11');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_user_id` int(10) NOT NULL,
  `order_billing_id` int(10) NOT NULL,
  `order_batch_id` int(10) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_user_id`, `order_billing_id`, `order_batch_id`, `order_date`) VALUES
(11, 15, 9, 150621, '2025-04-18 10:57:16'),
(12, 15, 9, 156656, '2025-04-18 10:57:16'),
(13, 15, 9, 154992, '2025-04-18 11:28:07'),
(14, 15, 9, 153646, '2025-04-18 12:31:16'),
(15, 15, 9, 157919, '2025-04-18 12:31:25'),
(16, 15, 9, 157232, '2025-04-18 12:31:34'),
(17, 15, 9, 156174, '2025-04-18 12:31:55'),
(18, 15, 9, 157544, '2025-04-18 12:32:04'),
(19, 15, 9, 159938, '2025-04-18 12:32:13'),
(20, 15, 9, 155121, '2025-04-18 12:32:22'),
(21, 15, 9, 156392, '2025-04-18 12:32:30'),
(22, 15, 9, 150499, '2025-04-18 12:32:40'),
(23, 15, 9, 151366, '2025-04-18 12:32:47'),
(24, 15, 9, 158178, '2025-04-18 12:33:03'),
(25, 15, 9, 156883, '2025-04-18 13:14:33'),
(26, 7, 10, 72203, '2025-04-18 13:31:18'),
(27, 7, 10, 78597, '2025-04-18 13:33:26'),
(28, 7, 10, 70836, '2025-04-18 13:56:23');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `prod_id` int(11) NOT NULL,
  `prod_name` varchar(255) NOT NULL,
  `prod_description` varchar(255) NOT NULL,
  `prod_stock` int(10) NOT NULL,
  `prod_price` int(10) NOT NULL,
  `prod_picture` varchar(255) NOT NULL,
  `prod_user_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`prod_id`, `prod_name`, `prod_description`, `prod_stock`, `prod_price`, `prod_picture`, `prod_user_id`) VALUES
(11, 'IdeaPad', 'Slim 1 i15 Gen 7', 120, 30000, 'uploads/IdeaPadSlim1i15Gen 7.png', 9),
(12, 'Yoga 7', ' 2-in-1 14\'Gen 9-2', 47, 20999, 'uploads/Yoga 7 2-in-1 14\'Gen 9-2.png', 8);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `user_type` enum('admin','seller','customer') NOT NULL,
  `create_on` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `profile_image` longblob DEFAULT NULL,
  `contact` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `user_type`, `create_on`, `profile_image`, `contact`) VALUES
(7, 'rayrt', 'raymart.poras@gmail.com', '$2y$10$UxaNquZEqk/sboAJ.9/v/.XTLJTXcY0MH6KBHhmFlZlM/Txkmh9w6', 'customer', '2025-04-18 15:44:27', 0x50726f66696c652e6a7067, ''),
(8, 'seller', 'seller@gmail.com', '$2y$10$UDaS4VSbpy/6agSDMJ6NKOagGCRjQVNWvmeZm41vT4FC/gkmqyB8.', 'seller', '2025-04-18 03:03:00', NULL, ''),
(9, 'seller 2', 'seller2@gmail.com', '$2y$10$3TGAHAHB9KOkRa4YnFbuWOlWQFaDKv5FBP8d6p/KJZkyjNvZx7RXu', 'seller', '2025-04-18 03:27:50', NULL, ''),
(15, 'killie', 'eraoflorenciaforsale@gmail.com', '$2y$10$ZPq1B0wOrl.M6ZuLYSvSEeh4dECtXPX/XnbDLfbmbM4R6YHbeseF2', 'customer', '2025-04-18 07:17:27', NULL, ''),
(16, 'admin', 'admin@gmail.com', '$2y$10$eBSfITemxLNxQVPoiNxmReSIA7d9.2fiKeqId/xJS.ofXUrb52IsO', 'admin', '2025-04-18 15:39:22', NULL, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addcarts`
--
ALTER TABLE `addcarts`
  ADD PRIMARY KEY (`addcart_id`);

--
-- Indexes for table `billing_orders`
--
ALTER TABLE `billing_orders`
  ADD PRIMARY KEY (`billing_order_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`prod_id`);

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
-- AUTO_INCREMENT for table `addcarts`
--
ALTER TABLE `addcarts`
  MODIFY `addcart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `billing_orders`
--
ALTER TABLE `billing_orders`
  MODIFY `billing_order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `prod_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
