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


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `alueet`
--

--
-- Table structure for table `alue`
--

DROP TABLE IF EXISTS `alue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alue` (
  `alue_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alue_code` varchar(5) NOT NULL,
  `alue_detail` text,
  `alue_location` varchar(40) DEFAULT NULL,
  `alue_taloudet` tinyint(3) unsigned DEFAULT NULL,
  `alue_lastdate` date DEFAULT NULL,
  `alue_group` int(10) unsigned DEFAULT '0',
  `lainassa` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`alue_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `alue_events`
--

DROP TABLE IF EXISTS `alue_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alue_events` (
  `event_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_type` int(10) unsigned DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `event_user` int(10) unsigned DEFAULT NULL,
  `event_alue` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `alue_group`
--

DROP TABLE IF EXISTS `alue_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alue_group` (
  `group_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(20) DEFAULT NULL,
  `group_events` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `person`
--

DROP TABLE IF EXISTS `person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person` (
  `person_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `person_name` varchar(30) NOT NULL,
  `person_lastname` varchar(20) DEFAULT NULL,
  `person_group` int(10) unsigned DEFAULT NULL,
  `person_leader` tinyint(3) unsigned DEFAULT '0',
  `person_show` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`person_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;


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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
