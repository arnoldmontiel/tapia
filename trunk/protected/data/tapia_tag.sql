
USE `tapia`;

LOCK TABLES `tag` WRITE;
/*!40000 ALTER TABLE `tag` DISABLE KEYS */;
INSERT INTO `tag` VALUES (1,'Problemas'),(2,'Terminado'),(3,'Solucionado'),(4,'Perdido'),(5,'Encontrado');
/*!40000 ALTER TABLE `tag` ENABLE KEYS */;
UNLOCK TABLES;

