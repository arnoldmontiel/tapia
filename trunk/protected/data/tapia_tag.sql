
USE `tapia`;

LOCK TABLES `tag` WRITE;
/*!40000 ALTER TABLE `tag` DISABLE KEYS */;
INSERT INTO `tag` VALUES (1,'Iniciaci�n'),(2,'Planificaci�n'),(3,'Ejecuci�n'),(4,'Cierre');
/*!40000 ALTER TABLE `tag` ENABLE KEYS */;
UNLOCK TABLES;

