-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server Version:               8.0.26 - MySQL Community Server - GPL
-- Server Betriebssystem:        Win64
-- HeidiSQL Version:             11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Exportiere Datenbank Struktur für seemlesslogin
CREATE DATABASE IF NOT EXISTS `seemlesslogin` /*!40100 DEFAULT CHARACTER SET utf8 */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `seemlesslogin`;

-- Exportiere Struktur von Tabelle seemlesslogin.sl_profiles
CREATE TABLE IF NOT EXISTS `sl_profiles` (
  `profiles_id` int NOT NULL,
  `profiles_firstname` varchar(500) DEFAULT '',
  `profiles_lastname` varchar(500) DEFAULT '',
  `profiles_email` varchar(500) DEFAULT '',
  `profiles_homepage` varchar(500) DEFAULT '',
  `profiles_dev` varchar(500) DEFAULT '',
  `soc_facebook` varchar(500) DEFAULT '',
  `soc_instagram` varchar(500) DEFAULT '',
  `soc_youtube` varchar(500) DEFAULT '',
  `soc_vimeo` varchar(500) DEFAULT '',
  `soc_xing` varchar(500) DEFAULT '',
  `soc_linkedin` varchar(500) DEFAULT '',
  `soc_pinterest` varchar(500) DEFAULT '',
  `soc_twitch` varchar(500) DEFAULT '',
  `soc_snapchat` varchar(500) DEFAULT '',
  `soc_tiktok` varchar(500) DEFAULT '',
  `soc_reddit` varchar(500) DEFAULT '',
  `soc_twitter` varchar(500) DEFAULT '',
  `soc_skype` varchar(500) DEFAULT '',
  `soc_microsoft` varchar(500) DEFAULT '',
  `soc_discord` varchar(500) DEFAULT '',
  `soc_whatsapp` varchar(500) DEFAULT '',
  `soc_telegram` varchar(500) DEFAULT '',
  `soc_signal` varchar(500) DEFAULT '',
  `soc_dropbox` varchar(500) DEFAULT '',
  `soc_gdrive` varchar(500) DEFAULT '',
  `pay_paypal` varchar(500) DEFAULT '',
  `pay_google` varchar(500) DEFAULT '',
  `pay_apple` varchar(500) DEFAULT '',
  `pay_amazon` varchar(500) DEFAULT '',
  `gms_origin` varchar(500) DEFAULT '',
  `gms_ea` varchar(500) DEFAULT '',
  `gms_steam` varchar(500) DEFAULT '',
  `gms_ubi` varchar(500) DEFAULT '',
  `gms_epic` varchar(500) DEFAULT '',
  `gms_playstation` varchar(500) DEFAULT '',
  PRIMARY KEY (`profiles_id`),
  CONSTRAINT `FK_sl_profiles_sl_users` FOREIGN KEY (`profiles_id`) REFERENCES `sl_users` (`users_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle seemlesslogin.sl_profiles_fields
CREATE TABLE IF NOT EXISTS `sl_profiles_fields` (
  `fields_id` int NOT NULL AUTO_INCREMENT,
  `fields_user` int DEFAULT NULL,
  `fields_name` varchar(250) DEFAULT NULL,
  `fields_value` longtext,
  PRIMARY KEY (`fields_id`),
  KEY `FK_sl_profiles_fields_sl_users` (`fields_user`),
  CONSTRAINT `FK_sl_profiles_fields_sl_users` FOREIGN KEY (`fields_user`) REFERENCES `sl_users` (`users_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle seemlesslogin.sl_sessions
CREATE TABLE IF NOT EXISTS `sl_sessions` (
  `sessions_id` varchar(250) NOT NULL,
  `sessions_userid` int DEFAULT NULL,
  `sessions_data` mediumtext,
  PRIMARY KEY (`sessions_id`),
  KEY `FK_sl_sessions_sl_users` (`sessions_userid`),
  CONSTRAINT `FK_sl_sessions_sl_users` FOREIGN KEY (`sessions_userid`) REFERENCES `sl_users` (`users_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle seemlesslogin.sl_users
CREATE TABLE IF NOT EXISTS `sl_users` (
  `users_id` int NOT NULL AUTO_INCREMENT,
  `users_sid` varchar(250) DEFAULT NULL,
  `users_username` varchar(250) DEFAULT NULL,
  `users_password` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`users_id`),
  UNIQUE KEY `users_username` (`users_username`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3;

-- Daten Export vom Benutzer nicht ausgewählt

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
