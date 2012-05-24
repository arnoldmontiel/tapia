
USE `tapia`;

LOCK TABLES `review_type` WRITE;
/*!40000 ALTER TABLE `review_type` DISABLE KEYS */;
INSERT INTO `review_type` VALUES (1,'Revisión'),(2,'Planifición');
/*!40000 ALTER TABLE `review_type` ENABLE KEYS */;
UNLOCK TABLES;

