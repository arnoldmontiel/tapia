-- MySQL dump 10.13  Distrib 5.5.15, for Win32 (x86)
--
-- Host: 127.0.0.1    Database: tapia
-- ------------------------------------------------------
-- Server version	5.1.33-community

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `tapia`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `tapia` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `tapia`;

--
-- Table structure for table `album`
--

DROP TABLE IF EXISTS `album`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `album` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) DEFAULT NULL,
  `description` text,
  `creation_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Id_customer` int(11) NOT NULL,
  `Id_review` int(11) NOT NULL,
  `username` varchar(128) NOT NULL,
  `Id_user_group_owner` int(11) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `fk_album_customer1` (`Id_customer`),
  KEY `fk_album_review1` (`Id_review`),
  KEY `fk_album_user1` (`username`),
  KEY `fk_album_user_group1` (`Id_user_group_owner`),
  CONSTRAINT `fk_album_customer1` FOREIGN KEY (`Id_customer`) REFERENCES `customer` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_album_review1` FOREIGN KEY (`Id_review`) REFERENCES `review` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_album_user1` FOREIGN KEY (`username`) REFERENCES `user` (`username`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_album_user_group1` FOREIGN KEY (`Id_user_group_owner`) REFERENCES `user_group` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `album_note`
--

DROP TABLE IF EXISTS `album_note`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `album_note` (
  `Id_note` int(11) NOT NULL,
  `Id_album` int(11) NOT NULL,
  PRIMARY KEY (`Id_note`,`Id_album`),
  KEY `fk_note_has_album_album1` (`Id_album`),
  KEY `fk_note_has_album_note1` (`Id_note`),
  CONSTRAINT `fk_note_has_album_album1` FOREIGN KEY (`Id_album`) REFERENCES `album` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_note_has_album_note1` FOREIGN KEY (`Id_note`) REFERENCES `note` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `assignments`
--

DROP TABLE IF EXISTS `assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assignments` (
  `itemname` varchar(64) NOT NULL,
  `userid` varchar(64) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `username` varchar(128) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `fk_customer_user` (`username`),
  CONSTRAINT `fk_customer_user` FOREIGN KEY (`username`) REFERENCES `user` (`username`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `itemchildren`
--

DROP TABLE IF EXISTS `itemchildren`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `itemchildren` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `multimedia`
--

DROP TABLE IF EXISTS `multimedia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `multimedia` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) DEFAULT NULL,
  `size` float DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `file_name_small` varchar(255) DEFAULT NULL,
  `size_small` float DEFAULT NULL,
  `Id_multimedia_type` int(11) NOT NULL,
  `Id_customer` int(11) NOT NULL,
  `creation_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Id_album` int(11) DEFAULT NULL,
  `width` int(11) DEFAULT '0',
  `height` int(11) DEFAULT '0',
  `width_small` int(11) DEFAULT '0',
  `height_small` int(11) DEFAULT '0',
  `Id_review` int(11) NOT NULL,
  `username` varchar(128) NOT NULL,
  `Id_user_group` int(11) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `fk_multimedia_multimedia_type1` (`Id_multimedia_type`),
  KEY `fk_multimedia_album1` (`Id_album`),
  KEY `fk_multimedia_customer1` (`Id_customer`),
  KEY `fk_multimedia_review1` (`Id_review`),
  KEY `fk_multimedia_user1` (`username`),
  KEY `fk_multimedia_user_group1` (`Id_user_group`),
  CONSTRAINT `fk_multimedia_album1` FOREIGN KEY (`Id_album`) REFERENCES `album` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_multimedia_customer1` FOREIGN KEY (`Id_customer`) REFERENCES `customer` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_multimedia_multimedia_type1` FOREIGN KEY (`Id_multimedia_type`) REFERENCES `multimedia_type` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_multimedia_review1` FOREIGN KEY (`Id_review`) REFERENCES `review` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_multimedia_user1` FOREIGN KEY (`username`) REFERENCES `user` (`username`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_multimedia_user_group1` FOREIGN KEY (`Id_user_group`) REFERENCES `user_group` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=399 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `multimedia_note`
--

DROP TABLE IF EXISTS `multimedia_note`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `multimedia_note` (
  `Id_note` int(11) NOT NULL,
  `Id_multimedia` int(11) NOT NULL,
  PRIMARY KEY (`Id_note`,`Id_multimedia`),
  KEY `fk_multimedia_note_m1` (`Id_multimedia`),
  KEY `fk_note_has_multimedia_note1` (`Id_note`),
  CONSTRAINT `fk_multimedia_note_m1` FOREIGN KEY (`Id_multimedia`) REFERENCES `multimedia` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_note_has_multimedia_note1` FOREIGN KEY (`Id_note`) REFERENCES `note` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `multimedia_type`
--

DROP TABLE IF EXISTS `multimedia_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `multimedia_type` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `note`
--

DROP TABLE IF EXISTS `note`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `note` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `note` text,
  `creation_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Id_customer` int(11) NOT NULL,
  `Id_review` int(11) DEFAULT NULL,
  `in_progress` tinyint(1) DEFAULT '1',
  `need_confirmation` tinyint(1) DEFAULT '0',
  `confirmed` tinyint(1) DEFAULT '0',
  `username` varchar(128) NOT NULL,
  `Id_user_group_owner` int(11) NOT NULL,
  `change_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `fk_note_customer1` (`Id_customer`),
  KEY `fk_note_review1` (`Id_review`),
  KEY `fk_note_user1` (`username`),
  KEY `fk_note_user_group1` (`Id_user_group_owner`),
  CONSTRAINT `fk_note_customer1` FOREIGN KEY (`Id_customer`) REFERENCES `customer` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_note_review1` FOREIGN KEY (`Id_review`) REFERENCES `review` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_note_user1` FOREIGN KEY (`username`) REFERENCES `user` (`username`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_note_user_group1` FOREIGN KEY (`Id_user_group_owner`) REFERENCES `user_group` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=510 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `note_note`
--

DROP TABLE IF EXISTS `note_note`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `note_note` (
  `Id_parent` int(11) NOT NULL,
  `Id_child` int(11) NOT NULL,
  PRIMARY KEY (`Id_parent`,`Id_child`),
  KEY `fk_note_has_note_note2` (`Id_child`),
  KEY `fk_note_has_note_note1` (`Id_parent`),
  CONSTRAINT `fk_note_has_note_note1` FOREIGN KEY (`Id_parent`) REFERENCES `note` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_note_has_note_note2` FOREIGN KEY (`Id_child`) REFERENCES `note` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `priority`
--

DROP TABLE IF EXISTS `priority`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priority` (
  `Id` int(11) NOT NULL,
  `description` varchar(45) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `review`
--

DROP TABLE IF EXISTS `review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `review` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `review` int(11) DEFAULT NULL,
  `Id_customer` int(11) NOT NULL,
  `description` text,
  `creation_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `change_date` timestamp NULL DEFAULT NULL,
  `Id_priority` int(11) NOT NULL DEFAULT '1',
  `read` tinyint(1) DEFAULT '0',
  `Id_review_type` int(11) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `fk_review_customer1` (`Id_customer`),
  KEY `fk_review_priority1` (`Id_priority`),
  KEY `fk_review_review_type1` (`Id_review_type`),
  CONSTRAINT `fk_review_customer1` FOREIGN KEY (`Id_customer`) REFERENCES `customer` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_review_priority1` FOREIGN KEY (`Id_priority`) REFERENCES `priority` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_review_review_type1` FOREIGN KEY (`Id_review_type`) REFERENCES `review_type` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `review_type`
--

DROP TABLE IF EXISTS `review_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `review_type` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `review_user`
--

DROP TABLE IF EXISTS `review_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `review_user` (
  `Id_review` int(11) NOT NULL,
  `username` varchar(128) NOT NULL,
  `read` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`Id_review`,`username`),
  KEY `fk_review_has_user_user1` (`username`),
  KEY `fk_review_has_user_review1` (`Id_review`),
  CONSTRAINT `fk_review_has_user_review1` FOREIGN KEY (`Id_review`) REFERENCES `review` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_review_has_user_user1` FOREIGN KEY (`username`) REFERENCES `user` (`username`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tag`
--

DROP TABLE IF EXISTS `tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tag` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tag_review`
--

DROP TABLE IF EXISTS `tag_review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tag_review` (
  `Id_tag` int(11) NOT NULL,
  `Id_review` int(11) NOT NULL,
  PRIMARY KEY (`Id_tag`,`Id_review`),
  KEY `fk_tag_has_review_review1` (`Id_review`),
  KEY `fk_tag_has_review_tag1` (`Id_tag`),
  CONSTRAINT `fk_tag_has_review_review1` FOREIGN KEY (`Id_review`) REFERENCES `review` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tag_has_review_tag1` FOREIGN KEY (`Id_tag`) REFERENCES `tag` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `username` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `Id_user_group` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`username`),
  KEY `fk_user_user_group1` (`Id_user_group`),
  CONSTRAINT `fk_user_user_group1` FOREIGN KEY (`Id_user_group`) REFERENCES `user_group` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_customer`
--

DROP TABLE IF EXISTS `user_customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_customer` (
  `username` varchar(128) NOT NULL,
  `Id_customer` int(11) NOT NULL,
  PRIMARY KEY (`username`,`Id_customer`),
  KEY `fk_user_has_customer_customer1` (`Id_customer`),
  KEY `fk_user_has_customer_user1` (`username`),
  CONSTRAINT `fk_user_has_customer_user1` FOREIGN KEY (`username`) REFERENCES `user` (`username`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_has_customer_customer1` FOREIGN KEY (`Id_customer`) REFERENCES `customer` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_group`
--

DROP TABLE IF EXISTS `user_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_group` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) DEFAULT NULL,
  `can_create` tinyint(4) NOT NULL DEFAULT '0',
  `is_administrator` tinyint(4) NOT NULL DEFAULT '0',
  `can_read` tinyint(4) NOT NULL DEFAULT '0',
  `addressed` tinyint(4) NOT NULL DEFAULT '0',
  `need_confirmation` tinyint(4) NOT NULL DEFAULT '0',
  `can_feedback` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_group_note`
--

DROP TABLE IF EXISTS `user_group_note`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_group_note` (
  `Id_user_group` int(11) NOT NULL,
  `Id_note` int(11) NOT NULL,
  `Id_customer` int(11) NOT NULL,
  `can_read` tinyint(4) NOT NULL DEFAULT '0',
  `can_feedback` tinyint(4) NOT NULL DEFAULT '0',
  `addressed` tinyint(4) NOT NULL DEFAULT '0',
  `need_confirmation` tinyint(4) NOT NULL DEFAULT '0',
  `confirmed` tinyint(4) NOT NULL DEFAULT '0',
  `declined` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Id_user_group`,`Id_note`),
  KEY `fk_user_group_has_note_note1` (`Id_note`),
  KEY `fk_user_group_has_note_user_group1` (`Id_user_group`),
  KEY `fk_user_group_note_customer1` (`Id_customer`),
  CONSTRAINT `fk_user_group_has_note_note1` FOREIGN KEY (`Id_note`) REFERENCES `note` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_user_group_has_note_user_group1` FOREIGN KEY (`Id_user_group`) REFERENCES `user_group` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_group_note_customer1` FOREIGN KEY (`Id_customer`) REFERENCES `customer` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wall`
--

DROP TABLE IF EXISTS `wall`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wall` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Id_note` int(11) DEFAULT NULL,
  `Id_multimedia` int(11) DEFAULT NULL,
  `index_order` int(11) DEFAULT NULL,
  `Id_album` int(11) DEFAULT NULL,
  `Id_customer` int(11) NOT NULL,
  `Id_review` int(11) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `fk_wall_note1` (`Id_note`),
  KEY `fk_wall_multimedia1` (`Id_multimedia`),
  KEY `fk_wall_customer1` (`Id_customer`),
  KEY `fk_wall_album1` (`Id_album`),
  KEY `fk_wall_review1` (`Id_review`),
  CONSTRAINT `fk_wall_customer1` FOREIGN KEY (`Id_customer`) REFERENCES `customer` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_wall_album1` FOREIGN KEY (`Id_album`) REFERENCES `album` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_wall_multimedia1` FOREIGN KEY (`Id_multimedia`) REFERENCES `multimedia` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_wall_note1` FOREIGN KEY (`Id_note`) REFERENCES `note` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_wall_review1` FOREIGN KEY (`Id_review`) REFERENCES `review` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-05-24 10:31:05
