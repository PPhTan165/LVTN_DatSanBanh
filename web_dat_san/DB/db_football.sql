-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3306
-- Thời gian đã tạo: Th8 11, 2024 lúc 04:57 PM
-- Phiên bản máy phục vụ: 5.7.31
-- Phiên bản PHP: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `db_football`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `booking`
--

DROP TABLE IF EXISTS `booking`;
CREATE TABLE IF NOT EXISTS `booking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `total` decimal(10,3) NOT NULL,
  `cus_id` int(11) NOT NULL,
  `pitch_detail_id` int(11) NOT NULL,
  `promotion_id` int(11) DEFAULT NULL,
  `status_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_cus_id_fk` (`cus_id`),
  KEY `booking_pitch_detail_id_fk` (`pitch_detail_id`),
  KEY `booking_promotion_id_fk` (`promotion_id`),
  KEY `booking_status_id_fk` (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `booking`
--

INSERT INTO `booking` (`id`, `name`, `phone`, `date`, `date_created`, `total`, `cus_id`, `pitch_detail_id`, `promotion_id`, `status_id`) VALUES
(1, 'Phan Phúc Tân', '0707719300', '2024-07-19', NULL, '630.000', 1, 18, 1, 3),
(2, 'Phan Phúc Tân', '0707719300', '2024-07-20', NULL, '300.000', 1, 8, NULL, 3),
(3, 'Phan Phúc Tân', '0707719300', '2024-07-22', NULL, '600.000', 1, 8, NULL, 3),
(4, 'Phan Phúc Tân', '0707719300', '2024-07-22', NULL, '600.000', 1, 9, NULL, 3),
(5, 'Giap GIap', '0707719300', '2024-07-22', NULL, '700.000', 1, 18, NULL, 2),
(6, 'Phan Hiếu Lâm', '0935660554', '2024-07-22', NULL, '400.000', 2, 32, NULL, 3),
(7, 'Phan Hiếu Lâm', '0935660554', '2024-07-22', NULL, '400.000', 2, 33, NULL, 3),
(8, 'Giap GIap', '0777466666', '2024-07-24', NULL, '400.000', 1, 68, NULL, 3),
(9, 'Giap GIap', '0777466666', '2024-07-24', NULL, '400.000', 1, 69, NULL, 3),
(10, 'Võ Tiến', '0356569985', '2024-07-26', NULL, '150.000', 1, 28, NULL, 3),
(11, 'Tân', '0707719300', '2024-07-27', NULL, '450.000', 1, 44, NULL, 3),
(12, 'Tân', '0707719300', '2024-07-27', NULL, '450.000', 1, 45, NULL, 3),
(13, 'Tân', '0707719300', '2024-07-27', NULL, '450.000', 1, 46, NULL, 3),
(14, 'Phan Phúc Tân', '0707719300', '2024-07-28', NULL, '450.000', 1, 38, NULL, 3),
(15, 'Phan Phúc Tân', '0707719300', '2024-07-28', NULL, '450.000', 1, 39, NULL, 3),
(16, 'Phan Phúc Tân', '0707719300', '2024-07-28', NULL, '450.000', 1, 40, NULL, 3),
(17, 'Phan Phúc Tân', '0707719300', '2024-07-27', NULL, '200.000', 2, 56, NULL, 3),
(18, 'Phan Phúc Tân', '0707719300', '2024-07-27', NULL, '200.000', 2, 57, NULL, 3),
(19, 'Phan Phúc Tân', '0707719300', '2024-08-02', NULL, '550.000', 1, 48, NULL, 3),
(20, 'Phan Phúc Tân', '0707719300', '2024-08-02', NULL, '550.000', 1, 49, NULL, 3),
(21, 'Phan Phúc Tân', '0707719300', '2024-08-02', '2024-08-03 00:10:03', '200.000', 1, 19, 1, 3),
(22, 'Phan Phúc Tân', '0707719300', '2024-08-02', '2024-08-03 00:10:03', '200.000', 1, 20, 1, 3),
(23, 'Phan Phúc Tân', '0707719300', '2024-08-03', '2024-08-03 09:56:13', '350.000', 1, 26, NULL, 3),
(24, 'Phan Phúc Tân', '0707719300', '2024-08-03', '2024-08-03 09:56:13', '350.000', 1, 27, NULL, 3),
(25, 'Phan Phúc Tân', '0707719300', '2024-08-03', '2024-08-03 09:56:13', '350.000', 1, 28, NULL, 3),
(26, 'Phan Phúc Tân', '0707719300', '2024-08-05', '2024-08-06 03:08:19', '400.000', 1, 109, NULL, 1),
(27, 'Phan Phúc Tân', '0707719300', '2024-08-05', '2024-08-06 03:08:19', '400.000', 1, 110, NULL, 1),
(28, 'Phan Phúc Tân', '0707719300', '2024-08-05', '2024-08-06 03:08:19', '400.000', 1, 111, NULL, 1),
(29, 'Phan Phúc Tân', '0707719300', '2024-08-05', '2024-08-06 03:08:19', '400.000', 1, 112, NULL, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `team_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_team_id_fk` (`team_id`),
  KEY `customer_user_id_fk` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `customer`
--

INSERT INTO `customer` (`id`, `name`, `phone`, `team_id`, `user_id`) VALUES
(1, 'Tân', '0774883391', NULL, 5),
(2, 'Phan Hieu Lam', '0987654321', NULL, 6),
(3, 'a', '0707719300', 6, 8),
(4, 'a', '0707719300', 7, 9),
(5, 'a', '0707719300', 8, 10),
(6, 'a', '0707719300', 9, 11),
(7, 'a', '0707719300', 10, 12),
(8, 'a', '0707719300', 11, 13),
(9, 'a', '0707719300', 12, 14),
(10, 'a', '0707719300', 13, 15),
(11, 'a', '0707719300', 14, 16);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `duration`
--

DROP TABLE IF EXISTS `duration`;
CREATE TABLE IF NOT EXISTS `duration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start` time NOT NULL,
  `end` time NOT NULL,
  `period_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `duration_period_id_fk` (`period_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `duration`
--

INSERT INTO `duration` (`id`, `start`, `end`, `period_id`) VALUES
(1, '06:00:00', '07:00:00', 1),
(2, '07:00:00', '08:00:00', 1),
(3, '08:00:00', '09:00:00', 1),
(4, '09:00:00', '10:00:00', 1),
(5, '10:00:00', '11:00:00', 2),
(6, '11:00:00', '12:00:00', 2),
(7, '12:00:00', '13:00:00', 2),
(8, '13:00:00', '14:00:00', 2),
(9, '14:00:00', '15:00:00', 2),
(10, '15:00:00', '16:00:00', 3),
(11, '16:00:00', '17:00:00', 3),
(12, '17:00:00', '18:00:00', 3),
(13, '18:00:00', '19:00:00', 4),
(14, '19:00:00', '20:00:00', 4),
(15, '20:00:00', '21:00:00', 4),
(16, '21:00:00', '22:00:00', 4),
(17, '22:00:00', '23:00:00', 4),
(18, '23:00:00', '24:00:00', 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `manager`
--

DROP TABLE IF EXISTS `manager`;
CREATE TABLE IF NOT EXISTS `manager` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `manager_user_id_fk` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `manager`
--

INSERT INTO `manager` (`id`, `name`, `phone`, `user_id`) VALUES
(1, 'Võ Tiến', '0123456789', 2),
(2, 'GiapGiap', '0145678923', 3),
(3, 'HieuLam', '0987654321', 4),
(4, 'Hiển', '0707719300', 7);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `match`
--

DROP TABLE IF EXISTS `match`;
CREATE TABLE IF NOT EXISTS `match` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `pitch_detail_id` int(11) NOT NULL,
  `winner` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referee` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `match_pitch_detail_id_fk` (`pitch_detail_id`)
) ENGINE=InnoDB AUTO_INCREMENT=181 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `match`
--

