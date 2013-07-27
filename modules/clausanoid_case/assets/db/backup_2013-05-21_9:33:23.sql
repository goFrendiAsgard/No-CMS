DROP TABLE IF EXISTS `cms_rel_com_pro`; 
DROP TABLE IF EXISTS `cms_productos`; 
DROP TABLE IF EXISTS `cms_componentes`; 

#
# TABLE STRUCTURE FOR: cms_componentes
#

CREATE TABLE `cms_componentes` (
  `num_int_com` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `num_ext` varchar(30) DEFAULT NULL,
  `num_parte` varchar(50) DEFAULT NULL,
  `descripcion` text,
  `precio_compra` decimal(10,0) DEFAULT NULL,
  `precio_venta` decimal(10,0) DEFAULT NULL,
  `proveedor` varchar(50) DEFAULT NULL,
  `ubicacion_bodega` text,
  `imagen` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`num_int_com`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO cms_componentes (`num_int_com`, `num_ext`, `num_parte`, `descripcion`, `precio_compra`, `precio_venta`, `proveedor`, `ubicacion_bodega`, `imagen`) VALUES (1, 'try', 'try', '<p>\n	try component</p>\n', '0', '0', 'fsafa', 'Caja-L-18', NULL);


#
# TABLE STRUCTURE FOR: cms_productos
#

CREATE TABLE `cms_productos` (
  `num_int_pro` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `num_ext` varchar(30) DEFAULT NULL,
  `num_parte` varchar(50) DEFAULT NULL,
  `descripcion` text,
  `precio_compra` decimal(10,0) DEFAULT NULL,
  `precio_venta` decimal(10,0) DEFAULT NULL,
  `ubicacion_bodega` text,
  `imagen` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`num_int_pro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: cms_rel_com_pro
#

CREATE TABLE `cms_rel_com_pro` (
  `priority` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `relcom` bigint(19) DEFAULT NULL,
  `relpro` bigint(19) DEFAULT NULL,
  `cantidad` double DEFAULT NULL,
  PRIMARY KEY (`priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

