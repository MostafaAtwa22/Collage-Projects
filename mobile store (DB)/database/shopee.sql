-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 20, 2020 at 11:01 AM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shopee`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL default 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `item_id` int(11) NOT NULL PRIMARY key,
  `item_brand` varchar(200) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_price` double(10,2) NOT NULL,
  `item_dis` DECIMAL(10, 2) DEFAULT 0,
  `item_image` varchar(255) NOT NULL,
  `item_register` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add quantity column to the product table
ALTER TABLE `product`
  ADD COLUMN `item_quantity` INT NOT NULL DEFAULT 0 AFTER `item_image`;

-- Update the existing product data with a default quantity (e.g., 1)
UPDATE `product` SET `item_quantity` = 3;

-- Display the updated structure
DESCRIBE `product`;
--
-- Dumping data for table `product`
--

INSERT INTO `product` (`item_id`, `item_brand`, `item_name`, `item_price`, `item_image`, `item_register`, `item_quantity`) VALUES
(1, 'Samsung', 'Samsung Note 10', 152.00, './assets/products/65.png', '2020-03-28 11:08:57',10), -- NOW()
(2, 'Redmi', 'Redmi Note 7', 122.00, './assets/products/2.png', '2020-03-28 11:08:57',10),
(3, 'Redmi', 'Redmi Note 6', 122.00, './assets/products/3.png', '2020-03-28 11:08:57',10),
(4, 'Redmi', 'Redmi Note 11', 122.00, './assets/products/62.png', '2020-03-28 11:08:57',10),
(5, 'Apple', 'Airpods', 122.00, './assets/70.png', '2020-03-28 11:08:57',10),
(6, 'Redmi', 'Redmi Note 8', 122.00, './assets/products/6.png', '2020-03-28 11:08:57',10),
(7, 'Redmi', 'Redmi Note 9', 122.00, './assets/products/8.png', '2020-03-28 11:08:57',10),
(8, 'Redmi', 'Redmi Note 11', 122.00, './assets/products/62.png', '2020-03-28 11:08:57',10),
(9, 'Samsung', 'Samsung Galaxy 10', 152.00, './assets/products/66.png', '2020-03-28 11:08:57',10),
(10, 'Apple', 'Apple iPhone 15', 152.00, './assets/products/40.png', '2020-03-28 11:08:57',10),
(11, 'Apple', 'Apple iPhone 14', 152.00, './assets/products/42.png', '2020-03-28 11:08:57',10),
(12, 'Apple', 'Apple iPhone 15', 152.00, './assets/products/43.png', '2020-03-28 11:08:57',10),
(13, 'Apple', 'Apple iPhone 14', 152.00, './assets/products/48.png', '2020-03-28 11:08:57',10),
(14, 'Apple', 'Airpods', 152.00, './assets/products/44.png', '2020-03-28 11:08:57',10);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL unique,
  `register_date` datetime DEFAULT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `first_name`, `last_name`,`email`, `register_date`,`password`) VALUES
(1, 'Daily', 'Tuition','daily@gmail.com', '2020-03-28 13:07:17','123456789'),
(2, 'Akshay', 'Kashyap','akshay@gmail.com', '2020-03-28 13:07:17','123456789');

-- --------------------------------------------------------

--

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`);


--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

-- Table structure for table `admin`
CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL unique,
  `password` varchar(100) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `admin` (`admin_id`, `first_name`, `last_name`, `email`, `password`, `is_admin`)
VALUES (1, 'Omar', 'Khaled', 'omar@gmail.com', '123456789', 1);



-- Indexes for table `admin`
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

CREATE TABLE orders (
    `order_id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` int(11) ,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `address` TEXT NOT NULL,
    `subtotal` DECIMAL(10,2) NOT NULL,
    `status` VARCHAR(20) DEFAULT 'ordered',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE orders
ADD COLUMN num_of_items INT NOT NULL;
-- Add foreign key constraint to orders table
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user_id`
  FOREIGN KEY (`user_id`)
  REFERENCES `user` (`user_id`);

-- Add foreign key constraint to cart table
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_cart_user_id`
  FOREIGN KEY (`user_id`)
  REFERENCES `user` (`user_id`);

ALTER TABLE `cart`
  MODIFY COLUMN `user_id` int(11) NULL DEFAULT NULL;


-- Create a new table for storing contact form messages
CREATE TABLE `contact_messages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `message` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `wishlist_id` int(11) NOT NULL PRIMARY key,
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `wishlist`
  MODIFY `wishlist_id` int(11) NOT NULL AUTO_INCREMENT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
