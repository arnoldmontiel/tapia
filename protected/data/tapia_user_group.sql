
USE `tapia`;

LOCK TABLES `user_group` WRITE;
/*!40000 ALTER TABLE `user_group` DISABLE KEYS */;
INSERT INTO `user_group` VALUES (1,'Administrador',1,1,1,0,0,1),(2,'Arquitecto',0,0,1,0,0,0),(3,'Cliente',0,0,1,1,0,0),(4,'Programador',1,0,0,0,0,0),(5,'T�cnico',1,0,0,0,0,0),(6,'Mujer Cliente',0,0,0,0,0,0);
/*!40000 ALTER TABLE `user_group` ENABLE KEYS */;
UNLOCK TABLES;

