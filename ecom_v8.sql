-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2025 at 10:04 AM
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
(56, 70721, 7, 8, 15, 4, 30000, 'accepted', '2025-04-24 21:07:03'),
(57, 70721, 7, 9, 16, 4, 9000000, 'pending', '2025-04-24 20:07:22');

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
(10, 7, 0, 'ray', 'poras', 'raymart.poras@gmail.com', 'purok-7', 'punta', 'pana-on', 'Misamis Occidental', 'Philippines', 7205);

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
(20, 8, 7, 56, 'Someone purchased your product: golden / 4 pcs / Price : 30000', 'purchase', 'read', '2025-04-24 12:07:22'),
(21, 9, 7, 57, 'Someone purchased your product: Yoga 7 / 4 pcs / Price : 9000000', 'purchase', 'unread', '2025-04-24 12:07:22'),
(22, 7, 8, 56, 'Your order #56 has been accepted.', 'response', 'unread', '2025-04-24 13:07:01');

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
(15, 'golden1', 'qweqwe', 100, 30000, 'uploads/IdeaPadSlim1i15Gen 7.png', 8),
(16, 'Yoga 7', 'yoga yoga', 20, 9000000, 'uploads/Lenovo LOQ 15AHP9.webp', 9);

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
(8, 'seller', 'seller@gmail.com', '$2y$10$UDaS4VSbpy/6agSDMJ6NKOagGCRjQVNWvmeZm41vT4FC/gkmqyB8.', 'seller', '2025-04-20 23:13:36', 0x313734353136323031365f57494e5f32303234313032395f30375f35365f31365f50726f2e6a7067, '09123123123'),
(9, 'seller 2', 'seller2@gmail.com', '$2y$10$3TGAHAHB9KOkRa4YnFbuWOlWQFaDKv5FBP8d6p/KJZkyjNvZx7RXu', 'seller', '2025-04-24 20:05:11', 0x313734353439363331315f50726f66696c652e6a7067, '09123123123'),
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
  MODIFY `addcart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `billing_orders`
--
ALTER TABLE `billing_orders`
  MODIFY `billing_order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `prod_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
