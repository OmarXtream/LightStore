-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 05, 2019 at 09:52 AM
-- Server version: 5.7.26
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wsenet_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `Customers`
--

CREATE TABLE `Customers` (
  `id` int(11) NOT NULL,
  `username` varchar(16) DEFAULT NULL,
  `password` varchar(64) DEFAULT NULL,
  `email` varchar(254) DEFAULT NULL,
  `phonenumber` varchar(20) DEFAULT NULL,
  `Credits` float DEFAULT '0',
  `img` text,
  `verify` int(11) DEFAULT '0',
  `verifyCode` varchar(80) DEFAULT NULL,
  `createdTime` int(11) NOT NULL DEFAULT '0',
  `rank` int(11) NOT NULL DEFAULT '0',
  `isStaff` int(11) NOT NULL DEFAULT '0',
  `lastupname` int(11) NOT NULL DEFAULT '0',
  `lastphonechanged` int(11) NOT NULL DEFAULT '0',
  `lastpwdu` int(11) NOT NULL DEFAULT '0',
  `lastavatar` int(11) NOT NULL DEFAULT '0',
  `timeresetpass` int(11) NOT NULL DEFAULT '0',
  `resetpass` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `copyrights` varchar(70) NOT NULL,
  `more` varchar(256) NOT NULL,
  `note` varchar(256) NOT NULL DEFAULT '-',
  `cl_id` int(11) NOT NULL,
  `pr_id` int(11) NOT NULL,
  `pr_name` varchar(70) NOT NULL,
  `pr_type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `owners`
--

CREATE TABLE `owners` (
  `id` int(11) NOT NULL,
  `id_pr` int(11) DEFAULT NULL,
  `pr_name` varchar(255) NOT NULL,
  `id_cl` int(11) NOT NULL,
  `file` varchar(256) NOT NULL,
  `create_time` int(11) NOT NULL,
  `code` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `cid` int(11) NOT NULL DEFAULT '0',
  `price` int(11) NOT NULL DEFAULT '0',
  `method` int(11) NOT NULL DEFAULT '0',
  `email` varchar(300) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `firstname` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `lastname` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `token` varchar(500) DEFAULT NULL,
  `payerid` varchar(200) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `time` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(70) NOT NULL,
  `type` int(11) NOT NULL,
  `describes` varchar(256) NOT NULL,
  `price` int(11) NOT NULL,
  `enc` int(11) NOT NULL DEFAULT '1',
  `create_time` int(11) NOT NULL,
  `file` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sitesettings`
--

CREATE TABLE `sitesettings` (
  `closeSite` int(11) NOT NULL DEFAULT '0',
  `closepaypal` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Sproducts`
--

CREATE TABLE `Sproducts` (
  `id` int(11) NOT NULL,
  `pr_name` varchar(70) NOT NULL,
  `pr_type` int(11) NOT NULL,
  `pr_des` varchar(256) NOT NULL,
  `pr_price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ticket`
--

CREATE TABLE `ticket` (
  `id` int(11) NOT NULL,
  `creator` int(11) DEFAULT '0',
  `title` varchar(64) DEFAULT NULL,
  `msg` varchar(260) DEFAULT NULL,
  `type` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `time` int(11) DEFAULT '0',
  `forwho` int(11) DEFAULT '0',
  `for_rank` int(11) DEFAULT '0',
  `needed_see` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_replies`
--

CREATE TABLE `ticket_replies` (
  `id` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  `reply` varchar(260) CHARACTER SET utf8 NOT NULL,
  `replyfrom` int(11) NOT NULL,
  `reply_time` int(11) NOT NULL,
  `from_rank` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Customers`
--
ALTER TABLE `Customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `owners`
--
ALTER TABLE `owners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Sproducts`
--
ALTER TABLE `Sproducts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `creator` (`creator`);

--
-- Indexes for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tid` (`tid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Customers`
--
ALTER TABLE `Customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `owners`
--
ALTER TABLE `owners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Sproducts`
--
ALTER TABLE `Sproducts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket`
--
ALTER TABLE `ticket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
