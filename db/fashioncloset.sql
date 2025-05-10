-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2025 at 03:10 AM
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
-- Database: `fashioncloset`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `AdminID` int(11) NOT NULL,
  `AdminName` varchar(100) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `CreatedAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`AdminID`, `AdminName`, `Email`, `PasswordHash`, `CreatedAt`) VALUES
(1, 'Annabel', 'annabella@gmail.com', '$2b$12$rrdBVXZ2WeGMCpO7VuL3t.wYd0qYVugBPCcKerCDUtkjJXQTGBBVG', '0000-00-00 00:00:00'),
(2, 'Frederick Nnubeng', 'frederickakonnubeng@gmail.com', '$2b$12$rrdBVXZ2WeGMCpO7VuL3t.wYd0qYVugBPCcKerCDUtkjJXQTGBBVG', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `CategoryID` int(11) NOT NULL,
  `CategoryName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`CategoryID`, `CategoryName`) VALUES
(4, 'Accessories'),
(1, 'Kids Fashion'),
(2, 'Mens Fashion'),
(3, 'Womens Fashion');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `MessageID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Message` text NOT NULL,
  `CreatedAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`MessageID`, `Name`, `Email`, `Message`, `CreatedAt`) VALUES
(1, 'Frederick Nnubeng', 'frederickakonnubeng@gmail.com', 'Make the user id show on the front page when we look login. It will the page better, I think.\r\nAnd also, your products are very affordable.', '2024-12-31 01:21:25'),
(2, 'Obeng Blessing', 'obengblessing@gmail.com', 'I like your website', '2024-12-31 11:32:24');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `OrderID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `OrderDate` datetime DEFAULT current_timestamp(),
  `TotalAmount` decimal(10,2) NOT NULL,
  `PaymentMethod` varchar(50) NOT NULL,
  `Status` varchar(50) DEFAULT 'Pending',
  `FullName` varchar(255) NOT NULL,
  `Address` text NOT NULL,
  `Phone` varchar(20) NOT NULL,
  `City` varchar(100) NOT NULL,
  `PostalCode` varchar(20) NOT NULL,
  `Country` varchar(100) NOT NULL,
  `TransactionID` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`OrderID`, `UserID`, `OrderDate`, `TotalAmount`, `PaymentMethod`, `Status`, `FullName`, `Address`, `Phone`, `City`, `PostalCode`, `Country`, `TransactionID`) VALUES
(1, 1, '2024-12-30 01:32:54', 64.95, 'mobileMoney', 'Confirmed', 'Obeng Blessing', 'AH-1433-3520', '0555945568', 'Asuofua', '233', 'Ghana', ''),
(2, 1, '2024-12-30 12:23:25', 135.95, 'bankTransfer', 'Confirmed', 'Frederick Nnubeng', 'AH-1433-3520', '0555945568', 'Kumasi - Bantame', '233', 'Ghana', 'Mr Frederick'),
(3, 2, '2024-12-30 18:30:24', 90.98, 'bankTransfer', 'Confirmed', 'Frederick Nnubeng', 'JJ Nortey', '0555945568', 'Accra Oyibi', '233', 'Ghana', 'Frederick Nnubeng'),
(4, 2, '2024-12-30 19:02:20', 107.97, 'mobileMoney', 'Confirmed', 'Frederick Nnubeng', 'JJ Nortey', '0200238603', 'Accra Oyibi', '233', 'Ghana', ''),
(5, 1, '2024-12-30 19:23:43', 24.67, 'mobileMoney', 'Confirmed', 'Obeng Blessing', 'AH-1433-3520', '0596890677', 'Asuofua', '233', 'Ghana', ''),
(6, 1, '2024-12-31 01:04:18', 27.36, 'mobileMoney', 'Confirmed', 'Obeng Blessing', 'AH-1433-3529', '0596890677', 'Asuofua', '233', 'Ghana', ''),
(7, 1, '2024-12-31 11:27:53', 16.97, 'mobileMoney', 'Confirmed', 'Obeng Blessing', 'AH-1433-3520', '0596890677', 'Asuofua', '233', 'Ghana', ''),
(10, 3, '2025-01-01 20:49:59', 79.99, 'mobileMoney', 'Confirmed', 'Nnubeng Junior', 'Plot S-54 Santasi', '0536385829', 'Kumasi - Santasi', '233', 'Ghana', ''),
(11, 3, '2025-01-01 20:50:57', 16.98, 'bankTransfer', 'Confirmed', 'Nnubeng Junior', 'Plot S-54 Santasi', '0536385829', 'Kumasi - Santasi', '233', 'Ghana', '4883234982323'),
(12, 3, '2025-01-01 20:55:52', 5.99, 'mobileMoney', 'Confirmed', 'Nnubeng Junior', 'Plot S-54 Santasi', '0536385829', 'Kumasi - Santasi', '233', 'Ghana', ''),
(13, 4, '2025-05-07 20:15:56', 79.99, 'mobileMoney', 'Confirmed', 'Aboagye Alex', 'Prempeh 2nd Street', '0555945568', 'Bantama', '233', 'Ghana', ''),
(14, 4, '2025-05-07 23:38:02', 74.22, 'bankTransfer', 'Confirmed', 'Aboagye Alex', 'Prempeh 2nd Street', '0555945568', 'Bantama', '233', 'Ghana', '23434500000000000');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `OrderItemID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `UnitPrice` decimal(10,2) NOT NULL,
  `TotalPrice` decimal(10,2) GENERATED ALWAYS AS (`Quantity` * `UnitPrice`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`OrderItemID`, `OrderID`, `ProductID`, `Quantity`, `UnitPrice`) VALUES
(26, 14, 34, 1, 8.99),
(27, 14, 30, 1, 5.99),
(28, 14, 31, 1, 59.24);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(255) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Stock` int(11) NOT NULL,
  `CategoryID` int(11) NOT NULL,
  `Image` longblob DEFAULT NULL,
  `CreatedAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ProductID`, `ProductName`, `Price`, `Stock`, `CategoryID`, `Image`, `CreatedAt`) VALUES
