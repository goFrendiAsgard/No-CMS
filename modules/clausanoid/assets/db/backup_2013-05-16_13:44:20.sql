DROP TABLE IF EXISTS `cms_rel_com_pro`; 
DROP TABLE IF EXISTS `cms_productos`; 
DROP TABLE IF EXISTS `cms_componentes`; 

#
# TABLE STRUCTURE FOR: cms_componentes
#

CREATE TABLE `cms_componentes` (
  `num_int` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`num_int`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: cms_productos
#

CREATE TABLE `cms_productos` (
  `num_int` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`num_int`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: cms_rel_com_pro
#

CREATE TABLE `cms_rel_com_pro` (
  `id_rel` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_com` int(10) DEFAULT NULL,
  `id_pro` int(10) DEFAULT NULL,
  `quantity` int(10) DEFAULT NULL,
  PRIMARY KEY (`id_rel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

