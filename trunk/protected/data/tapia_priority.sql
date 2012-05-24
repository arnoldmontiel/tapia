
USE `tapia`;

LOCK TABLES `priority` WRITE;
/*!40000 ALTER TABLE `priority` DISABLE KEYS */;
INSERT INTO `priority` VALUES (1,'Alta',5),(2,'Media-Alta',4),(3,'Media',3),(4,'Media-Baja',2),(5,'Baja',1);
/*!40000 ALTER TABLE `priority` ENABLE KEYS */;
UNLOCK TABLES;

