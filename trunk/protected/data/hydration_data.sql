USE `tapia`;

LOCK TABLES `assignments` WRITE;
/*!40000 ALTER TABLE `assignments` DISABLE KEYS */;
INSERT INTO `assignments` VALUES ('Administrator','admin','','s:0:\"\";'),('Arquitect','pablo','','s:0:\"\";'),('Authority','admin','','s:0:\"\";'),('Customer','arnold','','s:0:\"\";'),('Customer','daniel','','s:0:\"\";'),('Customer','mariela','','s:0:\"\";'),('Customer','matias','','s:0:\"\";'),('Customer','mazer','','s:0:\"\";'),('Customer','silvana','','s:0:\"\";');
/*!40000 ALTER TABLE `assignments` ENABLE KEYS */;
UNLOCK TABLES;
USE `tapia`;

LOCK TABLES `itemchildren` WRITE;
/*!40000 ALTER TABLE `itemchildren` DISABLE KEYS */;
INSERT INTO `itemchildren` VALUES ('AlbumManage','AlbumAdmin'),('AlbumManage','AlbumCreate'),('AlbumManage','AlbumDelete'),('AlbumManage','AlbumIndex'),('Administrator','AlbumManage'),('Arquitect','AlbumManage'),('AlbumManage','AlbumUpdate'),('AlbumManage','AlbumView'),('AuditLoginManage','AuditLoginIndex'),('Administrator','AuditLoginManage'),('CustomerManage','CustomerAdmin'),('CustomerManage','CustomerAssign'),('CustomerManage','CustomerAssignment'),('CustomerManage','CustomerCreate'),('CustomerManage','CustomerDelete'),('CustomerManage','CustomerIndex'),('Administrator','CustomerManage'),('CustomerManage','CustomerUpdate'),('CustomerManage','CustomerView'),('ReviewManage','ReviewCreate'),('ReviewManage','ReviewDelete'),('ReviewManage','ReviewIndex'),('Administrator','ReviewManage'),('Arquitect','ReviewManage'),('Customer','ReviewManage'),('ReviewTypeManage','ReviewTypeAdmin'),('ReviewTypeManage','ReviewTypeCreate'),('ReviewTypeManage','ReviewTypeDelete'),('ReviewTypeManage','ReviewTypeIndex'),('Administrator','ReviewTypeManage'),('ReviewTypeManage','ReviewTypeUpdate'),('ReviewTypeManage','ReviewTypeView'),('ReviewManage','ReviewUpdate'),('ReviewManage','ReviewUpdateAlbum'),('ReviewManage','ReviewUpdateAlbumIE'),('ReviewManage','ReviewUpdateDocuments'),('ReviewManage','ReviewView'),('SiteManage','SiteIndex'),('Administrator','SiteManage'),('Arquitect','SiteManage'),('Customer','SiteManage'),('TagManage','TagAdmin'),('TagManage','TagCreate'),('TagManage','TagDelete'),('TagManage','TagIndex'),('Administrator','TagManage'),('TagManage','TagUpdate'),('TagManage','TagView'),('UserManage','UserAdmin'),('UserManage','UserCreate'),('UserManage','UserDelete'),('UserGroupManage','UserGroupAdmin'),('UserGroupManage','UserGroupCreate'),('UserGroupManage','UserGroupDelete'),('UserGroupManage','UserGroupIndex'),('Administrator','UserGroupManage'),('UserGroupManage','UserGroupUpdate'),('UserGroupManage','UserGroupView'),('UserManage','UserIndex'),('Administrator','UserManage'),('UserManage','UserUpdate'),('UserManage','UserView'),('WallManage','WallIndex'),('Administrator','WallManage');
/*!40000 ALTER TABLE `itemchildren` ENABLE KEYS */;
UNLOCK TABLES;