INSERT INTO `match` (`id`, `name`, `date`, `pitch_detail_id`, `winner`, `referee`) VALUES
(18, 'D20_TH01 vs D20_TH10', '2024-08-14', 1, 'D20_TH10', 'Tài'),
(19, 'D20_TH11 vs D21_TH01', '2024-08-17', 1, 'D20_TH11', 'Tài'),
(20, 'D20_TH03 vs ', '2024-08-09', 1, 'D20_TH03', 'Tài'),
(154, 'D21_TH03 vs D21_TH06', '2024-08-10', 1, 'D21_TH03', 'Tài'),
(155, 'D21_TH03 vs D21_TH07', '2024-08-29', 15, 'Hòa', 'Tài'),
(156, 'D21_TH03 vs D21_TH04', '2024-08-11', 1, 'Hoà', 'Tài'),
(157, 'D21_TH03 vs D21_TH05', '2024-08-14', 1, 'Hòa', 'Tài'),
(158, 'D21_TH06 vs D21_TH07', '2024-08-09', 1, NULL, 'Tài'),
(159, 'D21_TH06 vs D21_TH04', '2024-08-09', 1, NULL, 'Tài'),
(160, 'D21_TH06 vs D21_TH05', '2024-08-09', 1, NULL, 'Tài'),
(161, 'D21_TH07 vs D21_TH04', '2024-08-09', 1, NULL, 'Tài'),
(162, 'D21_TH07 vs D21_TH05', '2024-08-09', 1, NULL, 'Tài'),
(163, 'D21_TH04 vs D21_TH05', '2024-08-09', 1, NULL, 'Tài'),
(164, 'D21_TH08 vs D21_TH10', '2024-08-09', 1, NULL, 'Tài'),
(165, 'D21_TH08 vs D21_TH09', '2024-08-09', 1, NULL, 'Tài'),
(166, 'D21_TH08 vs D21_TH02', '2024-08-09', 1, NULL, 'Tài'),
(167, 'D21_TH10 vs D21_TH09', '2024-08-09', 1, NULL, 'Tài'),
(168, 'D21_TH10 vs D21_TH02', '2024-08-09', 1, NULL, 'Tài'),
(169, 'D21_TH09 vs D21_TH02', '2024-08-09', 1, NULL, 'Tài'),
(170, 'D20_TH11 vs D20_TH10', '2024-08-09', 1, NULL, 'Tài'),
(173, 'D20_TH11 vs D20_TH10', '2024-08-17', 16, 'D20_TH11', 'Tài'),
(174, 'D20_TH03 vs ', '2024-08-10', 1, 'D20_TH03', 'Tài'),
(175, 'D21_TH03 vs D21_TH04', '2024-08-10', 1, NULL, 'Tài'),
(176, 'D21_TH04 vs D21_TH02', '2024-08-10', 1, NULL, 'Tài'),
(178, 'D20_TH11 vs D20_TH03', '2024-08-11', 1, NULL, 'Tài'),
(179, 'D20_TH11 vs D20_TH03', '2024-08-11', 1, NULL, 'Tài'),
(180, 'D20_TH11 vs D20_TH03', '2024-08-11', 1, 'D20_TH11', 'Tài');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `match_detail`
--

