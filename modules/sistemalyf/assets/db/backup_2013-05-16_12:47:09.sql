DROP TABLE IF EXISTS `cms_rel_pro_compuestos`; 
DROP TABLE IF EXISTS `cms_rel_com_pro`; 
DROP TABLE IF EXISTS `cms_productos_compuestos`; 
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO cms_componentes (`num_int_com`, `num_ext`, `num_parte`, `descripcion`, `precio_compra`, `precio_venta`, `proveedor`, `ubicacion_bodega`, `imagen`) VALUES (4, 'd46', 'r1', '<p>\n	resistor</p>\n', '1', '2', 'digikey', '<p>\n	en una cajita</p>\n', NULL);


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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO cms_productos (`num_int_pro`, `num_ext`, `num_parte`, `descripcion`, `precio_compra`, `precio_venta`, `ubicacion_bodega`, `imagen`) VALUES (1, '12', '21', '<p>\n	21</p>\n', '12', '12', '<p>\n	21</p>\n', NULL);


#
# TABLE STRUCTURE FOR: cms_productos_compuestos
#

CREATE TABLE `cms_productos_compuestos` (
  `num_int_procom` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `num_ext` varchar(30) DEFAULT NULL,
  `num_parte` varchar(50) DEFAULT NULL,
  `descripcion` text,
  `precio_compra` decimal(10,0) DEFAULT NULL,
  `precio_venta` decimal(10,0) DEFAULT NULL,
  `ubicacion_bodega` text,
  `imagen` varchar(255) DEFAULT NULL,
  `relprocom` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`num_int_procom`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO cms_productos_compuestos (`num_int_procom`, `num_ext`, `num_parte`, `descripcion`, `precio_compra`, `precio_venta`, `ubicacion_bodega`, `imagen`, `relprocom`) VALUES (2, 'lkh98', 'lkhjasd98', '<p>\n	as&ntilde;jdiu</p>\n', '78', '45', '1,2,3,4,5,6', NULL, NULL);


#
# TABLE STRUCTURE FOR: cms_rel_com_pro
#

CREATE TABLE `cms_rel_com_pro` (
  `priority` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `relcom` bigint(19) DEFAULT NULL,
  `relpro` bigint(19) DEFAULT NULL,
  `cantidad` double DEFAULT NULL,
  PRIMARY KEY (`priority`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO cms_rel_com_pro (`priority`, `relcom`, `relpro`, `cantidad`) VALUES (1, 4, 1, '121');


#
# TABLE STRUCTURE FOR: cms_rel_pro_compuestos
#

CREATE TABLE `cms_rel_pro_compuestos` (
  `priority` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `relpro` bigint(19) DEFAULT NULL,
  `relprocom` bigint(19) DEFAULT NULL,
  `cantidad` double DEFAULT NULL,
  PRIMARY KEY (`priority`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO cms_rel_pro_compuestos (`priority`, `relpro`, `relprocom`, `cantidad`) VALUES (2, 1, 2, '2');
INSERT INTO cms_rel_pro_compuestos (`priority`, `relpro`, `relprocom`, `cantidad`) VALUES (3, 1, 2, '1');


