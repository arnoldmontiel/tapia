CREATE DATABASE  IF NOT EXISTS `tapia` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `tapia`;
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
-- Dumping data for table `itemchildren`
--

LOCK TABLES `itemchildren` WRITE;
/*!40000 ALTER TABLE `itemchildren` DISABLE KEYS */;
INSERT INTO `itemchildren` VALUES ('AlbumManage','AlbumAdmin'),('AlbumManage','AlbumCreate'),('AlbumManage','AlbumDelete'),('AlbumManage','AlbumIndex'),('Administrator','AlbumManage'),('Arquitect','AlbumManage'),('AlbumManage','AlbumUpdate'),('AlbumManage','AlbumView'),('CustomerManage','CustomerAdmin'),('CustomerManage','CustomerAssign'),('CustomerManage','CustomerAssignment'),('CustomerManage','CustomerCreate'),('CustomerManage','CustomerDelete'),('CustomerManage','CustomerIndex'),('Administrator','CustomerManage'),('CustomerManage','CustomerUpdate'),('CustomerManage','CustomerView'),('ReviewManage','ReviewCreate'),('ReviewManage','ReviewDelete'),('ReviewManage','ReviewIndex'),('Administrator','ReviewManage'),('Arquitect','ReviewManage'),('Customer','ReviewManage'),('ReviewTypeManage','ReviewTypeAdmin'),('ReviewTypeManage','ReviewTypeCreate'),('ReviewTypeManage','ReviewTypeDelete'),('ReviewTypeManage','ReviewTypeIndex'),('Administrator','ReviewTypeManage'),('ReviewTypeManage','ReviewTypeUpdate'),('ReviewTypeManage','ReviewTypeView'),('ReviewManage','ReviewUpdate'),('ReviewManage','ReviewUpdateAlbum'),('ReviewManage','ReviewUpdateDocuments'),('ReviewManage','ReviewView'),('SiteManage','SiteIndex'),('Administrator','SiteManage'),('Arquitect','SiteManage'),('Customer','SiteManage'),('TagManage','TagAdmin'),('TagManage','TagCreate'),('TagManage','TagDelete'),('TagManage','TagIndex'),('Administrator','TagManage'),('TagManage','TagUpdate'),('TagManage','TagView'),('UserManage','UserAdmin'),('UserManage','UserCreate'),('UserManage','UserDelete'),('UserGroupManage','UserGroupAdmin'),('UserGroupManage','UserGroupCreate'),('UserGroupManage','UserGroupDelete'),('UserGroupManage','UserGroupIndex'),('Administrator','UserGroupManage'),('UserGroupManage','UserGroupUpdate'),('UserGroupManage','UserGroupView'),('UserManage','UserIndex'),('Administrator','UserManage'),('UserManage','UserUpdate'),('UserManage','UserView'),('WallManage','WallIndex'),('Administrator','WallManage');
/*!40000 ALTER TABLE `itemchildren` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-05-16 12:28:58