DROP TABLE IF EXISTS `match_detail`;
CREATE TABLE IF NOT EXISTS `match_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `score` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `match_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `match_detail_team_id_fk` (`team_id`),
  KEY `match_detail_match_id_fk` (`match_id`)
) ENGINE=InnoDB AUTO_INCREMENT=330 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `match_detail`
--

INSERT INTO `match_detail` (`id`, `score`, `team_id`, `match_id`) VALUES
(31, 1, 4, 18),
(32, 3, 2, 18),
(33, 5, 1, 19),
(34, 4, 5, 19),
(35, 3, 3, 20),
(280, 3, 7, 154),
(281, 0, 10, 154),
(282, 0, 7, 155),
(283, 0, 11, 155),
(284, 1, 7, 156),
(285, 1, 8, 156),
(286, 0, 7, 157),
(287, 0, 9, 157),
(288, 0, 10, 158),
(289, 0, 11, 158),
(290, 0, 10, 159),
(291, 0, 8, 159),
(292, 0, 10, 160),
(293, 0, 9, 160),
(294, 0, 11, 161),
(295, 0, 8, 161),
(296, 0, 11, 162),
(297, 0, 9, 162),
(298, 0, 8, 163),
(299, 0, 9, 163),
(300, 0, 7, 164),
(301, 0, 10, 164),
(302, 0, 7, 165),
(303, 0, 11, 165),
(304, 0, 7, 166),
(305, 0, 8, 166),
(306, 0, 10, 167),
(307, 0, 11, 167),
(308, 0, 10, 168),
(309, 0, 8, 168),
(310, 0, 11, 169),
(311, 0, 8, 169),
(315, 1, 1, 173),
(316, 0, 2, 173),
(317, 3, 3, 174),
(318, 0, 7, 175),
(319, 0, 8, 175),
(320, 0, 8, 176),
(321, 0, 6, 176),
(328, 1, 1, 180),
(329, 0, 3, 180);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `period`
--

