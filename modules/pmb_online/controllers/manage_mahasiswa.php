<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Manage_Mahasiswa
 *
 * @author No-CMS Module Generator
 */
class Manage_Mahasiswa extends CMS_Priv_Strict_Controller {

	protected $URL_MAP = array();

	public function index(){
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// initialize groceryCRUD
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $crud = new grocery_CRUD();
        $crud->unset_jquery();

        // set model
        $crud->set_model($this->cms_module_path().'/grocerycrud_mahasiswa_model');

        // adjust groceryCRUD's language to No-CMS's language
        $crud->set_language($this->cms_language());

        // table name
        $crud->set_table($this->cms_complete_table_name('mahasiswa'));

        // set subject
        $crud->set_subject('Mahasiswa');

        // displayed columns on list
        $crud->columns('Nama_Mhs','alamat','jenis_kelamin','anak_ke','Jumlah_saudara_kandung','tanggal_lahir','tempat_Lahir','Provinsi','warga_negara','id_agama','SMA_SMK_asal','id_jurusan_SMA_SMK','total_nilai_UN','No_telpon_HP','Email','Nama_Orang_Tua_Ibu','alamat_orang_tua','id_kota_orang_tua','id_pekerjaan_ayah','id_pekerjaan_ibu','alamat_malang','transkrip_nilai','ID_info_stiki','id_prodi','No_telpon_HP_orang_tua');
        // displayed columns on edit operation
        $crud->edit_fields('Nama_Mhs','alamat','jenis_kelamin','anak_ke','Jumlah_saudara_kandung','tanggal_lahir','tempat_Lahir','Provinsi','warga_negara','id_agama','SMA_SMK_asal','id_jurusan_SMA_SMK','total_nilai_UN','No_telpon_HP','Email','Nama_Orang_Tua_Ibu','alamat_orang_tua','id_kota_orang_tua','id_pekerjaan_ayah','id_pekerjaan_ibu','alamat_malang','transkrip_nilai','ID_info_stiki','id_prodi','No_telpon_HP_orang_tua');
        // displayed columns on add operation
        $crud->add_fields('Nama_Mhs','alamat','jenis_kelamin','anak_ke','Jumlah_saudara_kandung','tanggal_lahir','tempat_Lahir','Provinsi','warga_negara','id_agama','SMA_SMK_asal','id_jurusan_SMA_SMK','total_nilai_UN','No_telpon_HP','Email','Nama_Orang_Tua_Ibu','alamat_orang_tua','id_kota_orang_tua','id_pekerjaan_ayah','id_pekerjaan_ibu','alamat_malang','transkrip_nilai','ID_info_stiki','id_prodi','No_telpon_HP_orang_tua');

        // caption of each columns
        $crud->display_as('Nama_Mhs','Nama Lengkap');
        $crud->display_as('alamat','Alamat');
        $crud->display_as('jenis_kelamin','Jenis Kelamin');
        $crud->display_as('anak_ke','Anak Ke');
        $crud->display_as('Jumlah_saudara_kandung','Jumlah Saudara Kandung');
        $crud->display_as('tanggal_lahir','Tanggal Lahir');
        $crud->display_as('tempat_Lahir','Tempat Lahir');
        $crud->display_as('Provinsi','Provinsi');
        $crud->display_as('warga_negara','Warga Negara');
        $crud->display_as('id_agama','Agama');
        $crud->display_as('SMA_SMK_asal','SMA/SMK Asal');
        $crud->display_as('id_jurusan_SMA_SMK','Jurusan SMA/SMK');
        $crud->display_as('total_nilai_UN','Nilai UN');
        $crud->display_as('No_telpon_HP','No Telpon/HP');
        $crud->display_as('Email','Email');
        $crud->display_as('Nama_Orang_Tua_Ibu','Nama Orang Tua Ibu');
        $crud->display_as('alamat_orang_tua','Alamat Orang Tua');
        $crud->display_as('id_kota_orang_tua','Kota Orang Tua');
        $crud->display_as('id_pekerjaan_ayah','Pekerjaan Ayah');
        $crud->display_as('id_pekerjaan_ibu','Pekerjaan Ibu');
        $crud->display_as('alamat_malang','Alamat di Malang');
        $crud->display_as('transkrip_nilai','Transkrip Nilai');
        $crud->display_as('ID_info_stiki','Asal Informasi STIKI');
        $crud->display_as('id_prodi','Prodi');
        $crud->display_as('No_telpon_HP_orang_tua','No Telpon/HP Orang Tua');

		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// HINT: Put set relation (lookup) codes here
		// (documentation: http://www.grocerycrud.com/documentation/options_functions/set_relation)
		// eg:
		// 		$crud->set_relation( $field_name , $related_table, $related_title_field , $where , $order_by );
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$crud->set_relation('Provinsi', $this->cms_complete_table_name('provinsi'), 'nama_Provinsi');
		$crud->set_relation('id_agama', $this->cms_complete_table_name('agama'), 'Nama_Agama');
		$crud->set_relation('id_jurusan_SMA_SMK', $this->cms_complete_table_name('jurusan_sma_smk'), 'Nama_jurusan_SMA_SMK');
		$crud->set_relation('id_kota_orang_tua', $this->cms_complete_table_name('provinsi'), 'nama_Provinsi');
		$crud->set_relation('id_pekerjaan_ayah', $this->cms_complete_table_name('pekerjaan'), 'nama_Pekerjaan');
		$crud->set_relation('id_pekerjaan_ibu', $this->cms_complete_table_name('pekerjaan'), 'nama_Pekerjaan');
		$crud->set_relation('ID_info_stiki', $this->cms_complete_table_name('asal_info_stiki'), 'nama_Info');
		$crud->set_relation('id_prodi', $this->cms_complete_table_name('prodi'), 'nama_Prodi');

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// HINT: Put set relation_n_n (detail many to many) codes here
		// (documentation: http://www.grocerycrud.com/documentation/options_functions/set_relation_n_n)
		// eg:
		// 		$crud->set_relation_n_n( $field_name, $relation_table, $selection_table, $primary_key_alias_to_this_table,
		// 			$primary_key_alias_to_selection_table , $title_field_selection_table, $priority_field_relation );
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// HINT: Put custom field type here
		// (documentation: http://www.grocerycrud.com/documentation/options_functions/field_type)
		// eg:
		// 		$crud->field_type( $field_name , $field_type, $value  );
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$crud->field_type('jenis_kelamin', 'enum', array('Laki-Laki','Perempuan'));
		$crud->field_type('warga_negara', 'enum', array('WNI','WNA'));
		
		$crud->set_field_upload('transkrip_nilai','modules/pmb_online/assets/upload');
		
		 $crud->required_fields('Nama_Mhs','alamat','SMA_SMK_asal','Email','No_telpon_HP','id_prodi');

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// HINT: Put callback here
		// (documentation: httm://www.grocerycrud.com/documentation/options_functions)
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$crud->callback_before_insert(array($this,'before_insert'));
		$crud->callback_before_update(array($this,'before_update'));
		$crud->callback_before_delete(array($this,'before_delete'));
		$crud->callback_after_insert(array($this,'after_insert'));
		$crud->callback_after_update(array($this,'after_update'));
		$crud->callback_after_delete(array($this,'after_delete'));



        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // render
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $output = $crud->render();
        $this->view($this->cms_module_path().'/manage_mahasiswa_view', $output,
            $this->cms_complete_navigation_name('manage_mahasiswa'));

    }

    public function before_insert($post_array){
		return TRUE;
	}

	public function after_insert($post_array, $primary_key){
		$success = $this->after_insert_or_update($post_array, $primary_key);
		return $success;
	}

	public function before_update($post_array, $primary_key){
		return TRUE;
	}

	public function after_update($post_array, $primary_key){
		$success = $this->after_insert_or_update($post_array, $primary_key);
		return $success;
	}

	public function before_delete($primary_key){

		return TRUE;
	}

	public function after_delete($primary_key){
		return TRUE;
	}

	public function after_insert_or_update($post_array, $primary_key){

        return TRUE;
	}



}