(21, 'Glod watch', 45.99, 8, 4, 0x75706c6f6164732f676f6c642077617463682e6a7067, '2025-05-07 22:23:58'),
(22, 'Suit watch', 78.99, 9, 4, 0x75706c6f6164732f77617463682d6261636b67726f756e642e6a7067, '2025-05-07 22:24:47'),
(23, 'Casual women wears', 9.96, 200, 3, 0x75706c6f6164732f436c6f746865735f43617465676f72792831292e6a7067, '2025-05-07 22:31:57'),
(24, 'Hoodies', 7.99, 30, 2, 0x75706c6f6164732f686f6f6469652e6a7067, '2025-05-07 22:32:57'),
(25, 'Kids Casual Wear', 6.43, 30, 1, 0x75706c6f6164732f6b69642831292e6a7067, '2025-05-07 22:33:38'),
(26, 'Kids Jackets', 7.99, 40, 1, 0x75706c6f6164732f6b69642832292e6a7067, '2025-05-07 22:34:26'),
(27, 'Jeans Tops', 8.95, 27, 2, 0x75706c6f6164732f6d656e2831292e6a7067, '2025-05-07 22:35:00'),
(28, 'Designer Tops', 9.99, 48, 2, 0x75706c6f6164732f6d656e31342e6a7067, '2025-05-07 22:35:41'),
(29, 'Black Jacket', 7.99, 13, 2, 0x75706c6f6164732f6d656e2832292e6a7067, '2025-05-07 22:36:14'),
(30, 'Shirts', 5.99, 50, 2, 0x75706c6f6164732f73686972742e6a7067, '2025-05-07 22:36:44'),
(31, 'Gold Black Watch', 59.24, 7, 4, 0x75706c6f6164732f79656c6c6f7720676f6c6420626c61636b2077617463682e6a7067, '2025-05-07 22:37:34'),
(32, 'Women Dresses', 5.99, 50, 3, 0x75706c6f6164732f776f6d656e2833292e6a7067, '2025-05-07 22:38:35'),
(33, 'Jacket + Inner Tops', 15.99, 57, 2, 0x75706c6f6164732f6d656e31332e6a7067, '2025-05-07 23:09:21'),
(34, 'Cotton Tops', 8.99, 28, 2, 0x75706c6f6164732f6d656e31352e6a7067, '2025-05-07 23:10:29'),
(35, 'No Hands', 7.32, 14, 3, 0x75706c6f6164732f776f6d656e2e6a7067, '2025-05-07 23:11:14');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `store_name` varchar(100) NOT NULL DEFAULT 'Anna''s Closet',
  `store_email` varchar(100) NOT NULL DEFAULT 'info@annascloset.com',
  `store_phone` varchar(20) NOT NULL DEFAULT '+233 123 456 789',
  `store_address` text NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'GHS',
  `tax_rate` decimal(5,2) NOT NULL DEFAULT 12.50,
  `shipping_cost` decimal(10,2) NOT NULL DEFAULT 15.00,
  `free_shipping_threshold` decimal(10,2) NOT NULL DEFAULT 100.00,
  `enable_mobile_money` tinyint(1) NOT NULL DEFAULT 1,
  `enable_bank_transfer` tinyint(1) NOT NULL DEFAULT 1,
  `maintenance_mode` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `store_name`, `store_email`, `store_phone`, `store_address`, `currency`, `tax_rate`, `shipping_cost`, `free_shipping_threshold`, `enable_mobile_money`, `enable_bank_transfer`, `maintenance_mode`) VALUES
(1, 'Anna\'s Closet', 'info@annascloset.com', '+233 123 456 789', 'Vally View University, Accra, Ghana', 'USD', 12.50, 15.00, 100.00, 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `UserName` varchar(100) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `CreatedAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `UserName`, `Email`, `PasswordHash`, `CreatedAt`) VALUES
(1, 'Obeng Blessing', 'obengblessing@gmail.com', '$2b$12$rrdBVXZ2WeGMCpO7VuL3t.wYd0qYVugBPCcKerCDUtkjJXQTGBBVG', '2024-12-29 01:44:09'),
(2, 'Frederick Nnubeng', 'frederickakonnubeng@gmail.com', '$2b$12$rrdBVXZ2WeGMCpO7VuL3t.wYd0qYVugBPCcKerCDUtkjJXQTGBBVG', '2024-12-30 12:21:06'),
(3, 'Nnubeng Junior', 'frederickakonnubeng2020@gmail.com', '$2b$12$rrdBVXZ2WeGMCpO7VuL3t.wYd0qYVugBPCcKerCDUtkjJXQTGBBVG', '2025-01-01 19:58:48'),
(4, 'Aboagye Alex', 'aboagye@gmail.com', '$2y$10$xGWP3RtX8IubXJYi5ljo5u.WKYldXqenA6gwSUeNuWrNnQ8I6IjuG', '2025-05-07 20:04:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`AdminID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CategoryID`),
  ADD UNIQUE KEY `CategoryName` (`CategoryName`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`MessageID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`OrderItemID`),
  ADD KEY `OrderID` (`OrderID`),
  ADD KEY `ProductID` (`ProductID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ProductID`),
  ADD KEY `CategoryID` (`CategoryID`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `AdminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `MessageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `OrderItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `products` (`ProductID`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`CategoryID`) REFERENCES `categories` (`CategoryID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
