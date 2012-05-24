
USE `tapia`;

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES ('admin','admin','pmainieri@gruposmartliving.com',1,'Pablo','Mainieri','Evergreen 123');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
