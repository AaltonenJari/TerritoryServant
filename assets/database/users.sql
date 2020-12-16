-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 26, 2020 at 09:34 AM
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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_username`, `user_password`, `user_firstname`, `user_lastname`, `user_email`, `user_admin`) VALUES
(1, 'alue', 'alue', 'alue', '', ' ', 0),
(2, 'sali', 'sali', 'sali', ' ', ' ', 0),
(3, 'Jartsa', 'Ge30', 'Jari', 'Aaltonen', 'jari.aaltonen@gmail.com', 1),
(4, 'Enska', 'alue', 'Ensio', 'Lähteenmäki', '', 1),
(5, 'Reima', '3Mo4', 'Reima', 'Karvonen', '', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
