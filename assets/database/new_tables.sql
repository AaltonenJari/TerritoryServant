-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 29, 2020 at 02:50 PM
-- Server version: 8.0.18
-- PHP Version: 7.4.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `setting_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_order_id` int(11) UNSIGNED NOT NULL,
  `setting_input_type` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `setting_input_id` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `setting_desc` varchar(40) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `setting_value` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `setting_admin` tinyint(1) UNSIGNED DEFAULT '0',
  PRIMARY KEY (`setting_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_username` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `user_password` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `user_firstname` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT ' ',
  `user_lastname` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT ' ',
  `user_email` varchar(60) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT ' ',
  `user_admin` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
