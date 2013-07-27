CREATE TABLE `{{ complete_table_name:componentes }}` (
  `num_int_com` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `num_ext` varchar(30),
  `num_parte` varchar(50),
  `descripcion` text,
  `precio_compra` decimal,
  `precio_venta` decimal,
  `proveedor` varchar(50),
  `ubicacion_bodega` text,
  `imagen` varchar(255),
  PRIMARY KEY (`num_int_com`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:productos }}` (
  `num_int_pro` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `num_ext` varchar(30),
  `num_parte` varchar(50),
  `descripcion` text,
  `precio_compra` decimal,
  `precio_venta` decimal,
  `ubicacion_bodega` text,
  `imagen` varchar(255),
  PRIMARY KEY (`num_int_pro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:productos_compuestos }}` (
  `num_int_procom` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `num_ext` varchar(30),
  `num_parte` varchar(50),
  `descripcion` text,
  `precio_compra` decimal,
  `precio_venta` decimal,
  `ubicacion_bodega` text,
  `imagen` varchar(255),
  PRIMARY KEY (`num_int_procom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:rel_com_pro }}` (
  `priority` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `relcom` bigint(19),
  `relpro` bigint(19),
  `cantidad` double,
  PRIMARY KEY (`priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:rel_pro_compuestos }}` (
  `priority` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `relpro` bigint(19),
  `relprocom` bigint(19),
  `cantidad` double,
  PRIMARY KEY (`priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;