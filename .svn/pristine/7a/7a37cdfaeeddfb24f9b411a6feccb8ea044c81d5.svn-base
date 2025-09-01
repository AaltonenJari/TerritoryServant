-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 23, 2020 at 07:36 PM
-- Server version: 8.0.18
-- PHP Version: 7.4.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `alueet`
--

-- --------------------------------------------------------

--
-- Table structure for table `event_log`
--

DROP TABLE IF EXISTS `event_log`;
CREATE TABLE IF NOT EXISTS `event_log` (
  `log_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `log_event_id` int(10) UNSIGNED DEFAULT NULL,
  `log_event_type` int(10) UNSIGNED DEFAULT NULL,
  `log_event_date` date DEFAULT NULL,
  `log_event_person` int(10) UNSIGNED DEFAULT NULL,
  `log_event_terr` int(10) UNSIGNED DEFAULT NULL,
  `log_user_id` int(10) UNSIGNED DEFAULT NULL,
  `log_operation_code` tinyint(2) UNSIGNED NOT NULL DEFAULT '1',
  `log_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
