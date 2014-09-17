<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Manage_Pasien
 *
 * @author No-CMS Module Generator
 */
class Manage_Pasien extends CMS_Priv_Strict_Controller {

    protected $URL_MAP = array();

    public function cms_complete_table_name($table_name){
        $this->load->helper($this->cms_module_path().'/function');
        if(function_exists('cms_complete_table_name')){
            return cms_complete_table_name($table_name);
        }else{
            return parent::cms_complete_table_name($table_name);
        }
    }

    private function make_crud(){
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // initialize groceryCRUD
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $crud = $this->new_crud();
        // this is just for code completion
        if (FALSE) $crud = new Extended_Grocery_CRUD();

        // check state & get primary_key
        $state = $crud->getState();
        $state_info = $crud->getStateInfo();
        $primary_key = isset($state_info->primary_key)? $state_info->primary_key : NULL;
        switch($state){
            case 'unknown': break;
            case 'list' : break;
            case 'add' : break;
            case 'edit' : break;
            case 'delete' : break;
            case 'insert' : break;
            case 'update' : break;
            case 'ajax_list' : break;
            case 'ajax_list_info': break;
            case 'insert_validation': break;
            case 'update_validation': break;
            case 'upload_file': break;
            case 'delete_file': break;
            case 'ajax_relation': break;
            case 'ajax_relation_n_n': break;
            case 'success': break;
            case 'export': break;
            case 'print': break;
        }

        // unset things
        $crud->unset_jquery();
        $crud->unset_read();
        // $crud->unset_add();
        // $crud->unset_edit();
        // $crud->unset_delete();
        // $crud->unset_list();
        // $crud->unset_back_to_list();
        // $crud->unset_print();
        // $crud->unset_export();

        // set custom grocery crud model, uncomment to use.
        /*
        $this->load->model('grocery_crud_model');
        $this->load->model('grocery_crud_generic_model');
        $this->load->model('grocery_crud_automatic_model');
        $crud->set_model($this->cms_module_path().'/grocerycrud_pasien_model');
        */

        // adjust groceryCRUD's language to No-CMS's language
        $crud->set_language($this->cms_language());

        // table name
        $crud->set_table($this->cms_complete_table_name('pasien'));

        // primary key
        $crud->set_primary_key('id');

        // set subject
        $crud->set_subject('Pasien');

        // displayed columns on list
        $crud->columns('nama_lengkap','nama_panggilan','foto','alamat','telepon','hp','pin_bbm','email','tanggal_lahir','tempat_lahir','usia','berat_badan','tinggi_badan','kelainan_fisik','golongan_darah','jumlah_anak','menyusui','lama_menyusui','nenek_kanker','umur_diagnosa_nenek_kanker','ibu_kanker','umur_diagnosa_ibu_kanker','bibi_kanker','umur_diagnosa_bibi_kanker','saudara_kanker','umur_diagnosa_saudara_kanker','kemenakan_kanker','umur_diagnosa_kemenakan_kanker','anak_kanker','umur_diagnosa_anak_kanker','sakit','lama_sakit','penemu_sakit','benjolan','lama_benjolan','penemu_benjolan','pengerasan','lama_pengerasan','penemu_pengerasan','kategori');
        // displayed columns on edit operation
        $crud->edit_fields('nama_lengkap','nama_panggilan','foto','alamat','telepon','hp','pin_bbm','email','tanggal_lahir','tempat_lahir','usia','berat_badan','tinggi_badan','kelainan_fisik','golongan_darah','jumlah_anak','menyusui','lama_menyusui','nenek_kanker','umur_diagnosa_nenek_kanker','ibu_kanker','umur_diagnosa_ibu_kanker','bibi_kanker','umur_diagnosa_bibi_kanker','saudara_kanker','umur_diagnosa_saudara_kanker','kemenakan_kanker','umur_diagnosa_kemenakan_kanker','anak_kanker','umur_diagnosa_anak_kanker','sakit','lama_sakit','penemu_sakit','benjolan','lama_benjolan','penemu_benjolan','pengerasan','lama_pengerasan','penemu_pengerasan','kategori');
        // displayed columns on add operation
        $crud->add_fields('nama_lengkap','nama_panggilan','foto','alamat','telepon','hp','pin_bbm','email','tanggal_lahir','tempat_lahir','usia','berat_badan','tinggi_badan','kelainan_fisik','golongan_darah','jumlah_anak','menyusui','lama_menyusui','nenek_kanker','umur_diagnosa_nenek_kanker','ibu_kanker','umur_diagnosa_ibu_kanker','bibi_kanker','umur_diagnosa_bibi_kanker','saudara_kanker','umur_diagnosa_saudara_kanker','kemenakan_kanker','umur_diagnosa_kemenakan_kanker','anak_kanker','umur_diagnosa_anak_kanker','sakit','lama_sakit','penemu_sakit','benjolan','lama_benjolan','penemu_benjolan','pengerasan','lama_pengerasan','penemu_pengerasan','kategori');

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HINT: Put Tabs (if needed)
        // usage:
        //     $crud->set_tabs(array(
        //        'First Tab Caption'  => $how_many_field_on_first_tab,
        //        'Second Tab Caption' => $how_many_field_on_second_tab,
        //     ));
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        // caption of each columns
        $crud->display_as('nama_lengkap','Nama Lengkap');
        $crud->display_as('nama_panggilan','Nama Panggilan');
        $crud->display_as('foto','Foto');
        $crud->display_as('alamat','Alamat');
        $crud->display_as('telepon','Telepon');
        $crud->display_as('hp','Hp');
        $crud->display_as('pin_bbm','Pin Bbm');
        $crud->display_as('email','Email');
        $crud->display_as('tanggal_lahir','Tanggal Lahir');
        $crud->display_as('tempat_lahir','Tempat Lahir');
        $crud->display_as('usia','Usia');
        $crud->display_as('berat_badan','Berat Badan');
        $crud->display_as('tinggi_badan','Tinggi Badan');
        $crud->display_as('kelainan_fisik','Kelainan Fisik');
        $crud->display_as('golongan_darah','Golongan Darah');
        $crud->display_as('jumlah_anak','Jumlah Anak');
        $crud->display_as('menyusui','Menyusui');
        $crud->display_as('lama_menyusui','Lama Menyusui');
        $crud->display_as('nenek_kanker','Nenek Kanker');
        $crud->display_as('umur_diagnosa_nenek_kanker','Umur Diagnosa Nenek Kanker');
        $crud->display_as('ibu_kanker','Ibu Kanker');
        $crud->display_as('umur_diagnosa_ibu_kanker','Umur Diagnosa Ibu Kanker');
        $crud->display_as('bibi_kanker','Bibi Kanker');
        $crud->display_as('umur_diagnosa_bibi_kanker','Umur Diagnosa Bibi Kanker');
        $crud->display_as('saudara_kanker','Saudara Kanker');
        $crud->display_as('umur_diagnosa_saudara_kanker','Umur Diagnosa Saudara Kanker');
        $crud->display_as('kemenakan_kanker','Kemenakan Kanker');
        $crud->display_as('umur_diagnosa_kemenakan_kanker','Umur Diagnosa Kemenakan Kanker');
        $crud->display_as('anak_kanker','Anak Kanker');
        $crud->display_as('umur_diagnosa_anak_kanker','Umur Diagnosa Anak Kanker');
        $crud->display_as('sakit','Sakit');
        $crud->display_as('lama_sakit','Lama Sakit');
        $crud->display_as('penemu_sakit','Penemu Sakit');
        $crud->display_as('benjolan','Benjolan');
        $crud->display_as('lama_benjolan','Lama Benjolan');
        $crud->display_as('penemu_benjolan','Penemu Benjolan');
        $crud->display_as('pengerasan','Pengerasan');
        $crud->display_as('lama_pengerasan','Lama Pengerasan');
        $crud->display_as('penemu_pengerasan','Penemu Pengerasan');
        $crud->display_as('kategori','Kategori');

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HINT: Put required field validation codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/required_fields)
        // eg:
        //      $crud->required_fields( $field1, $field2, $field3, ... );
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HINT: Put required field validation codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/unique_fields)
        // eg:
        //      $crud->unique_fields( $field1, $field2, $field3, ... );
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HINT: Put field validation codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/set_rules)
        // eg:
        //      $crud->set_rules( $field_name , $caption, $filter );
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HINT: Put set relation (lookup) codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/set_relation)
        // eg:
        //      $crud->set_relation( $field_name , $related_table, $related_title_field , $where , $order_by );
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HINT: Put set relation_n_n (detail many to many) codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/set_relation_n_n)
        // eg:
        //      $crud->set_relation_n_n( $field_name, $relation_table, $selection_table, $primary_key_alias_to_this_table,
        //          $primary_key_alias_to_selection_table , $title_field_selection_table, $priority_field_relation );
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HINT: Put custom field type here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/field_type)
        // eg:
        //      $crud->field_type( $field_name , $field_type, $value  );
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $crud->field_type('golongan_darah', 'enum', array('A','AB','B','O'));
        $crud->field_type('kategori', 'enum', array('A1','A2','B1','B2','C'));



        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HINT: Put callback here
        // (documentation: httm://www.grocerycrud.com/documentation/options_functions)
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $crud->callback_before_insert(array($this,'_before_insert'));
        $crud->callback_before_update(array($this,'_before_update'));
        $crud->callback_before_delete(array($this,'_before_delete'));
        $crud->callback_after_insert(array($this,'_after_insert'));
        $crud->callback_after_update(array($this,'_after_update'));
        $crud->callback_after_delete(array($this,'_after_delete'));



        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HINT: Put custom error message here
        // (documentation: httm://www.grocerycrud.com/documentation/set_lang_string)
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // $crud->set_lang_string('delete_error_message', 'Cannot delete the record');
        // $crud->set_lang_string('update_error',         'Cannot edit the record'  );
        // $crud->set_lang_string('insert_error',         'Cannot add the record'   );

        $this->crud = $crud;
        return $crud;
    }

    public function index(){
        $crud = $this->make_crud();
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // render
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $output = $crud->render();
        $this->view($this->cms_module_path().'/manage_pasien_view', $output,
            $this->cms_complete_navigation_name('manage_pasien'));
    }

    public function delete_selection(){
        $crud = $this->make_crud();
        if(!$crud->unset_delete){
            $id_list = json_decode($this->input->post('data'));
            foreach($id_list as $id){
                if($this->_before_delete($id)){
                    $this->db->delete($this->cms_complete_table_name('pasien'),array('id'=>$id));
                    $this->_after_delete($id);
                }
            }
        }
    }

    public function _before_insert($post_array){
        $post_array = $this->_before_insert_or_update($post_array);
        // HINT : Put your code here
        return $post_array;
    }

    public function _after_insert($post_array, $primary_key){
        $success = $this->_after_insert_or_update($post_array, $primary_key);
        // HINT : Put your code here
        return $success;
    }

    public function _before_update($post_array, $primary_key){
        $post_array = $this->_before_insert_or_update($post_array, $primary_key);
        // HINT : Put your code here
        return $post_array;
    }

    public function _after_update($post_array, $primary_key){
        $success = $this->_after_insert_or_update($post_array, $primary_key);
        // HINT : Put your code here
        return $success;
    }

    public function _before_delete($primary_key){

        return TRUE;
    }

    public function _after_delete($primary_key){
        return TRUE;
    }

    public function _after_insert_or_update($post_array, $primary_key){

        return TRUE;
    }

    public function _before_insert_or_update($post_array, $primary_key=NULL){
        return $post_array;
    }



}