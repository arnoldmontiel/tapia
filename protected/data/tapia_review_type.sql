
USE `tapia`;

LOCK TABLES `review_type` WRITE;
/*!40000 ALTER TABLE `review_type` DISABLE KEYS */;
INSERT INTO `review_type` VALUES (1,'Revisión',0,0),(2,'Modificaciones Propuestas',0,0),(3,'Req Técnicos',0,0),(4,'Propuesta Inicial',0,1),(5,'Planos',0,0),(6,'Actas de Obra',0,0),(7,'Revisiones de Obra',0,0),(8,'Informe de Avance',0,0),(9,'Informe de Programación',1,0),(10,'Informe Técnico',1,0),(11,'Cierre de Obra',0,0),(12,'Estado de Cuentas',0,0),(13,'Alerta de Inconvenientes',1,0);
/*!40000 ALTER TABLE `review_type` ENABLE KEYS */;
UNLOCK TABLES;