USE `tapia`;

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES ('Administrator',2,'','','s:0:\"\";'),('AlbumAdmin',0,'','','s:0:\"\";'),('AlbumCreate',0,'','','s:0:\"\";'),('AlbumDelete',0,'','','s:0:\"\";'),('AlbumIndex',0,'','','s:0:\"\";'),('AlbumManage',1,'','','s:0:\"\";'),('AlbumUpdate',0,'','','s:0:\"\";'),('AlbumView',0,'','','s:0:\"\";'),('Arquitect',2,'','','s:0:\"\";'),('AuditLoginIndex',0,'','','s:0:\"\";'),('AuditLoginManage',1,'','','s:0:\"\";'),('Authority',2,'','','s:0:\"\";'),('Customer',2,'','','s:0:\"\";'),('CustomerAdmin',0,'','','s:0:\"\";'),('CustomerAssign',0,'','','s:0:\"\";'),('CustomerAssignment',0,'','','s:0:\"\";'),('CustomerCreate',0,'','','s:0:\"\";'),('CustomerDelete',0,'','','s:0:\"\";'),('CustomerIndex',0,'','','s:0:\"\";'),('CustomerManage',1,'','','s:0:\"\";'),('CustomerUpdate',0,'','','s:0:\"\";'),('CustomerView',0,'','','s:0:\"\";'),('ReviewCreate',0,'','','s:0:\"\";'),('ReviewDelete',0,'','','s:0:\"\";'),('ReviewIndex',0,'','','s:0:\"\";'),('ReviewManage',1,'','','s:0:\"\";'),('ReviewTypeAdmin',0,'','','s:0:\"\";'),('ReviewTypeCreate',0,'','','s:0:\"\";'),('ReviewTypeDelete',0,'','','s:0:\"\";'),('ReviewTypeIndex',0,'','','s:0:\"\";'),('ReviewTypeManage',1,'','','s:0:\"\";'),('ReviewTypeUpdate',0,'','','s:0:\"\";'),('ReviewTypeView',0,'','','s:0:\"\";'),('ReviewUpdate',0,'','','s:0:\"\";'),('ReviewUpdateAlbum',0,'','','s:0:\"\";'),('ReviewUpdateAlbumIE',0,'','','s:0:\"\";'),('ReviewUpdateDocuments',0,'','','s:0:\"\";'),('ReviewView',0,'','','s:0:\"\";'),('SiteIndex',0,'','','s:0:\"\";'),('SiteManage',1,'','','s:0:\"\";'),('TagAdmin',0,'','','s:0:\"\";'),('TagCreate',0,'','','s:0:\"\";'),('TagDelete',0,'','','s:0:\"\";'),('TagIndex',0,'','','s:0:\"\";'),('TagManage',1,'','','s:0:\"\";'),('TagUpdate',0,'','','s:0:\"\";'),('TagView',0,'','','s:0:\"\";'),('UserAdmin',0,'','','s:0:\"\";'),('UserCreate',0,'','','s:0:\"\";'),('UserDelete',0,'','','s:0:\"\";'),('UserGroupAdmin',0,'','','s:0:\"\";'),('UserGroupCreate',0,'','','s:0:\"\";'),('UserGroupDelete',0,'','','s:0:\"\";'),('UserGroupIndex',0,'','','s:0:\"\";'),('UserGroupManage',1,'','','s:0:\"\";'),('UserGroupUpdate',0,'','','s:0:\"\";'),('UserGroupView',0,'','','s:0:\"\";'),('UserIndex',0,'','','s:0:\"\";'),('UserManage',1,'','','s:0:\"\";'),('UserUpdate',0,'','','s:0:\"\";'),('UserView',0,'','','s:0:\"\";'),('WallIndex',0,'','','s:0:\"\";'),('WallManage',1,'','','s:0:\"\";');
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;


USE `tapia`;

LOCK TABLES `multimedia_type` WRITE;
/*!40000 ALTER TABLE `multimedia_type` DISABLE KEYS */;
INSERT INTO `multimedia_type` VALUES (1,'Image'),(2,'Video'),(3,'PDF'),(4,'Autocad'),(5,'Word'),(6,'Excel');
/*!40000 ALTER TABLE `multimedia_type` ENABLE KEYS */;
UNLOCK TABLES;



