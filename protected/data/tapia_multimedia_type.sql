
USE `tapia`;

LOCK TABLES `multimedia_type` WRITE;
/*!40000 ALTER TABLE `multimedia_type` DISABLE KEYS */;
INSERT INTO `multimedia_type` VALUES (1,'Image'),(2,'Video'),(3,'PDF'),(4,'Autocad'),(5,'Word'),(6,'Excel');
/*!40000 ALTER TABLE `multimedia_type` ENABLE KEYS */;
UNLOCK TABLES;

