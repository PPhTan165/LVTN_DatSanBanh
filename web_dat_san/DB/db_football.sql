-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 15, 2024 at 02:05 PM
-- Server version: 5.7.31
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_football`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

DROP TABLE IF EXISTS `booking`;
CREATE TABLE IF NOT EXISTS `booking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `total_revenue` decimal(10,2) NOT NULL,
  `cus_id` int(11) NOT NULL,
  `pitch_detail_id` int(11) NOT NULL,
  `promotion_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cus_id` (`cus_id`),
  KEY `promotion_id` (`promotion_id`),
  KEY `fk_pich_detail_id` (`pitch_detail_id`),
  KEY `fk_status_id` (`status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `team_id` (`team_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `duration`
--

DROP TABLE IF EXISTS `duration`;
CREATE TABLE IF NOT EXISTS `duration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start` time NOT NULL,
  `end` time NOT NULL,
  `period_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `period_id` (`period_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `duration`
--

INSERT INTO `duration` (`id`, `start`, `end`, `period_id`) VALUES
(1, '06:00:00', '06:59:59', 1),
(2, '07:00:00', '07:59:59', 1),
(3, '08:00:00', '08:59:59', 1),
(4, '09:00:00', '09:59:59', 1),
(5, '10:00:00', '10:59:59', 2),
(6, '11:00:00', '11:59:59', 2),
(7, '12:00:00', '12:59:59', 2),
(8, '13:00:00', '13:59:59', 2),
(9, '14:00:00', '14:59:59', 2),
(10, '15:00:00', '15:59:59', 3),
(11, '16:00:00', '16:59:59', 3),
(12, '17:00:00', '17:59:59', 3),
(13, '18:00:00', '18:59:59', 4),
(14, '19:00:00', '19:59:59', 4),
(15, '20:00:00', '20:59:59', 4),
(16, '21:00:00', '21:59:59', 4),
(17, '22:00:00', '22:59:59', 4),
(18, '23:00:00', '23:59:59', 4);

-- --------------------------------------------------------

--
-- Table structure for table `manager`
--

DROP TABLE IF EXISTS `manager`;
CREATE TABLE IF NOT EXISTS `manager` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `match`
--

DROP TABLE IF EXISTS `match`;
CREATE TABLE IF NOT EXISTS `match` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `pitch_id` int(11) NOT NULL,
  `tournament_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tournament_id` (`tournament_id`),
  KEY `pitch_id` (`pitch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `match_detail`
--

DROP TABLE IF EXISTS `match_detail`;
CREATE TABLE IF NOT EXISTS `match_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `team_id` int(11) NOT NULL,
  `match_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `team_id` (`team_id`),
  KEY `match_id` (`match_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `period`
--

DROP TABLE IF EXISTS `period`;
CREATE TABLE IF NOT EXISTS `period` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `period`
--

INSERT INTO `period` (`id`, `name`) VALUES
(1, 'morning'),
(2, 'afternoon'),
(3, 'evening'),
(4, 'midnight');

-- --------------------------------------------------------

--
-- Table structure for table `pitch`
--

DROP TABLE IF EXISTS `pitch`;
CREATE TABLE IF NOT EXISTS `pitch` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` decimal(5,2) NOT NULL,
  `manager_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`),
  KEY `manager_id` (`manager_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pitch_detail`
--

DROP TABLE IF EXISTS `pitch_detail`;
CREATE TABLE IF NOT EXISTS `pitch_detail` (
  `id` int(11) NOT NULL,
  `duration_id` int(11) NOT NULL,
  `pitch_id` int(11) NOT NULL,
  `price_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `duration_id` (`duration_id`),
  KEY `price_id` (`price_id`),
  KEY `pitch_id` (`pitch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `price`
--

DROP TABLE IF EXISTS `price`;
CREATE TABLE IF NOT EXISTS `price` (
  `id` int(11) NOT NULL,
  `price_per_hour` decimal(10,2) NOT NULL,
  `period_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `period_id` (`period_id`),
  KEY `type_id` (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `promotion`
--

DROP TABLE IF EXISTS `promotion`;
CREATE TABLE IF NOT EXISTS `promotion` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `discount` decimal(5,2) NOT NULL,
  `max_get` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referee`
--

DROP TABLE IF EXISTS `referee`;
CREATE TABLE IF NOT EXISTS `referee` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `match_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `match_id` (`match_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `revenue`
--

DROP TABLE IF EXISTS `revenue`;
CREATE TABLE IF NOT EXISTS `revenue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `daily_revenue` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `manager_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `manager_id` (`manager_id`),
  KEY `type_id` (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

DROP TABLE IF EXISTS `review`;
CREATE TABLE IF NOT EXISTS `review` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` int(11) NOT NULL,
  `cus_id` int(11) NOT NULL,
  `pitch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pitch_id` (`pitch_id`),
  KEY `cus_id` (`cus_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'manager'),
(3, 'customer');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

DROP TABLE IF EXISTS `status`;
CREATE TABLE IF NOT EXISTS `status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id`, `name`) VALUES
(1, 'Created'),
(2, 'Canceled'),
(3, 'Done');

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

DROP TABLE IF EXISTS `team`;
CREATE TABLE IF NOT EXISTS `team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tournament`
--

DROP TABLE IF EXISTS `tournament`;
CREATE TABLE IF NOT EXISTS `tournament` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_day` date DEFAULT NULL,
  `end_day` date DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `manager_id` (`manager_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `type`
--

DROP TABLE IF EXISTS `type`;
CREATE TABLE IF NOT EXISTS `type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `type`
--

INSERT INTO `type` (`id`, `name`) VALUES
(1, 'sân 5'),
(2, 'sân 7'),
(3, 'sân 11');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL,
  `deleted` tinyint(1) DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`cus_id`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`promotion_id`) REFERENCES `promotion` (`id`),
  ADD CONSTRAINT `fk_pich_detail_id` FOREIGN KEY (`pitch_detail_id`) REFERENCES `pitch_detail` (`id`),
  ADD CONSTRAINT `fk_status_id` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`);

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `customer_ibfk_2` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`);

--
-- Constraints for table `duration`
--
ALTER TABLE `duration`
  ADD CONSTRAINT `duration_ibfk_1` FOREIGN KEY (`period_id`) REFERENCES `period` (`id`);

--
-- Constraints for table `manager`
--
ALTER TABLE `manager`
  ADD CONSTRAINT `manager_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `match`
--
ALTER TABLE `match`
  ADD CONSTRAINT `match_ibfk_1` FOREIGN KEY (`tournament_id`) REFERENCES `tournament` (`id`),
  ADD CONSTRAINT `pitch_id` FOREIGN KEY (`pitch_id`) REFERENCES `pitch` (`id`);

--
-- Constraints for table `match_detail`
--
ALTER TABLE `match_detail`
  ADD CONSTRAINT `match_detail_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`),
  ADD CONSTRAINT `match_detail_ibfk_2` FOREIGN KEY (`match_id`) REFERENCES `match` (`id`);

--
-- Constraints for table `pitch`
--
ALTER TABLE `pitch`
  ADD CONSTRAINT `pitch_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `type` (`id`),
  ADD CONSTRAINT `pitch_ibfk_2` FOREIGN KEY (`manager_id`) REFERENCES `manager` (`id`);

--
-- Constraints for table `pitch_detail`
--
ALTER TABLE `pitch_detail`
  ADD CONSTRAINT `pitch_detail_ibfk_1` FOREIGN KEY (`duration_id`) REFERENCES `duration` (`id`),
  ADD CONSTRAINT `pitch_detail_ibfk_2` FOREIGN KEY (`price_id`) REFERENCES `price` (`id`),
  ADD CONSTRAINT `pitch_detail_ibfk_3` FOREIGN KEY (`pitch_id`) REFERENCES `pitch` (`id`);

--
-- Constraints for table `price`
--
ALTER TABLE `price`
  ADD CONSTRAINT `price_ibfk_1` FOREIGN KEY (`period_id`) REFERENCES `period` (`id`),
  ADD CONSTRAINT `price_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `type` (`id`);

--
-- Constraints for table `referee`
--
ALTER TABLE `referee`
  ADD CONSTRAINT `referee_ibfk_1` FOREIGN KEY (`match_id`) REFERENCES `match` (`id`);

--
-- Constraints for table `revenue`
--
ALTER TABLE `revenue`
  ADD CONSTRAINT `revenue_ibfk_1` FOREIGN KEY (`manager_id`) REFERENCES `manager` (`id`),
  ADD CONSTRAINT `revenue_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `type` (`id`);

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`pitch_id`) REFERENCES `pitch` (`id`),
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`cus_id`) REFERENCES `customer` (`id`);

--
-- Constraints for table `tournament`
--
ALTER TABLE `tournament`
  ADD CONSTRAINT `tournament_ibfk_1` FOREIGN KEY (`manager_id`) REFERENCES `manager` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
