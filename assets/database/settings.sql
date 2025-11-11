-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 13, 2025 at 02:34 PM
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
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_id`, `setting_order_id`, `setting_input_type`, `setting_input_id`, `setting_desc`, `setting_value`, `setting_admin`) VALUES
(1, 1, 'adminreadonly', 'congregationName', 'Seurakunnan nimi:', 'Kankaanpää', 0),
(2, 2, 'adminreadonly', 'congregationNumber', 'Seurakunnan numero:', '38703', 0),
(4, 11, 'dropbox', 'namePresentation', 'Nimen esitysmuoto:', '1', 0),
(5, 12, 'dropbox', 'eventOrder', 'Tapahtumamerkintöjen järjestys:', 'DESC', 0),
(6, 13, 'dropbox', 'archiveYears', 'Tapahtumamerkinnät ajalta korkeintaan:', '4', 0),
(7, 14, 'dropbox', 'btSwitch', 'Liikealueiden näyttäminen:', '0', 0),
(8, 15, 'dropbox', 'eventSaveSwitch', 'Tapahtumamerkintöjen tallennustapa:', '0', 0),
(9, 16, 'date', 'circuitWeekStart', 'Kierrosviikko alkaa:', '8.12.2020', 0),
(10, 17, 'datereadonly', 'circuitWeekEnd', 'Kierrosviikko päättyy:', '13.12.2020', 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;