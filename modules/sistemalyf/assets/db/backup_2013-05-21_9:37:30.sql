DROP TABLE IF EXISTS `cms_rel_pro_compuestos`; 
DROP TABLE IF EXISTS `cms_rel_com_pro`; 
DROP TABLE IF EXISTS `cms_productos_compuestos`; 
DROP TABLE IF EXISTS `cms_productos`; 
DROP TABLE IF EXISTS `cms_componentes`; 

#
# TABLE STRUCTURE FOR: cms_componentes
#

CREATE TABLE `cms_componentes` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: cms_productos
#

CREATE TABLE `cms_productos` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  PRIMARY KEY (`num_int_procom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: cms_rel_com_pro
#

CREATE TABLE `cms_rel_com_pro` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: cms_rel_pro_compuestos
#

CREATE TABLE `cms_rel_pro_compuestos` (
  `priority` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `relpro` bigint(19) DEFAULT NULL,
  `relprocom` bigint(19) DEFAULT NULL,
  `cantidad` double DEFAULT NULL,
  PRIMARY KEY (`priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

