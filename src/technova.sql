-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2026 at 02:02 PM
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
-- Database: `technova`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`) VALUES
(1, 'Apple', 'apple'),
(2, 'Dell', 'dell'),
(3, 'Asus', 'asus'),
(4, 'HP', 'hp'),
(5, 'Lenovo', 'lenovo');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `total_amount` int(11) DEFAULT NULL,
  `status` enum('pending','processing','completed','cancelled') DEFAULT 'completed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `customer_name`, `total_amount`, `status`, `created_at`) VALUES
(1, 2, 'Nguyễn Văn A', 45990000, 'completed', '2026-01-15 03:00:00'),
(2, 3, 'Trần Thị B', 27990000, 'completed', '2026-01-20 07:00:00'),
(3, 4, 'Lê Quang C', 14500000, 'completed', '2026-02-10 02:00:00'),
(4, 5, 'Phạm Tuấn D', 85000000, 'completed', '2026-02-14 04:30:00'),
(5, 6, 'Hoàng Thị E', 39500000, 'completed', '2026-03-05 09:00:00'),
(6, 7, 'Ngô Văn F', 28990000, 'completed', '2026-03-12 03:20:00'),
(7, 8, 'Vũ Thị G', 25500000, 'completed', '2026-04-01 01:00:00'),
(8, 9, 'Đặng Văn H', 22500000, 'completed', '2026-04-18 08:45:00'),
(9, 10, 'Bùi Xuân I', 17000000, 'completed', '2026-05-10 06:00:00'),
(10, 11, 'Đỗ Minh K', 42000000, 'completed', '2026-05-22 02:15:00'),
(11, 12, 'Hồ Thanh L', 31000000, 'completed', '2026-06-05 04:10:00'),
(12, 13, 'Dương Đức M', 11500000, 'completed', '2026-06-19 07:30:00'),
(13, 14, 'Lý Thu N', 32000000, 'completed', '2026-07-07 03:00:00'),
(14, 15, 'Vương Tuấn O', 28000000, 'completed', '2026-07-25 09:20:00'),
(15, 16, 'Trịnh Mai P', 34000000, 'completed', '2026-08-11 02:40:00'),
(16, 17, 'Đinh Văn Q', 29000000, 'completed', '2026-08-30 08:00:00'),
(17, 18, 'Phan Khắc R', 19000000, 'completed', '2026-09-02 07:15:00'),
(18, 19, 'Trương Cẩm S', 55000000, 'completed', '2026-09-15 03:50:00'),
(19, 20, 'Lâm Gia T', 41000000, 'completed', '2026-10-01 01:30:00'),
(20, 21, 'Đào Quỳnh U', 38000000, 'completed', '2026-10-18 04:00:00'),
(21, 22, 'Tô Bích V', 15500000, 'completed', '2026-11-05 06:20:00'),
(22, 23, 'Đoàn Trọng W', 18500000, 'completed', '2026-11-20 08:45:00'),
(23, 24, 'Thái Nhật X', 45990000, 'completed', '2026-12-01 02:00:00'),
(24, 25, 'Châu Hải Y', 27990000, 'completed', '2026-12-15 07:30:00'),
(25, 26, 'Mai Anh Z', 39500000, 'completed', '2026-12-25 03:00:00'),
(26, 27, 'Kiều Quang A', 21000000, 'completed', '2026-12-28 09:10:00'),
(27, 28, 'Tạ Băng B', 35000000, 'pending', '2026-11-15 03:00:00'),
(28, 29, 'Tôn Thất C', 23000000, 'processing', '2026-11-18 07:00:00'),
(29, 30, 'Chử Đồng D', 26000000, 'cancelled', '2026-12-10 02:00:00'),
(30, 31, 'Lục Kim E', 18990000, 'pending', '2026-12-29 04:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 1, 1, 45990000),
(2, 2, 2, 1, 27990000),
(3, 3, 6, 1, 14500000),
(4, 4, 3, 1, 85000000),
(5, 5, 5, 1, 39500000),
(6, 6, 9, 1, 28990000),
(7, 7, 10, 1, 25500000),
(8, 8, 13, 1, 22500000),
(9, 9, 14, 1, 17000000),
(10, 10, 17, 1, 42000000),
(11, 11, 18, 1, 31000000),
(12, 12, 19, 1, 11500000),
(13, 13, 21, 1, 32000000),
(14, 14, 22, 1, 28000000),
(15, 15, 23, 1, 34000000),
(16, 16, 24, 1, 29000000),
(17, 17, 25, 1, 19000000),
(18, 18, 26, 1, 55000000),
(19, 19, 27, 1, 41000000),
(20, 20, 28, 1, 38000000),
(21, 21, 29, 1, 15500000),
(22, 22, 30, 1, 18500000),
(23, 23, 1, 1, 45990000),
(24, 24, 2, 1, 27990000),
(25, 25, 5, 1, 39500000),
(26, 26, 11, 1, 21000000),
(27, 27, 15, 1, 35000000),
(28, 28, 16, 1, 23000000),
(29, 29, 20, 1, 26000000),
(30, 30, 4, 1, 18990000);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `stock` int(11) DEFAULT 10,
  `img` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `brand_id`, `name`, `price`, `stock`, `img`, `created_at`) VALUES
(1, 1, 'MacBook Pro 14 M2 Pro', 45990000, 15, 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8', '2026-10-19 15:53:11'),
(2, 1, 'MacBook Air M2', 27990000, 30, 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8', '2026-10-19 15:53:11'),
(3, 1, 'MacBook Pro 16 M2 Max', 85000000, 5, 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8', '2026-10-19 15:53:11'),
(4, 1, 'MacBook Air M1', 18990000, 50, 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8', '2026-10-19 15:53:11'),
(5, 2, 'Dell XPS 13 Plus', 39500000, 12, 'https://images.unsplash.com/photo-1593642702821-c823b2816291', '2026-10-19 15:53:11'),
(6, 2, 'Dell Inspiron 15 3520', 14500000, 40, 'https://images.unsplash.com/photo-1593642702821-c823b2816291', '2026-10-19 15:53:11'),
(7, 2, 'Dell Alienware m15 R7', 65000000, 8, 'https://images.unsplash.com/photo-1593642702821-c823b2816291', '2026-10-19 15:53:11'),
(8, 2, 'Dell Vostro 3420', 16000000, 25, 'https://images.unsplash.com/photo-1593642702821-c823b2816291', '2026-10-19 15:53:11'),
(9, 3, 'Asus ROG Strix G15', 28990000, 0, 'https://images.unsplash.com/photo-1603302576837-37561b2e2302', '2026-10-19 15:53:11'),
(10, 3, 'Asus Zenbook 14 OLED', 25500000, 18, 'https://images.unsplash.com/photo-1603302576837-37561b2e2302', '2026-10-19 15:53:11'),
(11, 3, 'Asus TUF Gaming F15', 21000000, 20, 'https://images.unsplash.com/photo-1603302576837-37561b2e2302', '2026-10-19 15:53:11'),
(12, 3, 'Asus Vivobook 15', 12500000, 45, 'https://images.unsplash.com/photo-1603302576837-37561b2e2302', '2026-10-19 15:53:11'),
(13, 4, 'HP Envy x360', 22500000, 10, 'https://images.unsplash.com/photo-1531297172867-4f40f09805d7', '2026-10-19 15:53:11'),
(14, 4, 'HP Pavilion 15', 17000000, 22, 'https://images.unsplash.com/photo-1531297172867-4f40f09805d7', '2026-10-19 15:53:11'),
(15, 4, 'HP Omen 16', 35000000, 6, 'https://images.unsplash.com/photo-1531297172867-4f40f09805d7', '2026-10-19 15:53:11'),
(16, 4, 'HP Victus 16', 23000000, 14, 'https://images.unsplash.com/photo-1531297172867-4f40f09805d7', '2026-10-19 15:53:11'),
(17, 5, 'Lenovo ThinkPad X1 Carbon', 42000000, 7, 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed', '2026-10-19 15:53:11'),
(18, 5, 'Lenovo Legion 5', 31000000, 16, 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed', '2026-10-19 15:53:11'),
(19, 5, 'Lenovo IdeaPad 3', 11500000, 35, 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed', '2026-10-19 15:53:11'),
(20, 5, 'Lenovo Yoga 7i', 26000000, 11, 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed', '2026-10-19 15:53:11'),
(21, 1, 'MacBook Pro 13 M2', 32000000, 20, 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8', '2026-10-19 15:53:11'),
(22, 2, 'Dell Latitude 7420', 28000000, 15, 'https://images.unsplash.com/photo-1593642702821-c823b2816291', '2026-10-19 15:53:11'),
(23, 3, 'Asus ExpertBook B9', 34000000, 5, 'https://images.unsplash.com/photo-1603302576837-37561b2e2302', '2026-10-19 15:53:11'),
(24, 4, 'HP EliteBook 840', 29000000, 9, 'https://images.unsplash.com/photo-1531297172867-4f40f09805d7', '2026-10-19 15:53:11'),
(25, 5, 'Lenovo ThinkBook 14', 19000000, 25, 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed', '2026-10-19 15:53:11'),
(26, 1, 'Mac Studio M2 Max', 55000000, 4, 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8', '2026-10-19 15:53:11'),
(27, 2, 'Dell Precision 3570', 41000000, 6, 'https://images.unsplash.com/photo-1593642702821-c823b2816291', '2026-10-19 15:53:11'),
(28, 3, 'Asus ROG Zephyrus G14', 38000000, 8, 'https://images.unsplash.com/photo-1603302576837-37561b2e2302', '2026-10-19 15:53:11'),
(29, 4, 'HP ProBook 450', 15500000, 30, 'https://images.unsplash.com/photo-1531297172867-4f40f09805d7', '2026-10-19 15:53:11'),
(30, 5, 'Lenovo ThinkPad E14', 18500000, 19, 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed', '2026-10-19 15:53:11'),
(31, 1, 'MacBook Pro', 30000000, 10, 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8', '2026-10-19 16:35:47');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Admin', 'admin@technova.vn', '123456', 'admin', '2026-10-19 15:53:11'),
(2, 'Nguyễn Văn A', 'nva1@gmail.com', '123456', 'user', '2026-10-19 15:53:11'),
(3, 'Trần Thị B', 'ttb2@gmail.com', '123456', 'user', '2026-10-19 15:53:11'),
(4, 'Lê Quang C', 'lqc3@gmail.com', '123456', 'user', '2026-10-19 15:53:11'),
(5, 'Phạm Tuấn D', 'ptd4@gmail.com', '123456', 'user', '2026-10-19 15:53:11'),
(6, 'Hoàng Thị E', 'hte5@gmail.com', '123456', 'user', '2026-10-19 15:53:11'),
(7, 'Ngô Văn F', 'nvf6@gmail.com', '123456', 'user', '2026-10-19 15:53:11'),
(8, 'Vũ Thị G', 'vtg7@gmail.com', '123456', 'user', '2026-10-19 15:53:11'),
(9, 'Đặng Văn H', 'dvh8@gmail.com', '123456', 'user', '2026-10-19 15:53:11'),
(10, 'Bùi Xuân I', 'bxi9@gmail.com', '123456', 'user', '2026-10-19 15:53:11'),
(11, 'Đỗ Minh K', 'dmk10@gmail.com', '123456', 'user', '2026-10-19 15:53:11'),
(12, 'Hồ Thanh L', 'htl11@gmail.com', '123456', 'user', '2026-10-19 15:53:11'),
(13, 'Dương Đức M', 'ddm12@gmail.com', '123456', 'user', '2026-10-19 15:53:11'),
(14, 'Lý Thu N', 'ltn13@gmail.com', '123456', 'user', '2026-10-19 15:53:11'),
(15, 'Vương Tuấn O', 'vto14@gmail.com', '123456', 'user', '2026-10-19 15:53:11'),
(16, 'Trịnh Mai P', 'tmp15@gmail.com', '123456', 'user', '2026-10-19 15:53:11'),
(17, 'Đinh Văn Q', 'dvq16@gmail.com', '123456', 'user', '2026-10-19 15:53:11'),
(18, 'Phan Khắc R', 'pkr17@gmail.com', '123456', 'user', '2026-10-19 15:53:11'),
(19, 'Trương Cẩm S', 'tcs18@gmail.com', '123456', 'user', '2026-10-19 15:53:11'),
(20, 'Lâm Gia T', 'lgt19@gmail.com', '123456', 'user', '2026-10-19 15:53:11'),
(21, 'Đào Quỳnh U', 'dqu20@gmail.com', '123456', 'user', '2026-10-19 15:53:11'),
(22, 'Tô Bích V', 'tbv21@gmail.com', '123456', 'user', '2026-10-19 15:53:11'),
(23, 'Đoàn Trọng W', 'dtw22@gmail.com', '123456', 'user', '2026-10-19 15:53:11'),
(24, 'Thái Nhật X', 'tnx23@gmail.com', '123456', 'user', '2026-10-19 15:53:11'),
(25, 'Châu Hải Y', 'chy24@gmail.com', '123456', 'user', '2026-10-19 15:53:11'),
(26, 'Mai Anh Z', 'maz25@gmail.com', '123456', 'user', '2026-10-19 15:53:11'),
(27, 'Kiều Quang A', 'kqa26@gmail.com', '123456', 'user', '2026-10-19 15:53:11'),
(28, 'Tạ Băng B', 'tbb27@gmail.com', '123456', 'user', '2026-10-19 15:53:11'),
(29, 'Tôn Thất C', 'ttc28@gmail.com', '123456', 'user', '2026-10-19 15:53:11'),
(30, 'Chử Đồng D', 'cdd29@gmail.com', '123456', 'user', '2026-10-19 15:53:11'),
(31, 'Lục Kim E', 'lke30@gmail.com', '123456', 'user', '2026-10-19 15:53:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brand_id` (`brand_id`);

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
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
