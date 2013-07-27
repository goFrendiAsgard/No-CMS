CREATE TABLE `{{ complete_table_name:barang }}` (
  `id_barang` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kode_barang` varchar(20),
  `nama` varchar(30),
  PRIMARY KEY (`id_barang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:kategori }}` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(30),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:kategori_barang }}` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_barang` int(10),
  `id_kategori` int(10),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;