DROP TABLE IF EXISTS `period`;
CREATE TABLE IF NOT EXISTS `period` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time_period` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `period`
--

INSERT INTO `period` (`id`, `name`, `time_period`) VALUES
(1, 'morning', '6:00 - 10:00'),
(2, 'afternoon', '11:00 - 17:00'),
(3, 'evening', '18:00 - 20:00'),
(4, 'midnight', '21:00 - 24:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pitch`
--

DROP TABLE IF EXISTS `pitch`;
CREATE TABLE IF NOT EXISTS `pitch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `manager_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pitch_manager_id_fk` (`manager_id`),
  KEY `pitch_type_id_fk` (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `pitch`
--

INSERT INTO `pitch` (`id`, `name`, `deleted`, `description`, `manager_id`, `type_id`) VALUES
(1, 'Sân đá giải  11 ', 1, 'Giải bóng đá thường niên STU 24-25', 2, 3),
(2, 'sân số 1', 0, '', 1, 1),
(3, 'sân số Hai', 0, '', 1, 1),
(4, 'sân số 3', 0, '', 4, 1),
(6, 'Sân số 5', 0, '', 3, 2),
(7, 'Sân số 6', 0, '', 1, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pitch_detail`
--

DROP TABLE IF EXISTS `pitch_detail`;
CREATE TABLE IF NOT EXISTS `pitch_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pitch_id` int(11) NOT NULL,
  `duration_id` int(11) NOT NULL,
  `price_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pitch_detail_pitch_id_fk` (`pitch_id`),
  KEY `pitch_detail_duration_id_fk` (`duration_id`),
  KEY `pitch_detail_price_id_fk` (`price_id`)
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `pitch_detail`
--

