
USE `tapia`;

LOCK TABLES `tag` WRITE;
/*!40000 ALTER TABLE `tag` DISABLE KEYS */;
INSERT INTO `tag` VALUES (1,'Iniciación'),(2,'Planificación'),(3,'Ejecución'),(4,'Cierre');
/*!40000 ALTER TABLE `tag` ENABLE KEYS */;
UNLOCK TABLES;