USE `tapia`;

LOCK TABLES `review_type` WRITE;
/*!40000 ALTER TABLE `review_type` DISABLE KEYS */;
INSERT INTO `review_type` VALUES (1,'Revisión',0,0),(2,'Modificaciones Propuestas',0,0),(3,'Req Técnicos',0,0),(4,'Propuesta Inicial',0,1),(5,'Planos',0,0),(6,'Actas de Obra',0,0),(7,'Revisiones de Obra',0,0),(8,'Informe de Avance',0,0),(9,'Informe de Programación',1,0),(10,'Informe Técnico',1,0),(11,'Cierre de Obra',0,0),(12,'Estado de Cuentas',0,0),(13,'Alerta de Inconvenientes',1,0);
/*!40000 ALTER TABLE `review_type` ENABLE KEYS */;
UNLOCK TABLES;


USE `tapia`;

LOCK TABLES `tag` WRITE;
/*!40000 ALTER TABLE `tag` DISABLE KEYS */;
INSERT INTO `tag` VALUES (1,'Iniciación'),(2,'Planificación'),(3,'Ejecución'),(4,'Cierre');
/*!40000 ALTER TABLE `tag` ENABLE KEYS */;
UNLOCK TABLES;


USE `tapia`;

LOCK TABLES `user_group` WRITE;
/*!40000 ALTER TABLE `user_group` DISABLE KEYS */;
INSERT INTO `user_group` VALUES (1,'Administrador',1,1,1,0,0,1),(2,'Arquitecto',0,0,1,0,0,0),(3,'Cliente',0,0,1,1,0,0),(4,'Programador',1,0,0,0,0,0),(5,'Técnico',1,0,0,0,0,0),(6,'Mujer Cliente',0,0,0,0,0,0);
/*!40000 ALTER TABLE `user_group` ENABLE KEYS */;
UNLOCK TABLES;


USE `tapia`;

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES ('admin','admin4321','pmainieri@gruposmartliving.com',1,'Pablo','Mainieri','Evergreen 123','','','Este es el administrador'),('arnold','arnold','arnold@gmail.com',3,'Arnaldo','Montiel','','','','Este tipo es arnol, un tipo como cualquier otro, con muchas habilidades y tranquilamente puede ser un tipo normalmente normal, siempre y cuando sea todo como debe ser en el sentido de ser, o sea ser o no ser, esa es la cuestion'),('daniel','daniel','daniel@smartliving.com',5,'Daniel','Luna','','','',NULL),('mariela','mariela','marielan@jkjhb',6,'Mariela','Mazer','chateaux','hfjsh','',NULL),('matias','matias','matias@gmail.com',4,'Matias','Montiel','lala','45445435','',NULL),('mazer','mazer','mazer@gmail',3,'Diego','Mazer','Chateaux','234455','','Este es el que vive en el piso 32'),('pablo','pablo','pablo@gmail.com',2,'Pablo','Pedraza','',NULL,NULL,NULL),('silvana','silvana','silvana@smartliving.com',3,'Silvana','Santoni','Aguirre 41','4445557742','',NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

USE `tapia`;

LOCK TABLES `interest_power` WRITE;
/*!40000 ALTER TABLE `interest_power` DISABLE KEYS */;
INSERT INTO `interest_power` VALUES (1,'Nula',0,0,0,0),(2,'Media',1,0,0,1);
/*!40000 ALTER TABLE `interest_power` ENABLE KEYS */;
UNLOCK TABLES;

USE `tapia`;

LOCK TABLES `setting` WRITE;
/*!40000 ALTER TABLE `setting` DISABLE KEYS */;
INSERT INTO `setting` VALUES (1,15);
/*!40000 ALTER TABLE `setting` ENABLE KEYS */;
UNLOCK TABLES;