INSERT INTO `pitch_detail` (`id`, `pitch_id`, `duration_id`, `price_id`) VALUES
(1, 1, 1, 9),
(2, 1, 2, 9),
(3, 1, 3, 9),
(4, 1, 4, 9),
(5, 1, 5, 10),
(6, 1, 6, 10),
(7, 1, 7, 10),
(8, 1, 8, 10),
(9, 1, 9, 10),
(10, 1, 10, 11),
(11, 1, 11, 11),
(12, 1, 12, 11),
(13, 1, 13, 12),
(14, 1, 14, 12),
(15, 1, 15, 12),
(16, 1, 16, 12),
(17, 1, 17, 12),
(18, 1, 18, 12),
(19, 2, 1, 1),
(20, 2, 2, 1),
(21, 2, 3, 1),
(22, 2, 4, 1),
(23, 2, 5, 2),
(24, 2, 6, 2),
(25, 2, 7, 2),
(26, 2, 8, 2),
(27, 2, 9, 2),
(28, 2, 10, 3),
(29, 2, 11, 3),
(30, 2, 12, 3),
(31, 2, 13, 4),
(32, 2, 14, 4),
(33, 2, 15, 4),
(34, 2, 16, 4),
(35, 2, 17, 4),
(36, 2, 18, 4),
(37, 3, 1, 5),
(38, 3, 2, 5),
(39, 3, 3, 5),
(40, 3, 4, 5),
(41, 3, 5, 6),
(42, 3, 6, 6),
(43, 3, 7, 6),
(44, 3, 8, 6),
(45, 3, 9, 6),
(46, 3, 10, 7),
(47, 3, 11, 7),
(48, 3, 12, 7),
(49, 3, 13, 8),
(50, 3, 14, 8),
(51, 3, 15, 8),
(52, 3, 16, 8),
(53, 3, 17, 8),
(54, 3, 18, 8),
(55, 4, 1, 1),
(56, 4, 2, 1),
(57, 4, 3, 1),
(58, 4, 4, 1),
(59, 4, 5, 2),
(60, 4, 6, 2),
(61, 4, 7, 2),
(62, 4, 8, 2),
(63, 4, 9, 2),
(64, 4, 10, 3),
(65, 4, 11, 3),
(66, 4, 12, 3),
(67, 4, 13, 4),
(68, 4, 14, 4),
(69, 4, 15, 4),
(70, 4, 16, 4),
(71, 4, 17, 4),
(72, 4, 18, 4),
(91, 6, 1, 5),
(92, 6, 2, 5),
(93, 6, 3, 5),
(94, 6, 4, 5),
(95, 6, 5, 6),
(96, 6, 6, 6),
(97, 6, 7, 6),
(98, 6, 8, 6),
(99, 6, 9, 6),
(100, 6, 10, 7),
(101, 6, 11, 7),
(102, 6, 12, 7),
(103, 6, 13, 8),
(104, 6, 14, 8),
(105, 6, 15, 8),
(106, 6, 16, 8),
(107, 6, 17, 8),
(108, 6, 18, 8),
(109, 7, 1, 1),
(110, 7, 2, 1),
(111, 7, 3, 1),
(112, 7, 4, 1),
(113, 7, 5, 2),
(114, 7, 6, 2),
(115, 7, 7, 2),
(116, 7, 8, 2),
(117, 7, 9, 2),
(118, 7, 10, 3),
(119, 7, 11, 3),
(120, 7, 12, 3),
(121, 7, 13, 4),
(122, 7, 14, 4),
(123, 7, 15, 4),
(124, 7, 16, 4),
(125, 7, 17, 4),
(126, 7, 18, 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `price`
--

DROP TABLE IF EXISTS `price`;
CREATE TABLE IF NOT EXISTS `price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `price_per_hour` decimal(10,3) NOT NULL,
  `period_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `price_period_id_fk` (`period_id`),
  KEY `price_type_id_fk` (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `price`
--

INSERT INTO `price` (`id`, `price_per_hour`, `period_id`, `type_id`) VALUES
(1, '100.000', 1, 1),
(2, '100.000', 2, 1),
(3, '150.000', 3, 1),
(4, '200.000', 4, 1),
(5, '150.000', 1, 2),
(6, '100.000', 2, 2),
(7, '250.000', 3, 2),
(8, '300.000', 4, 2),
(9, '200.000', 1, 3),
(10, '300.000', 2, 3),
(11, '500.000', 3, 3),
(12, '700.000', 4, 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `promotion`
--

DROP TABLE IF EXISTS `promotion`;
CREATE TABLE IF NOT EXISTS `promotion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_exp` date NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `discount` decimal(5,2) NOT NULL,
  `max_get` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `promotion`
--

INSERT INTO `promotion` (`id`, `name`, `date_exp`, `deleted`, `discount`, `max_get`) VALUES
(1, 'SAIGONTOIYEU', '2024-08-11', 0, '10.00', 50000),
(2, 'ILOVESAIGON2024', '2024-08-30', 0, '5.00', 100000),
(3, 'JAWFLRHXLD2024', '2024-07-30', 0, '10.00', 50000),
(4, 'ABCACB', '2024-08-31', 0, '10.00', 20000);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `role`
--

INSERT INTO `role` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'manager'),
(3, 'customer');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `status`
--

DROP TABLE IF EXISTS `status`;
CREATE TABLE IF NOT EXISTS `status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `status`
--

INSERT INTO `status` (`id`, `name`) VALUES
(1, 'Created'),
(2, 'Canceled'),
(3, 'Done');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `team`
--

DROP TABLE IF EXISTS `team`;
CREATE TABLE IF NOT EXISTS `team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `info_team` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isWinner` int(11) NOT NULL DEFAULT '0',
  `point` int(11) DEFAULT '0',
  `tournament_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `team_tournament_id_fk` (`tournament_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `team`
--

INSERT INTO `team` (`id`, `name`, `deleted`, `info_team`, `group`, `isWinner`, `point`, `tournament_id`) VALUES
(1, 'D20_TH11', 0, NULL, NULL, 1, 0, 1),
(2, 'D20_TH10', 0, NULL, NULL, 0, 0, 1),
(3, 'D20_TH03', 0, NULL, NULL, 0, 0, 1),
(4, 'D20_TH01', 0, NULL, NULL, 0, 0, 1),
(5, 'D21_TH01', 0, NULL, NULL, 0, 0, 1),
(6, 'D21_TH02', 0, NULL, 'B', 1, 3, 4),
(7, 'D21_TH03', 0, NULL, 'A', 0, 7, 4),
(8, 'D21_TH04', 0, NULL, 'A', 0, 4, 4),
(9, 'D21_TH05', 0, NULL, 'A', 0, 1, 4),
(10, 'D21_TH06', 0, NULL, 'A', 0, 0, 4),
(11, 'D21_TH07', 0, NULL, 'A', 0, 2, 4),
(12, 'D21_TH08', 0, NULL, 'B', 0, 0, 4),
(13, 'D21_TH09', 0, NULL, 'B', 0, 0, 4),
(14, 'D21_TH10', 0, NULL, 'B', 0, 0, 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tournament`
--

DROP TABLE IF EXISTS `tournament`;
CREATE TABLE IF NOT EXISTS `tournament` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `start_day` date NOT NULL,
  `manager_id` int(11) NOT NULL,
  `type_tour_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tournament_manager_id_fk` (`manager_id`),
  KEY `tournament_type_tour_id` (`type_tour_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tournament`
--

INSERT INTO `tournament` (`id`, `name`, `deleted`, `start_day`, `manager_id`, `type_tour_id`) VALUES
(1, 'Giải sinh viên CNTT 24-25', 1, '2024-08-01', 2, 1),
(2, 'EPL', 0, '2024-07-23', 1, 2),
(3, 'LALIGA', 1, '2024-06-30', 1, 2),
(4, 'V League', 0, '2024-08-01', 3, 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `type`
--

DROP TABLE IF EXISTS `type`;
CREATE TABLE IF NOT EXISTS `type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `type`
--

INSERT INTO `type` (`id`, `name`) VALUES
(1, 'sân 5'),
(2, 'sân 7'),
(3, 'sân 11');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `type_tour`
--

DROP TABLE IF EXISTS `type_tour`;
CREATE TABLE IF NOT EXISTS `type_tour` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `type_tour`
--

INSERT INTO `type_tour` (`id`, `name`) VALUES
(1, 'Loại trực tiếp'),
(2, 'Xoay vòng');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) DEFAULT '0',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_role_id_fk` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `user`
--

INSERT INTO `user` (`id`, `deleted`, `email`, `password`, `role_id`) VALUES
(1, 0, 'admin@gmail.com', '21232f297a57a5a743894a0e4a801fc3', 1),
(2, 0, 'manager1@gmail.com', '202cb962ac59075b964b07152d234b70', 2),
(3, 0, 'manager2@gmail.com', '202cb962ac59075b964b07152d234b70', 2),
(4, 0, 'manager3@gmail.com', '202cb962ac59075b964b07152d234b70', 2),
(5, 0, 'meollo74@gmail.com', '1230f85672a0b50349ead2ea02ab9ff0', 3),
(6, 0, 'harlem@gmail.com', '1230f85672a0b50349ead2ea02ab9ff0', 3),
(7, 0, 'manager4@gmail.com', '1230f85672a0b50349ead2ea02ab9ff0', 2),
(8, 0, 'a', 'a', 3),
(9, 0, 'a', 'a', 3),
(10, 0, 'b', 'a', 3),
(11, 0, 'c', 'a', 3),
(12, 0, 'd', 'a', 3),
(13, 0, 'e', 'a', 3),
(14, 0, 'f', 'a', 3),
(15, 0, 'g', 'a', 3),
(16, 0, 'h', 'a', 3);

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_cus_id_fk` FOREIGN KEY (`cus_id`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `booking_pitch_detail_id_fk` FOREIGN KEY (`pitch_detail_id`) REFERENCES `pitch_detail` (`id`),
  ADD CONSTRAINT `booking_promotion_id_fk` FOREIGN KEY (`promotion_id`) REFERENCES `promotion` (`id`),
  ADD CONSTRAINT `booking_status_id_fk` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`);

--
-- Các ràng buộc cho bảng `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_team_id_fk` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`),
  ADD CONSTRAINT `customer_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Các ràng buộc cho bảng `duration`
--
ALTER TABLE `duration`
  ADD CONSTRAINT `duration_period_id_fk` FOREIGN KEY (`period_id`) REFERENCES `period` (`id`);

--
-- Các ràng buộc cho bảng `manager`
--
ALTER TABLE `manager`
  ADD CONSTRAINT `manager_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Các ràng buộc cho bảng `match`
--
ALTER TABLE `match`
  ADD CONSTRAINT `match_pitch_detail_id_fk` FOREIGN KEY (`pitch_detail_id`) REFERENCES `pitch_detail` (`id`);

--
-- Các ràng buộc cho bảng `match_detail`
--
ALTER TABLE `match_detail`
  ADD CONSTRAINT `match_detail_match_id_fk` FOREIGN KEY (`match_id`) REFERENCES `match` (`id`),
  ADD CONSTRAINT `match_detail_team_id_fk` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`);

--
-- Các ràng buộc cho bảng `pitch`
--
ALTER TABLE `pitch`
  ADD CONSTRAINT `pitch_manager_id_fk` FOREIGN KEY (`manager_id`) REFERENCES `manager` (`id`),
  ADD CONSTRAINT `pitch_type_id_fk` FOREIGN KEY (`type_id`) REFERENCES `type` (`id`);

--
-- Các ràng buộc cho bảng `pitch_detail`
--
ALTER TABLE `pitch_detail`
  ADD CONSTRAINT `pitch_detail_duration_id_fk` FOREIGN KEY (`duration_id`) REFERENCES `duration` (`id`),
  ADD CONSTRAINT `pitch_detail_pitch_id_fk` FOREIGN KEY (`pitch_id`) REFERENCES `pitch` (`id`),
  ADD CONSTRAINT `pitch_detail_price_id_fk` FOREIGN KEY (`price_id`) REFERENCES `price` (`id`);

--
-- Các ràng buộc cho bảng `price`
--
ALTER TABLE `price`
  ADD CONSTRAINT `price_period_id_fk` FOREIGN KEY (`period_id`) REFERENCES `period` (`id`),
  ADD CONSTRAINT `price_type_id_fk` FOREIGN KEY (`type_id`) REFERENCES `type` (`id`);

--
-- Các ràng buộc cho bảng `team`
--
ALTER TABLE `team`
  ADD CONSTRAINT `team_tournament_id_fk` FOREIGN KEY (`tournament_id`) REFERENCES `tournament` (`id`);

--
-- Các ràng buộc cho bảng `tournament`
--
ALTER TABLE `tournament`
  ADD CONSTRAINT `tournament_manager_id_fk` FOREIGN KEY (`manager_id`) REFERENCES `manager` (`id`),
  ADD CONSTRAINT `tournament_type_tour_id` FOREIGN KEY (`type_tour_id`) REFERENCES `type_tour` (`id`);

--
-- Các ràng buộc cho bảng `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_role_id_fk` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
