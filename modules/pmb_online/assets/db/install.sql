CREATE TABLE `{{ complete_table_name:agama }}` (
  `id_Agama` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Kode_Agama` varchar(255),
  `Nama_Agama` varchar(255),
  PRIMARY KEY (`id_Agama`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:asal_info_stiki }}` (
  `id_Asal` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama_Info` varchar(255),
  PRIMARY KEY (`id_Asal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:jurusan_sma_smk }}` (
  `id_jurusan_SMA_SMK` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Kode_jurusan_SMA_SMK` varchar(255),
  `Nama_jurusan_SMA_SMK` varchar(255),
  PRIMARY KEY (`id_jurusan_SMA_SMK`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:mahasiswa }}` (
  `id_mhs` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `Nama_Mhs` varchar(255),
  `alamat` varchar(255),
  `jenis_kelamin` enum('Laki-Laki','Perempuan'),
  `anak_ke` smallint(20),
  `Jumlah_saudara_kandung` smallint(20),
  `tanggal_lahir` datetime,
  `tempat_Lahir` varchar(255),
  `Provinsi` bigint(19),
  `warga_negara` enum('WNI','WNA'),
  `id_agama` smallint(20),
  `SMA_SMK_asal` varchar(255),
  `id_jurusan_SMA_SMK` smallint(20),
  `total_nilai_UN` smallint(20),
  `No_telpon_HP` smallint(20),
  `Email` varchar(255),
  `Nama_Orang_Tua_Ibu` varchar(255),
  `alamat_orang_tua` varchar(255),
  `id_kota_orang_tua` smallint(20),
  `id_pekerjaan_ayah` smallint(20),
  `id_pekerjaan_ibu` smallint(20),
  `alamat_malang` varchar(255),
  `transkrip_nilai` varchar(255),
  `ID_info_stiki` smallint(20),
  `id_prodi` smallint(20),
  `No_telpon_HP_orang_tua` smallint(20),
  `user_id` int(10) unsigned, 
  PRIMARY KEY (`id_mhs`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:pekerjaan }}` (
  `id_Pekerjaan` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kode_Pekerjaan` varchar(255),
  `nama_Pekerjaan` varchar(255),
  PRIMARY KEY (`id_Pekerjaan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:prodi }}` (
  `id_Prodi` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Kode_Prodi` varchar(255),
  `nama_Prodi` varchar(255),
  `Jenjang_Prodi` varchar(20),
  PRIMARY KEY (`id_Prodi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:provinsi }}` (
  `id_Provinsi` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kode_Provinsi` varchar(255),
  `nama_Provinsi` varchar(255),
  PRIMARY KEY (`id_Provinsi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
