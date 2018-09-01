-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.30-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for dashboard
CREATE DATABASE IF NOT EXISTS `dashboard` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `dashboard`;

-- Dumping structure for table dashboard.loa
CREATE TABLE IF NOT EXISTS `loa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `userid` int(11) NOT NULL,
  `approverid` int(11) NOT NULL DEFAULT '0',
  `requestdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `leavedate` datetime NOT NULL,
  `returndate` int(11) NOT NULL,
  `reason` varchar(512) NOT NULL,
  `approved` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table dashboard.misc
CREATE TABLE IF NOT EXISTS `misc` (
  `companyMOTD` varchar(1024) NOT NULL DEFAULT 'This is the Message of the Day!',
  `companyNotice1` varchar(1024) NOT NULL,
  `companyNotice2` varchar(1024) NOT NULL,
  `companyNotice3` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table dashboard.users
CREATE TABLE IF NOT EXISTS `users` (
  `UserID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(50) NOT NULL DEFAULT 'INVALID_USERNAME',
  `Password` varchar(384) NOT NULL,
  `Email` varchar(84) NOT NULL,
  `PhoneNumber` varchar(50) NOT NULL,
  `Tech` int(11) NOT NULL DEFAULT '0',
  `Marketing` int(11) NOT NULL DEFAULT '0',
  `Animation` int(11) NOT NULL DEFAULT '0',
  `HumanResources` int(11) NOT NULL DEFAULT '0',
  `Exec` int(11) NOT NULL DEFAULT '0',
  `OnLeave` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
